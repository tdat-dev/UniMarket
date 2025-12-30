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
                    <!-- Chat Item (Active) -->
                    <div class="p-3 border-b border-gray-100 flex gap-3 hover:bg-gray-50 cursor-pointer bg-blue-50/50 border-l-4 border-l-[#2C67C8]">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-10 h-10 rounded-full">
                            <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="text-sm font-medium text-gray-800 truncate">Hỗ trợ khách hàng</h4>
                                <span class="text-[10px] text-gray-400">Vừa xong</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">Chào bạn, mình có thể giúp gì cho bạn?</p>
                        </div>
                    </div>
                    <!-- Chat Item -->
                    <div class="p-3 border-b border-gray-100 flex gap-3 hover:bg-gray-50 cursor-pointer">
                         <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Seller+A&background=random" class="w-10 h-10 rounded-full">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="text-sm font-medium text-gray-800 truncate">Nguyễn Văn A</h4>
                                <span class="text-[10px] text-gray-400">1 giờ</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">Sản phẩm này còn hàng không shop?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-2/3 flex flex-col bg-gray-50">
                <!-- Header -->
                <div class="p-4 bg-white border-b border-gray-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-8 h-8 rounded-full">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Hỗ trợ khách hàng</h3>
                            <span class="text-xs text-green-500 flex items-center gap-1"><i class="fa-solid fa-circle text-[6px]"></i> Đang hoạt động</span>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    <div class="text-center text-xs text-gray-400 my-4">Hôm nay</div>
                    
                    <div class="flex justify-end">
                        <div class="bg-[#2C67C8] text-white p-3 rounded-lg rounded-tr-none text-sm max-w-[80%] shadow-sm">
                            Chào shop, mình muốn hỏi về sản phẩm này ạ.
                        </div>
                    </div>

                    <div class="flex justify-start">
                         <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-8 h-8 rounded-full mr-2 self-end">
                        <div class="bg-white border border-gray-200 text-gray-800 p-3 rounded-lg rounded-tl-none text-sm max-w-[80%] shadow-sm">
                            Chào bạn, mình có thể giúp gì cho bạn?
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="p-4 bg-white border-t border-gray-200">
                    <form onsubmit="event.preventDefault(); alert('Tin nhắn đã được gửi (Giả lập)');" class="flex gap-2">
                        <button type="button" class="p-2 text-gray-400 hover:text-[#2C67C8]"><i class="fa-regular fa-image text-lg"></i></button>
                        <button type="button" class="p-2 text-gray-400 hover:text-[#2C67C8]"><i class="fa-solid fa-paperclip text-lg"></i></button>
                        <input type="text" placeholder="Nhập tin nhắn..." class="flex-1 bg-gray-100 border-none rounded-sm px-4 py-2 focus:ring-1 focus:ring-[#2C67C8] focus:bg-white transition-colors" required>
                        <button type="submit" class="bg-[#2C67C8] text-white px-4 py-2 rounded-sm hover:bg-blue-700 transition-colors">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
