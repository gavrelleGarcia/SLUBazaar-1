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
    <link rel="stylesheet" href="/assets\css\user\itemdetails.css">

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
        <!-- Image still static need to change to dynamic -->
        <div class="left-container">
            <div class="item-image-wrapper">
<?php if (!empty($itemDetails->images)): ?>
    <?php foreach ($itemDetails->images as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="image">
    <?php endforeach; ?>
<?php else: ?>
    <p>No images available for this item.</p>
<?php endif; ?>
            </div>
         

            <div class="image-selector">
                <? foreach ($itemDetails->images as $img): ?>
                    <img src="<? htmlspecialchars($img)?>" alt="image">
            </div>
        </div>

        <div class="right-container">
            <h2><?=  htmlspecialchars($itemDetails->title) ?></h2>
              <p> <?= nl2br(htmlspecialchars($itemDetails->description)) ?></p>

            <div class="item-meta">
                <p><strong>Status:</strong> <?= htmlspecialchars($itemDetails->status)?></p>
                <p><strong>Category:</strong><?= htmlspecialchars($itemDetails->category) ?></p>
                <p><strong>Price:</strong><?= htmlspecialchars($itemDetails->currentPrice) ?></p>
            </div>
            
    <!-- NEW BUTTON HERE -->
  <div class="place-bid-wrapper">
    <form action="/index.php?action=place_bid" method="POST" class="place-bid-wrapper">
    <input type="hidden" name="item_id" value="<?= $itemDetails->id ?>">
    <input type="number" name="bid_amount" min="<?= $itemDetails->minNextBid ?>" step="1" required>
    <button class="place-bid-btn" type="submit">Place Bid</button>
    </form>
</div>
        </div>

    </div> <!-- ✅ FIXED: This closing tag was missing -->


    <!-- BIDDING SECTION (Now works properly) -->
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

<div class="history-list">
    <?php if (isset($itemDetails->bidHistory) && is_array($itemDetails->bidHistory)): ?>
        <?php foreach ($itemDetails->bidHistory as $bid): ?>
            <p>
                <strong><?= htmlspecialchars($bid['name'] ?? 'Unknown bidder') ?></strong> — 
                ₱<?= number_format($bid['amount'] ?? 0, 2) ?>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No bidding history available.</p>
    <?php endif; ?>
</div>


            </div>

        </div>

    </div>

</div>






    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>

</body>
</html>