<?php
require_once __DIR__ . '/../dbconn.php';

$base_path = $base_path ?? '';

$result = $conn->query("SELECT * FROM menu_details ORDER BY item_id DESC");

if ($result && $result->num_rows > 0):
    while ($item = $result->fetch_assoc()):
        $category = htmlspecialchars($item['category'] ?? '');
        $raw_image_url = $item['image_url'] ?? 'images/default.jpg';
        $image_url = htmlspecialchars($raw_image_url);
        if (!preg_match('/^(https?:\/\/|\/)/', $image_url)) {
            $img_src = $base_path . $image_url;
        } else {
            $img_src = $image_url;
        }

        $name = htmlspecialchars($item['name'] ?? 'Unnamed');
        $description = htmlspecialchars($item['description'] ?? '');
        $raw_price = $item['price'] ?? 0;
        $formatted_price = number_format($raw_price, 2);
        $itemId = (int) $item['item_id'];

        $available = $item['availability'] ? 'Available' : 'Unavailable';
        $badgeClass = $item['availability'] ? 'badge-success' : 'badge-secondary';
?>
    <div class="col-lg-4 col-md-6 special-grid <?= $category ?>">
        <!-- Tooltip added here -->
        <div class="gallery-single fix" title="Status: <?= $available ?>">
            <img src="<?= $img_src ?>" class="img-fluid" alt="<?= $name ?>">
            <div class="why-text">
                <h4><?= $name ?></h4>
                <p><?= $description ?></p>
                <h5>RM<?= $formatted_price ?></h5>

                <!-- Show availability badge -->
                <span class="badge <?= $badgeClass ?> mt-2"><?= $available ?></span>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="admin-controls mt-2">
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="<?= $itemId ?>"
                            data-name="<?= $name ?>"
                            data-description="<?= $description ?>"
                            data-price="<?= $raw_price ?>"
                            data-availability="<?= $item['availability'] ?>"
                            data-category="<?= $category ?>"
                            data-image="<?= $image_url ?>">
                            Edit
                        </button>
                        <a href="<?= $base_path ?>admin/delete_menu_item.php?id=<?= $itemId ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Are you sure you want to delete this item?')">
                        Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
    endwhile;
else:
    echo '<p class="text-center">No menu items found.</p>';
endif;
?>
