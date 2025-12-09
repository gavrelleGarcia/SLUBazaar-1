<?php
/** @var ItemDetailsDTO $itemDetails */
// This DTO is passed from AuctionController::viewItem()
if (!isset($itemDetails)) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>Item data is missing.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($itemDetails->title); ?> | SLU Bazaar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/user/item_details.css">
    <link rel="stylesheet" href="/assets/css/global.css"> 
</head>
<body>

    <div class="bg-layer-stripes"></div>
    <div class="bg-layer-building"></div>

    <nav class="sidebar">
        <div class="nav-icons">
            <a href="index.php?action=profile" title="Profile"><i class="fa-regular fa-user"></i></a>
            <a href="index.php?action=marketplace" title="Home"><i class="fa-solid fa-house"></i></a>
            <a href="index.php?action=create_listing" title="New Listing"><i class="fa-solid fa-circle-plus"></i></a>
            <a href="index.php?action=chat" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
            <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
        <div class="bottom-user">
             <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fname'] ?? 'User'); ?>&background=random" alt="Me">
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="site-branding">
            <h1><?php echo htmlspecialchars($itemDetails->title); ?></h1>
            <p>Viewing details for <?php echo htmlspecialchars($itemDetails->category); ?></p>
        </div>

        <div class="item-detail-content">
            <p style="padding: 50px; background: white; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
                **Item Details UI Goes Here**
                <br>
                Data successfully loaded for item ID: **<?php echo $itemDetails->itemId; ?>**
            </p>
        </div>
    </div>

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/item_details.js?v=<?php echo time(); ?>"></script>

</body>
</html>