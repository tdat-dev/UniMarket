<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<main class="bg-gray-100 min-h-screen pb-10 flex flex-col">
    <div class="max-w-[1000px] w-full mx-auto px-4 flex-1 flex flex-col pt-6">
        
        <div class="bg-white rounded-sm shadow-sm flex overflow-hidden h-[600px] border border-gray-200">
            <!-- Sidebar -->
            <div class="w-1/3 border-r border-gray-200 flex flex-col">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="relative">
                        <input type="text" placeholder="Tìm kiếm tin nhắn..." class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-[#2C67C8]">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <?php if (empty($conversations)): ?>
                        <div class="p-4 text-center text-gray-400 text-xs">Chưa có tin nhắn nào.</div>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <?php 
                                $partner = $conv['partner'];
                                $lastMsg = $conv['last_message'];
                                $isActive = ($activePartner && $activePartner['id'] == $partner['id']);
                            ?>
                            <a href="/chat?user_id=<?= $partner['id'] ?>" class="block">
                                <div class="p-3 border-b border-gray-100 flex gap-3 hover:bg-gray-50 cursor-pointer <?= $isActive ? 'bg-blue-50/50 border-l-4 border-l-[#2C67C8]' : '' ?>">
                                    <div class="relative">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($partner['full_name']) ?>&background=random" class="w-10 h-10 rounded-full object-cover">
                                        <!-- Online status mock -->
                                        <!-- <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white"></div> -->
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <h4 class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($partner['full_name']) ?></h4>
                                            <span class="text-[10px] text-gray-400"><?= date('H:i', strtotime($lastMsg['created_at'])) ?></span>
                                        </div>
                                        <p class="text-xs text-gray-500 truncate <?= !$lastMsg['is_read'] && $lastMsg['receiver_id'] == $currentUserId ? 'font-bold' : '' ?>">
                                            <?= $lastMsg['sender_id'] == $currentUserId ? 'Bạn: ' : '' ?><?= htmlspecialchars($lastMsg['content']) ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-2/3 flex flex-col bg-gray-50">
                <?php if ($activePartner): ?>
                    <!-- Header -->
                    <div class="p-4 bg-white border-b border-gray-200 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($activePartner['full_name']) ?>&background=random" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <h3 class="text-sm font-bold text-gray-800"><?= htmlspecialchars($activePartner['full_name']) ?></h3>
                                <span class="text-xs text-green-500 flex items-center gap-1"><i class="fa-solid fa-circle text-[6px]"></i> Đang hoạt động</span>
                            </div>
                        </div>
                        <a href="/shop?id=<?= $activePartner['id'] ?>" class="text-gray-400 hover:text-[#2C67C8]" title="Xem Shop">
                            <i class="fa-solid fa-store"></i>
                        </a>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col-reverse"> 
                        <!-- flex-col-reverse helps scroll to bottom, need to reverse order if backend sends ASC. 
                             Usually backend sends ASC, so we use normal flex col and JS to scroll. 
                             Let's stick to normal flow and simple JS. -->
                        
                        <?php 
                        // Messages are ASC
                        foreach ($messages as $msg): 
                            $isMe = ($msg['sender_id'] == $currentUserId);
                        ?>
                            <div class="flex <?= $isMe ? 'justify-end' : 'justify-start' ?>">
                                <?php if (!$isMe): ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($activePartner['full_name']) ?>&background=random" class="w-8 h-8 rounded-full mr-2 self-end object-cover">
                                <?php endif; ?>
                                
                                <div class="<?= $isMe ? 'bg-[#2C67C8] text-white rounded-tr-none' : 'bg-white border border-gray-200 text-gray-800 rounded-tl-none' ?> p-3 rounded-lg text-sm max-w-[80%] shadow-sm">
                                    <?= htmlspecialchars($msg['content']) ?>
                                    <!-- <div class="text-[10px] <?= $isMe ? 'text-blue-100' : 'text-gray-400' ?> mt-1 text-right">
                                        <?= date('H:i', strtotime($msg['created_at'])) ?>
                                    </div> -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($messages)): ?>
                            <div class="text-center text-gray-400 text-sm mt-10">Bắt đầu cuộc trò chuyện với <?= htmlspecialchars($activePartner['full_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Input -->
                    <div class="p-4 bg-white border-t border-gray-200">
                        <form action="/chat/send" method="POST" class="flex gap-2">
                            <input type="hidden" name="receiver_id" value="<?= $activePartner['id'] ?>">
                            <!-- Attachments (visual only) -->
                            <button type="button" class="p-2 text-gray-400 hover:text-[#2C67C8]"><i class="fa-regular fa-image text-lg"></i></button>
                            <button type="button" class="p-2 text-gray-400 hover:text-[#2C67C8]"><i class="fa-solid fa-paperclip text-lg"></i></button>
                            
                            <input type="text" name="content" placeholder="Nhập tin nhắn..." class="flex-1 bg-gray-100 border-none rounded-sm px-4 py-2 focus:ring-1 focus:ring-[#2C67C8] focus:bg-white transition-colors" required autocomplete="off">
                            
                            <button type="submit" class="bg-[#2C67C8] text-white px-4 py-2 rounded-sm hover:bg-blue-700 transition-colors">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-regular fa-comments text-4xl mb-4"></i>
                        <p>Chọn một cuộc trò chuyện để bắt đầu</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script>
    // Auto scroll to bottom
    const container = document.querySelector('.overflow-y-auto.space-y-4');
    if(container) {
        container.scrollTop = container.scrollHeight;
    }
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
