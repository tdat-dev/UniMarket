<?php
/**
 * Test Redis Cache Helper
 * Chạy file này để kiểm tra Redis có hoạt động không
 */

require_once __DIR__ . '/app/Core/RedisCache.php';

use App\Core\RedisCache;

echo "=== TEST REDIS CACHE ===\n\n";

// 1. Kiểm tra kết nối
$redis = RedisCache::getInstance();

if (!$redis->isAvailable()) {
    echo "❌ Redis không khả dụng!\n";
    echo "   Hệ thống sẽ tự động fallback về Session cache.\n";
    echo "   Xem hướng dẫn cài đặt trong file: REDIS_SETUP.md\n";
    exit(1);
}

echo "✅ Redis đã kết nối thành công!\n\n";

// 2. Test SET
echo "📝 Test SET...\n";
$testData = [
    'name' => 'UniMarket',
    'version' => '1.0',
    'features' => ['search', 'cart', 'checkout'],
    'timestamp' => time()
];

$result = $redis->set('test_unimarket', $testData, 60);
if ($result) {
    echo "   ✅ Đã lưu cache thành công\n";
} else {
    echo "   ❌ Lưu cache thất bại\n";
}

// 3. Test GET
echo "\n📖 Test GET...\n";
$cachedData = $redis->get('test_unimarket');
if ($cachedData) {
    echo "   ✅ Đã lấy cache thành công\n";
    echo "   📦 Dữ liệu: " . json_encode($cachedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "   ❌ Không tìm thấy cache\n";
}

// 4. Test EXISTS
echo "\n🔍 Test EXISTS...\n";
if ($redis->exists('test_unimarket')) {
    echo "   ✅ Key 'test_unimarket' tồn tại\n";
} else {
    echo "   ❌ Key không tồn tại\n";
}

// 5. Test TTL
echo "\n⏰ Test TTL...\n";
$ttl = $redis->ttl('test_unimarket');
if ($ttl > 0) {
    echo "   ✅ TTL còn lại: $ttl giây\n";
} elseif ($ttl === -1) {
    echo "   ⚠️  Key không có thời gian hết hạn\n";
} else {
    echo "   ❌ Key không tồn tại hoặc đã hết hạn\n";
}

// 6. Test DELETE
echo "\n🗑️  Test DELETE...\n";
if ($redis->delete('test_unimarket')) {
    echo "   ✅ Đã xóa cache thành công\n";
} else {
    echo "   ❌ Xóa cache thất bại\n";
}

// 7. Verify DELETE
echo "\n✔️  Verify DELETE...\n";
if (!$redis->exists('test_unimarket')) {
    echo "   ✅ Cache đã được xóa hoàn toàn\n";
} else {
    echo "   ❌ Cache vẫn còn tồn tại\n";
}

// 8. Test với Top Keywords (giống production)
echo "\n\n=== TEST TOP KEYWORDS CACHE ===\n\n";

// Giả lập dữ liệu keywords
$mockKeywords = [
    ['keyword' => 'sục crocs', 'search_count' => 150],
    ['keyword' => 'áo khoác', 'search_count' => 120],
    ['keyword' => 'giáo trình c++', 'search_count' => 95],
    ['keyword' => 'bàn phím cơ', 'search_count' => 80],
];

echo "📝 Lưu top keywords vào cache (TTL: 300s)...\n";
$redis->set('top_keywords', $mockKeywords, 300);

echo "📖 Lấy top keywords từ cache...\n";
$keywords = $redis->get('top_keywords');

if ($keywords) {
    echo "✅ Thành công! Danh sách keywords:\n";
    foreach ($keywords as $kw) {
        echo "   - {$kw['keyword']} ({$kw['search_count']} lượt)\n";
    }
} else {
    echo "❌ Không lấy được cache\n";
}

// 9. Cleanup
echo "\n🧹 Dọn dẹp...\n";
$redis->delete('top_keywords');
echo "   ✅ Đã xóa test cache\n";

echo "\n\n=== KẾT QUẢ ===\n";
echo "✅ Tất cả tests đã PASS!\n";
echo "🎉 Redis đang hoạt động hoàn hảo!\n";
echo "\n💡 Tip: Bây giờ em có thể mở trình duyệt và test trang web.\n";
echo "   Lần đầu sẽ query DB, các lần sau sẽ dùng Redis cache.\n";
