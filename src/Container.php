<?php

declare(strict_types=1);

// Enums
require_once __DIR__ . '/model/enum/AccountStatus.php';
require_once __DIR__ . '/model/enum/Category.php';
require_once __DIR__ . '/model/enum/ItemStatus.php';
require_once __DIR__ . '/model/enum/NotificationType.php';
require_once __DIR__ . '/model/enum/ConversationStatus.php';
require_once __DIR__ . '/model/enum/ReportStatus.php';
require_once __DIR__ . '/model/enum/ReportType.php';
require_once __DIR__ . '/model/enum/Role.php';

// Models
require_once __DIR__ . '/model/User.php';
require_once __DIR__ . '/model/Item.php';
require_once __DIR__ . '/model/Bid.php';
require_once __DIR__ . '/model/Rating.php';
require_once __DIR__ . '/model/Conversation.php';
require_once __DIR__ . '/model/Message.php';
require_once __DIR__ . '/model/Report.php';
require_once __DIR__ . '/model/Watchlist.php';
require_once __DIR__ . '/model/Notification.php';

// DTOs
require_once __DIR__ . '/dto/internal/BidRowDTO.php';
require_once __DIR__ . '/dto/internal/ItemRowDTO.php';
require_once __DIR__ . '/dto/request/SearchItemsRequestDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemCardDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemDetailsDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemPageBidDTO.php';
require_once __DIR__ . '/dto/response/Admin/AdminUserTableRowDTO.php';
require_once __DIR__ . '/dto/response/Notification/NotificationDTO.php';
require_once __DIR__ . '/dto/response/Messaging/MessagesDTO.php';
require_once __DIR__ . '/dto/response/Messaging/ConversationDTO.php';
require_once __DIR__ . '/dto/response/Profile/ActiveBidCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/ClaimItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/HistoryItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/RatingCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/SellerListingCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/SoldItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/ToHandoverItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/UnsoldItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/WatchlistItemDTO.php';

// Repositories
require_once __DIR__ . '/repository/UserRepository.php';
require_once __DIR__ . '/repository/ItemRepository.php';
require_once __DIR__ . '/repository/BidRepository.php';
require_once __DIR__ . '/repository/RatingRepository.php';
require_once __DIR__ . '/repository/NotificationRepository.php';
require_once __DIR__ . '/repository/WatchlistRepository.php';
require_once __DIR__ . '/repository/ConversationRepository.php';
require_once __DIR__ . '/repository/MessageRepository.php';
require_once __DIR__ . '/repository/ReportRepository.php';

// Services
require_once __DIR__ . '/service/NotificationService.php';
require_once __DIR__ . '/service/AuthService.php';
require_once __DIR__ . '/service/ChatService.php';
require_once __DIR__ . '/service/UserService.php';
require_once __DIR__ . '/service/AuctionService.php';
require_once __DIR__ . '/service/ModerationService.php';

// Controllers
require_once __DIR__ . '/controller/BaseController.php';
require_once __DIR__ . '/controller/AuthController.php';
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/controller/AuctionController.php';
require_once __DIR__ . '/controller/ChatController.php';
require_once __DIR__ . '/controller/AdminController.php';


class Container
{
    private array $services = []; 
    private ?mysqli $db = null;
    
