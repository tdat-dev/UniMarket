<!-- Footer -->
<footer class="bg-gray-100 py-[30px]">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-wrap justify-center items-start gap-20 md:gap-32 lg:gap-40 py-[30px]">
            <!-- Service -->
            <div>
                <h3 class="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wide">DỊCH VỤ KHÁCH HÀNG</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Trung Tâm Trợ Giúp
                            <?= $siteSettings['general']['site_name'] ?? 'Zoldify' ?></a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Hướng Dẫn Mua Hàng/Đặt Hàng</a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Hướng Dẫn Bán Hàng</a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Đơn Hàng</a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Trả Hàng/Hoàn Tiền</a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Liên Hệ
                            <?= $siteSettings['general']['site_name'] ?? 'Zoldify' ?></a></li>
                    <li><a href="#" class="hover:text-gray-800 transition-colors">Chính Sách Bảo Hành</a></li>
                </ul>
            </div>

            <!-- Pay -->
            <div>
                <h3 class="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wide">THANH TOÁN</h3>
                <div class="flex gap-3 flex-wrap">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"
                        class="h-8 w-auto">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"
                        class="h-8 w-auto">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB"
                        class="h-8 w-auto">
                </div>
            </div>

            <!-- Monitor -->
            <div>
                <h3 class="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wide">THEO DÕI
                    <?= strtoupper($siteSettings['general']['site_name'] ?? 'ZOLDIFY') ?>
                </h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <?php if (!empty($siteSettings['social']['social_facebook'])): ?>
                        <li class="flex items-center gap-3">
                            <i class="fa-brands fa-facebook text-base text-gray-700"></i>
                            <a href="<?= htmlspecialchars($siteSettings['social']['social_facebook']) ?>" target="_blank"
                                class="hover:text-gray-800 transition-colors">Facebook</a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($siteSettings['social']['social_instagram'])): ?>
                        <li class="flex items-center gap-3">
                            <i class="fa-brands fa-square-instagram text-base text-gray-700"></i>
                            <a href="<?= htmlspecialchars($siteSettings['social']['social_instagram']) ?>" target="_blank"
                                class="hover:text-gray-800 transition-colors">Instagram</a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($siteSettings['social']['social_youtube'])): ?>
                        <li class="flex items-center gap-3">
                            <i class="fa-brands fa-youtube text-base text-gray-700"></i>
                            <a href="<?= htmlspecialchars($siteSettings['social']['social_youtube']) ?>" target="_blank"
                                class="hover:text-gray-800 transition-colors">YouTube</a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($siteSettings['social']['social_zalo'])): ?>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-comment-dots text-base text-gray-700"></i>
                            <a href="https://zalo.me/<?= htmlspecialchars($siteSettings['social']['social_zalo']) ?>"
                                target="_blank" class="hover:text-gray-800 transition-colors">Zalo</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center text-sm text-gray-500">
        <p>©
            <?= date('Y') ?>
            <?= htmlspecialchars($siteSettings['general']['site_name'] ?? 'Zoldify') ?>. Tất cả các quyền được bảo lưu.
        </p>
        <p class="mt-1">Quốc gia & Khu vực: Việt Nam</p>
        <?php if (!empty($siteSettings['contact']['contact_email'])): ?>
            <p class="mt-1">Email:
                <?= htmlspecialchars($siteSettings['contact']['contact_email']) ?>
            </p>
        <?php endif; ?>
    </div>
</footer>

<!-- Socket.IO Client Library -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<!-- Chat Socket Client -->
<script src="/js/chat-socket.js?v=<?= time() ?>"></script>

<!-- Khởi tạo kết nối nếu user đã đăng nhập -->
<?php if (isset($_SESSION['user']['id'])): ?>
    <script>
        // Kết nối Socket
        document.addEventListener('DOMContentLoaded', function () {
            window.chatSocket.connect(<?= $_SESSION['user']['id'] ?>);
        });
    </script>
<?php endif; ?>