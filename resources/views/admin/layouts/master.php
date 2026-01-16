<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title ?? 'Admin' ?> - Zoldify Admin
    </title>

    <!-- Favicon -->
    <link rel="icon" href="/images/icons/favicon.ico" />

    <!-- TailwindCSS & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #fff;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">

            <!-- TOPBAR -->
            <?php include __DIR__ . '/../partials/header.php'; ?>

            <!-- PAGE CONTENT -->
            <main class="flex-1 p-6">
                <?= $content ?? '' ?>
            </main>

        </div>
    </div>

    <!-- Zoldify Custom Dialog System -->
    <script src="/js/zoldify-dialog.js?v=<?= time() ?>"></script>

    <!-- Scripts -->
    <?= $scripts ?? '' ?>
</body>

</html>