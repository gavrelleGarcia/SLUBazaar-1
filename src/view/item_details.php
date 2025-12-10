<?php
/** @var ItemDetailsDTO $itemDetails */
// This DTO is passed from AuctionController::viewItem()
if (!isset($itemDetails)) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>Item data is missing.</p>";
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Market</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/user/marketplace.css">
    <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body>

    <div class="bg-layer-stripes"></div>
    <div class="bg-layer-building"></div>

    <nav class="sidebar">
        <div class="nav-icons">
            <a href="index.php?action=profile" title="Profile"><i class="fa-regular fa-user"></i></a>
            <a href="index.php?action=marketplace" class="active" title="Home"><i class="fa-solid fa-house"></i></a>
            <a href="index.php?action=create_listing" title="New Listing"><i class="fa-solid fa-circle-plus"></i></a>
            <a href="index.php?action=chat" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
        </div>
        <div class="bottom-user">
             <img src="https://ui-avatars.com/api/?name=User&background=random" alt="Me">
        </div>
    </nav>

    <div class="main-wrapper">
        <!-- TODO -->
    </div>

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>

</body>
</html>