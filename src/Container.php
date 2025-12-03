<?php

declare(strict_types=1);

// Require your Controller classes here or ensure they are loaded in bootstrap
require_once '/../controller/AuthController.php';
// require_once __DIR__ . '/controller/AuctionController.php'; // etc...

class Container
{
    private array $services = [];
    private mysqli $db;

    public function __construct()
    {
        // 1. Establish DB Connection immediately
        $this->db = new mysqli('127.0.0.1', 'root', '', 'slubazaar');
        
        if ($this->db->connect_error) {
            // JSON response because this usually happens during an API request
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $this->db->connect_error]);
            exit;
        }
        $this->db->set_charset("utf8mb4");
    }

    // --- REPOSITORIES ---

    public function getUserRepo(): UserRepository
    {
        if (!isset($this->services['userRepo'])) {
            $this->services['userRepo'] = new UserRepository($this->db);
        }
        return $this->services['userRepo'];
    }

    // Add other repos here (getItemRepo, etc.)

    // --- SERVICES ---

    public function getAuthService(): AuthService
    {
        if (!isset($this->services['authService'])) {
            $this->services['authService'] = new AuthService($this->getUserRepo());
        }
        return $this->services['authService'];
    }

    // --- CONTROLLERS ---

    public function getAuthController(): AuthController
    {
        if (!isset($this->services['authController'])) {
            $this->services['authController'] = new AuthController($this->getAuthService());
        }
        return $this->services['authController'];
    }

    // Add other controllers here (getAuctionController, etc.)
}