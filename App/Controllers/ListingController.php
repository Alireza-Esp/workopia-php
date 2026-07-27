<?php 
namespace App\Controllers;

use Framework\Database;

class ListingController {
    protected $db;
    public function __construct() {
        $db_config = require basePath('config/db.php');
        $this->db = new Database(config: $db_config);
    }

    public function index() {
        $listings = $this->db->query("SELECT * FROM listings LIMIT 6")->fetchAll();

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
    
        inspectAndDie($newListingData);
    }
    
}