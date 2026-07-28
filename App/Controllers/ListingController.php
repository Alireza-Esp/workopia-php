<?php 
namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController {
    protected $db;

    public function __construct() {
        $db_config = require basePath('config/db.php');
        $this->db = new Database(config: $db_config);
    }

    public function index() {
        $listings = $this->db->query("SELECT * FROM listings")->fetchAll();

        loadView(
            "listings/index",
            [
                'listings' => $listings
            ]
        );
    }

    public function create() {
        loadView("listings/create");
    }

    public function show($params) {
        $id = $params['id'] ?? '';

        $queryParams = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        if (!$listing) {
            ErrorController::notFound();
        }
        
        loadView(
            "listings/show",
            [
                'listing' => $listing
            ]
        );
    }

    public function store(): void {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = 1;

        $newListingData = array_map('sanitize', $newListingData);
    
        $requiredFields = ['title', 'description', 'company', 'email'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }

        if (!empty($errors)) {
            loadView(
                'listings/create',
                [
                    'errors' => $errors,
                    'listing' => $newListingData
                ]
                );
        } else {
            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            redirect('/listings');

        }
    }

    public function destroy($params): void {
        $id = $params['id'];

        $queryParams = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        if (empty($listing)) {
            ErrorController::notFound();
            return;
        }

        $this->db->query("DELETE FROM listings WHERE id = :id", $params);

        $_SESSION['success_message'] = "Listing deleted successfully";

        redirect('/listings');
    }
}