<?php 
namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;


class UserController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function login(): void {
        loadView('users/login');
    }

    public function create(): void {
        loadView('users/create');
    }

    public function store(): void {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $errors = [];

        // Validations
        if (!Validation::email($email)) {
            $errors['email'] = "Please enter a valid email address";
        }
        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = "Name should be between 2 and 50 characters";
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = "Password should be greater than 6 characters";
        }
        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = "Passwords should be equal";
        }

        // Check if errors exist
        if (!empty($errors)) {
            loadView(
                'users/create',
                [
                    'errors' => $errors,
                    'user' => [
                        'name' => $name,
                        'email' => $email,
                        'city' => $city,
                        'state' => $state,
                    ]
                ]
            );
            exit;
        }

        // Check if email exists
        $queryParams = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM  users WHERE email = :email', $queryParams)->fetch();

        if (!empty($user)) {
            $errors['email'] = 'This email already exists';
            loadView(
                'users/create',
                [
                    'errors' => $errors
                ]
            );
            exit;
        }

        // Create new user
        $queryParams = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $queryParams)->fetch();

        $userID = $this->db->conn->lastInsertId();

        Session::set(
            'user',
            [
                'id' => $userID,
                'name' => $name,
                'email' => $email
            ]
        );

        redirect('/');
    }

    public function logout(): void {
        Session::clearAll();

        $params = session_get_cookie_params();

        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }

    public function authenticate(): void {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email';
        }
        if (!Validation::string($password)) {
            $errors['password'] = 'Password must be between 6 and 50 characters';
        }

        if (!empty($errors)) {
            loadView(
                'users/login',
                [
                    'errors' => $errors
                ]
            );
            exit;
        }

        // Check for email
        $queryParams = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM  users WHERE email = :email', $queryParams)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect credintials';
            loadView(
                'users/login',
                [
                    'errors' => $errors
                ]
            );
            exit;
        }

        // Password check
        if (!password_verify($password, $user->password)) {
            $errors['email'] = 'Incorrect credintials';
            loadView(
                'users/login',
                [
                    'errors' => $errors
                ]
            );
            exit;
        }

        // Set user session
        Session::set(
            'user',
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        );

        redirect('/');
    }
}

