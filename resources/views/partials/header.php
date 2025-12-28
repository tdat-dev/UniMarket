<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniMarket Header</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
        font-family: 'Quicksand', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50">

    <header class="w-full sticky top-0 z-50 bg-white font-sans shadow-sm">

        <div class="bg-gray-100 border-b border-gray-200">
            <div class="max-w-[1200px] mx-auto px-4">
                <div class="h-[34px] flex items-center justify-end gap-6 text-[13px] text-gray-600">

                    <a href="#" class="flex items-center gap-1 hover:text-[#2C67C8] transition-colors">
                        <i class="fa-regular fa-bell"></i>
                        <span>Thông Báo</span>
                    </a>

                    <a href="#" class="flex items-center gap-1 hover:text-[#2C67C8] transition-colors">
                        <i class="fa-regular fa-circle-question"></i>
                        <span>Hỗ Trợ</span>
                    </a>

                    <div class="flex items-center gap-3">
                        <a href="/register" class="hover:text-[#2C67C8] font-medium transition-colors">Đăng Ký</a>
                        <span class="h-[14px] w-[1px] bg-gray-300"></span>
                        <a href="/login" class="hover:text-[#2C67C8] font-medium transition-colors">Đăng Nhập</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="bg-white pb-3">
            <div class="max-w-[1200px] mx-auto px-4 pt-4">
                <div class="flex items-center gap-8">

                    <a href="/" class="flex items-center gap-2 flex-shrink-0 group no-underline">
                        <img src="/images/UniMarketHead.svg" alt="UniMarket" class="h-16 w-auto object-contain">
                    </a>

                    <div class="flex-1">
                        <form
                            class="flex h-[44px] border border-gray-300 rounded-lg overflow-hidden hover:border-[#2C67C8] focus-within:border-[#2C67C8] focus-within:ring-2 focus-within:ring-[#2C67C8]/20 transition-all">
                            <input type="text" placeholder="Tìm giáo trình, đồ gia dụng, quần áo..."
                                class="flex-1 px-4 text-sm text-[#333] placeholder-gray-400 focus:outline-none bg-transparent">

                            <button
                                class="w-[70px] bg-gradient-to-b from-[#2C67C8] to-[#1990AA] flex items-center justify-center hover:opacity-90 transition-opacity">
                                <i class="fa-solid fa-magnifying-glass text-white text-lg"></i>
                            </button>
                        </form>

                        <div class="flex flex-wrap gap-x-4 mt-2 text-xs text-gray-500 pl-1">
                            <a href="#" class="hover:text-[#2C67C8] transition-colors">Sục Crocs</a>
                            <a href="#" class="hover:text-[#2C67C8] transition-colors">Áo Khoác</a>
                            <a href="#" class="hover:text-[#2C67C8] transition-colors">Áo 0 Đồng</a>
                            <a href="#" class="hover:text-[#2C67C8] transition-colors">Giáo trình C++</a>
                            <a href="#" class="hover:text-[#2C67C8] transition-colors">Quạt mini</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-8 flex-shrink-0">

                        <a href="#" class="relative group p-1">
                            <i
                                class="fa-solid fa-cart-shopping text-gray-600 text-2xl group-hover:text-[#2C67C8] transition-colors"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center border border-white">2</span>
                        </a>

                        <a href="#"
                            class="px-6 py-2.5 bg-gradient-to-r from-[#2C67C8] to-[#1990AA] text-white font-bold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all transform flex items-center gap-2 no-underline">
                            <i class="fa-solid fa-plus text-sm"></i>
                            <span>Đăng Bán</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

</body>

</html>