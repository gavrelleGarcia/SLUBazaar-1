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
    <title>Dashboard | Market</title>

    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/user/marketplace.css">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/user/itemdetails.css">
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

<div class="main-wrapper" id="item-modal">

    <div class="item-container">

        <!-- LEFT SIDE: IMAGES -->
        <div class="left-container">

            <!-- Main Image -->
            <div class="item-image-wrapper">
                <?php if (!empty($itemDetails->images)): ?>
                    <img src="<?= htmlspecialchars($itemDetails->images[0]) ?>" alt="item image">
                <?php else: ?>
                    <p>No images available for this item.</p>
                <?php endif; ?>
            </div>

            <!-- Image Selector -->
            <?php if (!empty($itemDetails->images)): ?>
                <div class="image-selector">
                    <?php foreach ($itemDetails->images as $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="thumbnail">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT SIDE: DETAILS -->
        <div class="right-container">

            <h2><?= htmlspecialchars($itemDetails->title) ?></h2>

            <p><?= nl2br(htmlspecialchars($itemDetails->description)) ?></p>

            <div class="item-meta">
                <p><strong>Status:</strong> <?= htmlspecialchars($itemDetails->status) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($itemDetails->category) ?></p>
                <p><strong>Current Price:</strong> ₱<?= number_format($itemDetails->currentPrice, 2) ?></p>
            </div>

         <!-- PLACE BID BUTTON -->
<div class="place-bid-wrapper">
    <button id="openBidPopup" class="place-bid-btn" type="button">Place Bid</button>
</div>

<!-- BID POPUP MODAL -->
<div id="bidPopup" class="bid-popup-overlay" style="display:none;">
    <div class="bid-popup-content">
        <span id="closeBidPopup" class="close-popup">&times;</span>
        <form id="bidForm" action="/index.php?action=place_bid" method="POST">
            <input type="hidden" name="item_id" value="<?= $itemDetails->id ?>">
            <label for="bid_amount">Enter your bid:</label>
            <input id="bid_amount" type="number" 
                   name="bid_amount" 
                   min="<?= $itemDetails->minNextBid ?>" 
                   step="1" 
                   required>
            <button class="place-bid-btn" type="submit">Submit Bid</button>
        </form>
    </div>
</div>


        </div>
    </div>


<!-- BIDDING SECTION -->
<div class="bidding-section">
    <div class="bidding-card left-card">

        <div class="bidding-inner">

            <div class="bidding-left">
                <h2>Bidding:</h2>
                <div class="bid-info">
                    <p><strong>Current Bidder:</strong>
                        <?= htmlspecialchars($itemDetails->currentBidder ?? "None") ?>
                    </p>

                    <p><strong>Highest Bidder:</strong>
                        <?= htmlspecialchars($itemDetails->highestBidder ?? "None") ?>
                    </p>
                </div>
            </div>

            <!-- BID HISTORY INSIDE BIDDING SECTION -->
            <div class="history-list">
                <h3>Bidding History</h3>
                <p>No bidding history available.</p>
                
            </div>

        </div>

    </div>
</div>


<script src="/assets/js/utils.js"></script>
<script src="/assets/js/marketplace.js?v=<?= time(); ?>"></script>

</body>
</html>
