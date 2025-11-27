<div class="filter-group">
    <label>Sort By</label>
    <select name="sort" onchange="this.form.submit()">
        <!-- Default -->
        <option value="newest" <?= isSelected($criteria->sortBy, 'newest') ?>>Newest Listed</option>
        <option value="created_asc" <?= isSelected($criteria->sortBy, 'created_asc') ?>>Oldest Listed</option>

        <!-- Price -->
        <option value="bid_asc" <?= isSelected($criteria->sortBy, 'bid_asc') ?>>Price: Low to High</option>
        <option value="bid_desc" <?= isSelected($criteria->sortBy, 'bid_desc') ?>>Price: High to Low</option>

        <!-- Auction End -->
        <option value="end_asc" <?= isSelected($criteria->sortBy, 'end_asc') ?>>Ending Soonest</option>
        <option value="end_desc" <?= isSelected($criteria->sortBy, 'end_desc') ?>>Ending Latest</option>

        <!-- Seller Rating -->
        <option value="rating_desc" <?= isSelected($criteria->sortBy, 'rating_desc') ?>>Seller Rating: High to Low</option>
        <option value="rating_asc" <?= isSelected($criteria->sortBy, 'rating_asc') ?>>Seller Rating: Low to High</option>
    </select>
</div>

newest
created_asc
bid_asc
bid_desc
end_asc
end_desc
rating_desc
rating_asc