<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMPATEKS Socials</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        surface: { DEFAULT: '#12121a', light: '#1a1a2e', border: '#2d2d44' },
                        accent: { DEFAULT: '#a78bfa', dim: 'rgba(167,139,250,0.15)' }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/posts-viewer.css?v=<?= filemtime(__DIR__ . '/../../assets/css/posts-viewer.css') ?>">
</head>
<body class="bg-[#0f0f13] text-gray-300 font-['Inter',sans-serif] min-h-screen">

<!-- ===== Mobile Top Bar ===== -->
<header id="mobileBar" class="md:hidden fixed top-0 left-0 right-0 z-[150] bg-surface/95 backdrop-blur-md border-b border-surface-border px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-2.5">
        <span class="bg-violet-500 text-white w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-black">TT</span>
        <span class="text-white font-bold text-sm">TAMPATEKS <span class="text-violet-400 font-medium">Socials</span></span>
    </div>
    <button onclick="toggleSidebar()" class="text-violet-400 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-all" aria-label="Toggle menu">
        <svg id="menuIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        <svg id="closeIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
    </button>
</header>

<!-- ===== Sidebar Overlay (mobile) ===== -->
<div id="sidebarOverlay" class="md:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden" onclick="closeSidebar()"></div>

<!-- ===== Sidebar ===== -->
<aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-surface z-[110] border-r border-surface-border flex flex-col overflow-y-auto
                           -translate-x-full md:translate-x-0 transition-transform duration-300 ease-out">
    <div class="px-5 pt-6 pb-2">
        <h2 class="text-white font-bold text-base tracking-tight flex items-center gap-2.5">
            <span class="bg-violet-500 text-white w-8 h-8 rounded-lg flex items-center justify-center text-xs font-black">TT</span>
            <span>TAMPATEKS<br><span class="text-violet-400 font-medium text-xs">Socials</span></span>
        </h2>
    </div>
    <nav class="mt-6 flex-1">
        <ul class="space-y-0.5">
            <li>
                <a href="?view=pending" onclick="closeSidebar()"
                   class="group flex items-center justify-between px-5 py-3 text-sm transition-all duration-200 border-l-[3px]
                          <?= $data['currentView'] === 'pending'
                              ? 'bg-accent-dim text-accent border-accent font-semibold'
                              : 'text-gray-500 border-transparent hover:bg-white/[0.04] hover:text-gray-300' ?>">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        Pending
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full min-w-[26px] text-center
                                 bg-violet-500/20 text-violet-400"><?= $data['countPending'] ?></span>
                </a>
            </li>
            <li>
                <a href="?view=approved" onclick="closeSidebar()"
                   class="group flex items-center justify-between px-5 py-3 text-sm transition-all duration-200 border-l-[3px]
                          <?= $data['currentView'] === 'approved'
                              ? 'bg-emerald-500/10 text-emerald-400 border-emerald-400 font-semibold'
                              : 'text-gray-500 border-transparent hover:bg-white/[0.04] hover:text-gray-300' ?>">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        Approved
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full min-w-[26px] text-center
                                 bg-emerald-500/20 text-emerald-400"><?= $data['countApproved'] ?></span>
                </a>
            </li>
            <li>
                <a href="?view=rejected" onclick="closeSidebar()"
                   class="group flex items-center justify-between px-5 py-3 text-sm transition-all duration-200 border-l-[3px]
                          <?= $data['currentView'] === 'rejected'
                              ? 'bg-red-400/10 text-red-400 border-red-400 font-semibold'
                              : 'text-gray-500 border-transparent hover:bg-white/[0.04] hover:text-gray-300' ?>">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        Rejected
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full min-w-[26px] text-center
                                 bg-red-400/20 text-red-400"><?= $data['countRejected'] ?></span>
                </a>
            </li>
            <li>
                <a href="?view=sent" onclick="closeSidebar()"
                   class="group flex items-center justify-between px-5 py-3 text-sm transition-all duration-200 border-l-[3px]
                          <?= $data['currentView'] === 'sent'
                              ? 'bg-blue-400/10 text-blue-400 border-blue-400 font-semibold'
                              : 'text-gray-500 border-transparent hover:bg-white/[0.04] hover:text-gray-300' ?>">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                        Sent
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full min-w-[26px] text-center
                                 bg-blue-400/20 text-blue-400"><?= $data['countSent'] ?></span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="px-5 py-4 border-t border-surface-border">
        <p class="text-[10px] text-gray-600 text-center">Tampa Teks © <?= date('Y') ?></p>
    </div>
</aside>

<!-- ===== Main Content ===== -->
<main class="md:ml-64 pt-16 md:pt-0 p-4 md:p-6 lg:p-8 min-h-screen">
    <?php
    $viewFile = __DIR__ . '/' . $data['currentView'] . '.php';
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        include __DIR__ . '/pending.php';
    }
    ?>
</main>

<!-- Modal Zoom -->
<div class="fixed inset-0 bg-black/85 z-[1000] hidden items-center justify-center cursor-pointer" id="zoomModal" onclick="closeZoom()">
    <img id="zoomImg" src="" alt="Zoom" class="max-w-[90vw] max-h-[90vh] rounded-xl shadow-2xl shadow-violet-500/20 object-contain cursor-default">
</div>

<!-- Modal Post to Metricool -->
<?php include __DIR__ . '/post-modal.php'; ?>

<script src="assets/js/posts-viewer.js?v=<?= filemtime(__DIR__ . '/../../assets/js/posts-viewer.js') ?>"></script>
<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var menuIcon = document.getElementById('menuIcon');
        var closeIcon = document.getElementById('closeIcon');
        var isOpen = !sidebar.classList.contains('-translate-x-full');

        if (isOpen) {
            closeSidebar();
        } else {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var menuIcon = document.getElementById('menuIcon');
        var closeIcon = document.getElementById('closeIcon');

        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    }
</script>

</body>
</html>
