<?php

declare(strict_types=1);

class AuctionController extends BaseController
{
    private AuctionService $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
    }



    /**
     * Route: index.php?action=marketplace
     * Handles both initial Page Load (HTML) and AJAX Search/Filter (JSON).
     */
    public function marketplace(): void
    {
        $input = $this->getInput();
        
        $search   = $input['q'] ?? '';
        $category = $input['category'] ?? 'all';
        $status   = $input['status'] ?? 'Active';
        $minPrice = isset($input['min']) && is_numeric($input['min']) ? (float)$input['min'] : null;
        $maxPrice = isset($input['max']) && is_numeric($input['max']) ? (float)$input['max'] : null;
        $sort     = $input['sort'] ?? 'newest';

        $items = $this->auctionService->searchItems(
            $search, $category, $status, $minPrice, $maxPrice, $sort
        );

        // We only send json if it is ajaxx
        if ($this->isAjax())
            $this->jsonResponse($items); 
        else 
            require __DIR__ . '/../view/marketplace.php';  // PLACEHOLDER ##################################################
    }




    /**
     * Route: index.php?action=item_details&id=123
     */
    public function viewItem(): void
    {
        $itemId = (int)($_GET['id'] ?? 0);
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0; 

        try {
            $itemDetails = $this->auctionService->getItemDetailsView($itemId, $userId);

            if (!$itemDetails) {
                http_response_code(404);
                echo "<h1>404 Not Found</h1><p>The item you requested does not exist.</p>";
                return;
            }

            require __DIR__ . '/../view/item_details.php';

        } catch (Throwable $e) { // CHANGED: Exception -> Throwable
            http_response_code(500);
            echo "<h1>500 Internal Server Error</h1><p>Error processing item request: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }






    /**
     * Route: index.php?action=place_bid
     */
    public function placeBid(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int)($input['item_id'] ?? 0);
            $amount = (float)($input['amount'] ?? 0.0);

            if ($itemId <= 0 || $amount <= 0)
                throw new Exception("Invalid bid data.");

            $result = $this->auctionService->placeBid($userId, $itemId, $amount);
            $this->jsonResponse($result); 
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    

    

    /**
     * Route: index.php?action=create_listing
     * SERVES 2 PURPOSE, NAVIGATING INTO THE 'CREATE LISTING' AND ACTUALLY SUBMITTING THE FORM for the CREATION OF LISTING
     */
    public function createListing(): void
    {
        $userId = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // We access $_POST directly here because file uploads are multipart/form-data
                // BaseController::getInput() might return JSON or POST array, but files are in $_FILES
                $input = $_POST; 

                // Format Files array properly
                // PHP $_FILES structure is weird for multiple files: ['name'][0], ['name'][1]
                // We normalize it into an array of file objects
                $files = [];
                if (!empty($_FILES['images']['name'][0])) {
                    $count = count($_FILES['images']['name']);
                    for ($i = 0; $i < $count; $i++) {
                        $files[] = [
                            'name'     => $_FILES['images']['name'][$i],
                            'type'     => $_FILES['images']['type'][$i],
                            'tmp_name' => $_FILES['images']['tmp_name'][$i],
                            'error'    => $_FILES['images']['error'][$i],
                            'size'     => $_FILES['images']['size'][$i],
                        ];
                    }
                }

                $newId = $this->auctionService->createListing(
                    $userId,
                    $input['title'],
                    $input['description'],
                    (float)$input['starting_bid'],
                    $input['category'],
                    $input['end_time'],
                    $files
                );

                $this->jsonResponse(['success' => true, 'item_id' => $newId]);

            } catch (Exception $e) {
                $this->errorResponse($e->getMessage());
            }
        } else {
            require __DIR__ . '/../view/create_listing.php'; // PLACEHOLDER ########################################
        }
    }





    /**
     * Route: index.php?action=cancel_auction
     */
    public function cancelAuction(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int)($input['item_id'] ?? 0);
            
            $this->auctionService->cancelAuction($userId, $itemId);
            
            $this->jsonResponse(['success' => true, 'message' => 'Auction cancelled successfully.']);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=toggle_watchlist
     */
    public function toggleWatchlist(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int)($input['item_id'] ?? 0);

            $isWatching = $this->auctionService->toggleWatchlist($userId, $itemId);

            $this->jsonResponse([
                'success' => true,
                'is_watching' => $isWatching, // Frontend updates UI (Heart filled/empty)
                'message' => $isWatching ? 'Added to watchlist' : 'Removed from watchlist'
            ]);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}