    private array $dbConfig; 

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    
    public function getDb(): mysqli
    {
        if ($this->db === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $this->db = new mysqli(
                    $this->dbConfig['host'], 
                    $this->dbConfig['user'], 
                    $this->dbConfig['pass'], 
                    $this->dbConfig['name']
                );
                $this->db->set_charset("utf8mb4");
            } catch (mysqli_sql_exception $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
                exit;
            }
        }
        return $this->db;
    }





    // =========================================================================
    //  REPOSITORIES
    // =========================================================================

    public function getUserRepo(): UserRepository
    {
        if (!isset($this->services['userRepo']))
            $this->services['userRepo'] = new UserRepository($this->getDb());
        return $this->services['userRepo'];
    }

    public function getItemRepo(): ItemRepository
    {
        if (!isset($this->services['itemRepo']))
            $this->services['itemRepo'] = new ItemRepository($this->getDb());
        return $this->services['itemRepo'];
    }

    public function getBidRepo(): BidRepository
    {
        if (!isset($this->services['bidRepo']))
            $this->services['bidRepo'] = new BidRepository($this->getDb());
        return $this->services['bidRepo'];
    }

    public function getRatingRepo(): RatingRepository
    {
        if (!isset($this->services['ratingRepo']))
            $this->services['ratingRepo'] = new RatingRepository($this->getDb());
        return $this->services['ratingRepo'];
    }

    public function getNotifRepo(): NotificationRepository
    {
        if (!isset($this->services['notifRepo']))
            $this->services['notifRepo'] = new NotificationRepository($this->getDb());
        return $this->services['notifRepo'];
    }

    public function getWatchlistRepo(): WatchlistRepository
    {
        if (!isset($this->services['watchlistRepo']))
            $this->services['watchlistRepo'] = new WatchlistRepository($this->getDb());
        return $this->services['watchlistRepo'];
    }

    public function getConvoRepo(): ConversationRepository
    {
        if (!isset($this->services['convoRepo']))
            $this->services['convoRepo'] = new ConversationRepository($this->getDb());
        return $this->services['convoRepo'];
    }

    public function getMessageRepo(): MessageRepository
    {
        if (!isset($this->services['messageRepo']))
            $this->services['messageRepo'] = new MessageRepository($this->getDb());
        return $this->services['messageRepo'];
    }

    public function getReportRepo(): ReportRepository
    {
        if (!isset($this->services['reportRepo']))
            $this->services['reportRepo'] = new ReportRepository($this->getDb());
        return $this->services['reportRepo'];
    }



    
    // =========================================================================
    //  SERVICES (Inject Repositories)
    // =========================================================================

    public function getNotifService(): NotificationService
    {
        if (!isset($this->services['notifService']))
            $this->services['notifService'] = new NotificationService($this->getNotifRepo());
        return $this->services['notifService'];
    }

    public function getAuthService(): AuthService
    {
        if (!isset($this->services['authService']))
            $this->services['authService'] = new AuthService($this->getUserRepo());
        return $this->services['authService'];
    }

    public function getChatService(): ChatService
    {
        if (!isset($this->services['chatService'])) {
            $this->services['chatService'] = new ChatService(
                $this->getConvoRepo(),
                $this->getMessageRepo(),
                $this->getItemRepo()
            );
        }
        return $this->services['chatService'];
    }

    public function getUserService(): UserService
    {
        if (!isset($this->services['userService'])) {
            $this->services['userService'] = new UserService(
                $this->getUserRepo(),
                $this->getItemRepo(),
                $this->getBidRepo(),
                $this->getRatingRepo(),
                $this->getWatchlistRepo()
            );
        }
        return $this->services['userService'];
    }

    public function getAuctionService(): AuctionService
    {
        if (!isset($this->services['auctionService'])) {
            $this->services['auctionService'] = new AuctionService(
                $this->getItemRepo(),
                $this->getBidRepo(),
                $this->getUserRepo(),
                $this->getWatchlistRepo(),
                $this->getNotifService(),
                $this->getChatService()
            );
        }
        return $this->services['auctionService'];
    }

    public function getModerationService(): ModerationService
    {
        if (!isset($this->services['modService'])) {
            $this->services['modService'] = new ModerationService(
                $this->getReportRepo(),
                $this->getUserRepo(),
                $this->getItemRepo()
            );
        }
        return $this->services['modService'];
    }



    

    // =========================================================================
    //  CONTROLLERS (Inject Services)
    // =========================================================================

    public function getAuthController(): AuthController
    {
        if (!isset($this->services['authController']))
            $this->services['authController'] = new AuthController($this->getAuthService());
        return $this->services['authController'];
    }

    public function getUserController(): UserController
    {
        if (!isset($this->services['userController'])) {
            $this->services['userController'] = new UserController(
                $this->getUserService(),
                $this->getAuthService(),
                $this->getNotifService(),
                $this->getChatService()
            );
        }
        return $this->services['userController'];
    }

    public function getAuctionController(): AuctionController
    {
        if (!isset($this->services['auctionController']))
            $this->services['auctionController'] = new AuctionController($this->getAuctionService());
        return $this->services['auctionController'];
    }

    public function getChatController(): ChatController
    {
        if (!isset($this->services['chatController']))
            $this->services['chatController'] = new ChatController($this->getChatService());
        return $this->services['chatController'];
    }

    public function getAdminController(): AdminController
    {
        if (!isset($this->services['adminController']))
            $this->services['adminController'] = new AdminController($this->getModerationService());
        return $this->services['adminController'];
    }
}