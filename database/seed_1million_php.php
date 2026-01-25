<?php
/**
 * ZOLDIFY - PHP Script Seed 1 Triệu Dữ Liệu
 * Chạy nhanh hơn SQL procedure nhiều lần
 * 
 * Usage: php database/seed_1million_php.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database config from .env
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'zoldify');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');

// Tăng thời gian chạy
set_time_limit(0);
ini_set('memory_limit', '2G');

class MassSeeder
{
    private $pdo;
    private $batchSize = 5000;
    
    // Placeholder images - dùng số làm hình
    private $avatarUrl = 'https://i.pravatar.cc/150?u=';
    private $productImageUrl = 'https://picsum.photos/seed/prod';
    private $categoryImageUrl = 'https://placehold.co/400x300/';
    
    // Vietnamese sample data
    private $productNames = [
        'iPhone', 'Samsung Galaxy', 'Laptop Dell', 'Macbook Pro', 'Áo thun',
        'Quần jean', 'Giày Nike', 'Túi xách', 'Đồng hồ Casio', 'Tai nghe Sony',
        'Máy ảnh Canon', 'Bàn phím gaming', 'Chuột Logitech', 'Loa Bluetooth',
        'Máy tính bảng', 'Smart TV', 'Tủ lạnh', 'Máy giặt', 'Điều hòa', 'Quạt điện'
    ];
    
    private $productSuffixes = ['Pro', 'Max', 'Plus', 'Mini', 'Ultra', 'Lite', 'SE', 'X', 'S', ''];
    
    private $descriptions = [
        'Hàng chính hãng 100%. Bảo hành 12 tháng. Full phụ kiện.',
        'Mới 99%, ít sử dụng. Fullbox đầy đủ. Còn bảo hành.',
        'Đẹp như mới, không trầy xước. Thanh lý giá tốt.',
        'Hàng secondhand chất lượng. Đã test kỹ trước khi bán.',
        'Sản phẩm cao cấp, chất lượng vượt trội. Giá hợp lý.',
        'Thanh lý do không sử dụng. Còn mới 95%. Có hóa đơn.'
    ];
    
    private $reviewComments = [
        'Sản phẩm rất tốt, đúng mô tả!',
        'Giao hàng nhanh, đóng gói cẩn thận.',
        'Chất lượng OK với giá tiền.',
        'Seller thân thiện, sẽ ủng hộ tiếp.',
        'Hàng đẹp, giống hình. 5 sao!',
        'Tạm được, có vài lỗi nhỏ.',
        'Rất hài lòng với sản phẩm này!',
        'Đã mua nhiều lần, rất hài lòng!',
        'Giá hợp lý, chất lượng tốt.',
        'Shop uy tín, giao hàng đúng hẹn.'
    ];
    
    private $messageContents = [
        'Chào bạn, sản phẩm này còn hàng không?',
        'Giá có thể giảm được không ạ?',
        'Bạn ơi, giao hàng được không?',
        'Mình quan tâm sản phẩm này!',
        'OK, mình sẽ đặt hàng nhé.',
        'Cảm ơn bạn, đã nhận được hàng.',
        'Cho mình hỏi thêm về sản phẩm.',
        'Có size/màu khác không ạ?',
        'Bạn có thể gửi thêm ảnh không?',
        'Đã thanh toán rồi nhé!'
    ];
    
    private $provinces = [
        'TP. Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng',
        'Bình Dương', 'Đồng Nai', 'Long An', 'Bà Rịa-Vũng Tàu', 'Khánh Hòa'
    ];
    
    private $districts = [
        'Quận 1', 'Quận 3', 'Quận 7', 'Quận 10', 'Bình Thạnh',
        'Gò Vấp', 'Tân Bình', 'Phú Nhuận', 'Thủ Đức', 'Quận 12'
    ];
    
    private $banks = [
        'Vietcombank', 'Techcombank', 'MB Bank', 'VPBank', 'ACB',
        'BIDV', 'Agribank', 'Sacombank', 'TPBank', 'VIB'
    ];
    
    private $colors = ['333', '666', '999', 'c00', '0c0', '00c', 'cc0', '0cc', 'c0c', 'f60'];
    
    public function __construct()
    {
        $this->pdo = new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME),
            DB_USER,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_LOCAL_INFILE => true
            ]
        );
        
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("SET UNIQUE_CHECKS = 0");
        $this->pdo->exec("SET autocommit = 0");
    }
    
    public function run()
    {
        $startTime = microtime(true);
        
        echo "==============================================\n";
        echo "ZOLDIFY - SEEDING 1 MILLION RECORDS\n";
        echo "==============================================\n\n";
        
        $this->seedCategories(100);
        $this->seedUsers(1000000);
        $this->seedProducts(1000000);
        $this->seedProductImages(1000000);
        $this->seedOrders(1000000);
        $this->seedOrderDetails(1000000);
        $this->seedReviews(1000000);
        $this->seedFavorites(1000000);
        $this->seedMessages(1000000);
        $this->seedNotifications(1000000);
        $this->seedFollows(1000000);
        $this->seedInteractions(1000000);
        $this->seedSearchKeywords(100000);
        $this->seedUserAddresses(1000000);
        $this->seedWallets();
        
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        $this->pdo->exec("SET UNIQUE_CHECKS = 1");
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        echo "\n==============================================\n";
        echo "SEEDING COMPLETED in {$duration} seconds!\n";
        echo "==============================================\n";
        
        $this->showStats();
    }
    
    private function seedCategories($count)
    {
        echo "Seeding categories... ";
        $this->pdo->exec("TRUNCATE TABLE categories");
        
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (id, parent_id, name, description, tag, icon, image, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        for ($i = 1; $i <= $count; $i++) {
            $parentId = ($i <= 20) ? null : rand(1, 20);
            $color = $this->colors[array_rand($this->colors)];
            
            $stmt->execute([
                $i,
                $parentId,
                "Danh mục {$i}",
                "Mô tả chi tiết cho danh mục số {$i}. Chứa nhiều sản phẩm đa dạng.",
                "tag-{$i}",
                "https://placehold.co/64x64/{$color}/fff?text={$i}",
                "{$this->categoryImageUrl}{$color}/fff?text=Cat{$i}&font=roboto",
                $i
            ]);
        }
        
        $this->pdo->commit();
        echo "Done! ({$count} records)\n";
    }
    
    private function seedUsers($count)
    {
        echo "Seeding users... ";
        $this->pdo->exec("TRUNCATE TABLE users");
        
        $roles = ['buyer', 'buyer', 'buyer', 'buyer', 'buyer', 'buyer', 'seller', 'seller', 'seller', 'moderator'];
        $genders = ['male', 'female', 'other'];
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $role = $roles[array_rand($roles)];
            $gender = $genders[array_rand($genders)];
            $phone = '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            
            $values = array_merge($values, [
                $i,
                "User {$i} " . substr(md5(rand()), 0, 4),
                "user{$i}@zoldify.test",
                $passwordHash,
                $phone,
                rand(0, 1),
                $gender,
                "Số " . rand(1, 999) . ", Đường " . rand(1, 100) . ", Q." . rand(1, 12) . ", TP.HCM",
                $role,
                rand(0, 50000000),
                "{$this->avatarUrl}{$i}",
                rand(0, 1),
                rand(0, 1) ? 0 : (rand(0, 100) > 95 ? 1 : 0),
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO users (id, full_name, email, password, phone_number, phone_verified, gender, address, role, balance, avatar, email_verified, is_locked, last_seen) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nUsers done! ({$count} records)\n";
    }
    
    private function seedProducts($count)
    {
        echo "Seeding products... ";
        $this->pdo->exec("TRUNCATE TABLE products");
        
        $statuses = ['active', 'active', 'active', 'active', 'sold', 'hidden'];
        $conditions = ['new', 'like_new', 'good', 'fair'];
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $name = $this->productNames[array_rand($this->productNames)];
            $suffix = $this->productSuffixes[array_rand($this->productSuffixes)];
            $desc = $this->descriptions[array_rand($this->descriptions)];
            $status = $statuses[array_rand($statuses)];
            $condition = $conditions[array_rand($conditions)];
            
            $values = array_merge($values, [
                $i,
                rand(1, 1000000),
                rand(1, 100),
                "{$name} {$suffix} #{$i}",
                "Sản phẩm #{$i}. {$desc}",
                rand(50000, 50000000),
                rand(1, 100),
                rand(0, 10000),
                "{$this->productImageUrl}{$i}/800/800",
                $status,
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 730) . " days")),
                $condition
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO products (id, user_id, category_id, name, description, price, quantity, view_count, image, status, created_at, `condition`) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nProducts done! ({$count} records)\n";
    }
    
    private function seedProductImages($productCount)
    {
        echo "Seeding product_images (3 per product)... ";
        $this->pdo->exec("TRUNCATE TABLE product_images");
        
        $values = [];
        $placeholders = [];
        $total = 0;
        
        for ($productId = 1; $productId <= $productCount; $productId++) {
            for ($imgIdx = 1; $imgIdx <= 3; $imgIdx++) {
                $values = array_merge($values, [
                    $productId,
                    "https://picsum.photos/seed/p{$productId}i{$imgIdx}/800/800",
                    $imgIdx === 1 ? 1 : 0,
                    $imgIdx
                ]);
                
                $placeholders[] = "(?, ?, ?, ?)";
                $total++;
            }
            
            if ($productId % ($this->batchSize / 3) === 0 || $productId === $productCount) {
                $sql = "INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($productId, $productCount);
            }
        }
        
        echo "\nProduct images done! ({$total} records)\n";
    }
    
    private function seedOrders($count)
    {
        echo "Seeding orders... ";
        $this->pdo->exec("TRUNCATE TABLE orders");
        
        $statuses = ['pending', 'paid', 'confirmed', 'shipping', 'received', 'completed', 'completed', 'completed', 'cancelled'];
        $paymentMethods = ['cod', 'bank_transfer', 'payos'];
        $paymentStatuses = ['pending', 'paid', 'paid', 'paid', 'failed'];
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $buyerId = rand(1, 1000000);
            $sellerId = rand(1, 1000000);
            while ($sellerId === $buyerId) {
                $sellerId = rand(1, 1000000);
            }
            
            $total = rand(100000, 10000000);
            $platformFee = round($total * 0.05, 2);
            $sellerAmount = $total - $platformFee;
            $status = $statuses[array_rand($statuses)];
            
            $values = array_merge($values, [
                $i,
                $buyerId,
                $sellerId,
                $total,
                $platformFee,
                $sellerAmount,
                rand(15000, 65000),
                "Số " . rand(1, 999) . ", Đường " . rand(1, 100) . ", Q." . rand(1, 12) . ", TP.HCM",
                '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                "Nguyen Van " . chr(65 + rand(0, 25)),
                $status,
                $paymentMethods[array_rand($paymentMethods)],
                $paymentStatuses[array_rand($paymentStatuses)],
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO orders (id, buyer_id, seller_id, total_amount, platform_fee, seller_amount, shipping_fee, shipping_address, shipping_phone, shipping_name, status, payment_method, payment_status, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nOrders done! ({$count} records)\n";
    }
    
    private function seedOrderDetails($orderCount)
    {
        echo "Seeding order_details (1-4 per order)... ";
        $this->pdo->exec("TRUNCATE TABLE order_details");
        
        $values = [];
        $placeholders = [];
        $total = 0;
        
        for ($orderId = 1; $orderId <= $orderCount; $orderId++) {
            $itemsCount = rand(1, 4);
            
            for ($j = 1; $j <= $itemsCount; $j++) {
                $values = array_merge($values, [
                    $orderId,
                    rand(1, 1000000),
                    rand(1, 5),
                    rand(50000, 5000000)
                ]);
                
                $placeholders[] = "(?, ?, ?, ?)";
                $total++;
            }
            
            if ($orderId % ($this->batchSize / 2) === 0 || $orderId === $orderCount) {
                $sql = "INSERT INTO order_details (order_id, product_id, quantity, price_at_purchase) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($orderId, $orderCount);
            }
        }
        
        echo "\nOrder details done! ({$total} records)\n";
    }
    
    private function seedReviews($count)
    {
        echo "Seeding reviews... ";
        $this->pdo->exec("TRUNCATE TABLE reviews");
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $comment = $this->reviewComments[array_rand($this->reviewComments)];
            
            $values = array_merge($values, [
                $i,
                rand(1, 1000000),
                rand(1, 1000000),
                rand(1, 5),
                $comment,
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO reviews (id, reviewer_id, product_id, rating, comment, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nReviews done! ({$count} records)\n";
    }
    
    private function seedFavorites($count)
    {
        echo "Seeding favorites... ";
        $this->pdo->exec("TRUNCATE TABLE favorites");
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $values = array_merge($values, [
                $i,
                rand(1, 1000000),
                rand(1, 1000000),
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT IGNORE INTO favorites (id, user_id, product_id, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nFavorites done! ({$count} records)\n";
    }
    
    private function seedMessages($count)
    {
        echo "Seeding messages... ";
        $this->pdo->exec("TRUNCATE TABLE messages");
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $senderId = rand(1, 1000000);
            $receiverId = rand(1, 1000000);
            while ($receiverId === $senderId) {
                $receiverId = rand(1, 1000000);
            }
            
            $content = $this->messageContents[array_rand($this->messageContents)];
            
            $values = array_merge($values, [
                $i,
                $senderId,
                $receiverId,
                $content,
                rand(0, 1),
                rand(0, 10) > 8 ? 1 : 0,
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 180) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO messages (id, sender_id, receiver_id, content, is_read, has_attachment, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nMessages done! ({$count} records)\n";
    }
    
    private function seedNotifications($count)
    {
        echo "Seeding notifications... ";
        $this->pdo->exec("TRUNCATE TABLE notifications");
        
        $contents = [
            'Bạn có đơn hàng mới!',
            'Đơn hàng của bạn đã được xác nhận.',
            'Sản phẩm của bạn có người quan tâm.',
            'Có tin nhắn mới từ người mua.',
            'Đơn hàng đang được giao.',
            'Bạn nhận được đánh giá 5 sao!',
            'Thanh toán thành công.',
            'Có người theo dõi shop của bạn.',
            'Sản phẩm được thêm vào yêu thích.',
            'Đơn hàng đã hoàn thành!'
        ];
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $content = $contents[array_rand($contents)];
            
            $values = array_merge($values, [
                $i,
                rand(1, 1000000),
                $content,
                rand(0, 1),
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 90) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO notifications (id, user_id, content, is_read, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nNotifications done! ({$count} records)\n";
    }
    
    private function seedFollows($count)
    {
        echo "Seeding follows... ";
        $this->pdo->exec("TRUNCATE TABLE follows");
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $followerId = rand(1, 1000000);
            $followingId = rand(1, 1000000);
            while ($followingId === $followerId) {
                $followingId = rand(1, 1000000);
            }
            
            $values = array_merge($values, [
                $i,
                $followerId,
                $followingId,
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT IGNORE INTO follows (id, follower_id, following_id, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nFollows done! ({$count} records)\n";
    }
    
    private function seedInteractions($count)
    {
        echo "Seeding interactions... ";
        $this->pdo->exec("TRUNCATE TABLE interactions");
        
        $types = ['view', 'view', 'view', 'click'];
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $type = $types[array_rand($types)];
            
            $values = array_merge($values, [
                $i,
                rand(1, 1000000),
                rand(1, 1000000),
                $type,
                rand(1, 5),
                date('Y-m-d H:i:s', strtotime("-" . rand(0, 90) . " days"))
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT INTO interactions (id, user_id, product_id, interaction_type, score, created_at) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nInteractions done! ({$count} records)\n";
    }
    
    private function seedSearchKeywords($count)
    {
        echo "Seeding search_keywords... ";
        $this->pdo->exec("TRUNCATE TABLE search_keywords");
        
        $keywords = [
            'iphone', 'samsung', 'laptop', 'áo', 'quần', 'giày', 'túi xách', 'đồng hồ',
            'tai nghe', 'máy ảnh', 'điện thoại', 'máy tính', 'bàn phím', 'chuột',
            'loa', 'tivi', 'tủ lạnh', 'máy giặt', 'nồi cơm', 'quạt'
        ];
        
        $suffixes = ['mới', 'cũ', 'secondhand', 'chính hãng', 'giá rẻ', 'cao cấp', 'mini', 'pro', 'max', ''];
        
        $values = [];
        $placeholders = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $keyword = $keywords[array_rand($keywords)] . ' ' . $suffixes[array_rand($suffixes)];
            if (rand(0, 1)) {
                $keyword .= ' ' . $i;
            }
            
            $values = array_merge($values, [
                $i,
                trim($keyword),
                rand(1, 10000)
            ]);
            
            $placeholders[] = "(?, ?, ?)";
            
            if ($i % $this->batchSize === 0 || $i === $count) {
                $sql = "INSERT IGNORE INTO search_keywords (id, keyword, search_count) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($i, $count);
            }
        }
        
        echo "\nSearch keywords done! ({$count} records)\n";
    }
    
    private function seedUserAddresses($userCount)
    {
        echo "Seeding user_addresses (1-3 per user)... ";
        $this->pdo->exec("TRUNCATE TABLE user_addresses");
        
        $labels = ['Nhà riêng', 'Công ty', 'Nhà bạn'];
        
        $values = [];
        $placeholders = [];
        $total = 0;
        
        for ($userId = 1; $userId <= $userCount; $userId++) {
            $addrCount = rand(1, 3);
            
            for ($j = 1; $j <= $addrCount; $j++) {
                $province = $this->provinces[array_rand($this->provinces)];
                $district = $this->districts[array_rand($this->districts)];
                $ward = "Phường " . rand(1, 20);
                $street = "Số " . rand(1, 999) . ", Đường " . rand(1, 50);
                
                $values = array_merge($values, [
                    $userId,
                    $labels[$j - 1],
                    "Người nhận {$userId}-{$j}",
                    '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                    $province,
                    $district,
                    $ward,
                    $street,
                    "{$street}, {$ward}, {$district}, {$province}",
                    $j === 1 ? 1 : 0
                ]);
                
                $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $total++;
            }
            
            if ($userId % ($this->batchSize / 2) === 0 || $userId === $userCount) {
                $sql = "INSERT INTO user_addresses (user_id, label, recipient_name, phone_number, province, district, ward, street_address, full_address, is_default) VALUES " . implode(', ', $placeholders);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
                
                $this->showProgress($userId, $userCount);
            }
        }
        
        echo "\nUser addresses done! ({$total} records)\n";
    }
    
    private function seedWallets()
    {
        echo "Seeding wallets for sellers... ";
        $this->pdo->exec("TRUNCATE TABLE wallets");
        
        $stmt = $this->pdo->query("SELECT id, full_name FROM users WHERE role IN ('seller', 'admin') LIMIT 400000");
        $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $values = [];
        $placeholders = [];
        $count = 0;
        
        foreach ($sellers as $seller) {
            $bank = $this->banks[array_rand($this->banks)];
            
            $values = array_merge($values, [
                $seller['id'],
                rand(0, 50000000),
                rand(0, 10000000),
                rand(0, 100000000),
                rand(0, 20000000),
                $bank,
                str_pad(rand(0, 9999999999), 14, '0', STR_PAD_LEFT),
                $seller['full_name']
            ]);
            
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
            $count++;
            
            if ($count % $this->batchSize === 0) {
                $sql = "INSERT INTO wallets (user_id, balance, pending_balance, total_earned, total_withdrawn, bank_name, bank_account_number, bank_account_name) VALUES " . implode(', ', $placeholders);
                $stmtInsert = $this->pdo->prepare($sql);
                $stmtInsert->execute($values);
                $this->pdo->commit();
                
                $values = [];
                $placeholders = [];
            }
        }
        
        if (!empty($placeholders)) {
            $sql = "INSERT INTO wallets (user_id, balance, pending_balance, total_earned, total_withdrawn, bank_name, bank_account_number, bank_account_name) VALUES " . implode(', ', $placeholders);
            $stmtInsert = $this->pdo->prepare($sql);
            $stmtInsert->execute($values);
            $this->pdo->commit();
        }
        
        echo "Done! ({$count} records)\n";
    }
    
    private function showProgress($current, $total)
    {
        $percent = round(($current / $total) * 100);
        echo "\r  Progress: {$current}/{$total} ({$percent}%)";
    }
    
    private function showStats()
    {
        echo "\n=== FINAL STATISTICS ===\n";
        
        $tables = [
            'users', 'categories', 'products', 'product_images', 'orders',
            'order_details', 'reviews', 'favorites', 'messages', 'notifications',
            'follows', 'interactions', 'search_keywords', 'user_addresses', 'wallets'
        ];
        
        $totalRecords = 0;
        
        foreach ($tables as $table) {
            $count = $this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
            $totalRecords += $count;
            echo sprintf("%-20s %s records\n", $table . ':', number_format($count));
        }
        
        echo "----------------------------\n";
        echo sprintf("%-20s %s records\n", 'TOTAL:', number_format($totalRecords));
    }
}

// Run seeder
$seeder = new MassSeeder();
$seeder->run();
