<?php /** Pending posts view — grouped by source file with filter */ ?>

<div class="mb-6">
    <h1 class="text-white text-2xl font-bold tracking-tight mb-1 flex items-center gap-2">
        <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        Pending Posts
    </h1>
    <p class="text-gray-500 text-sm"><span class="text-violet-400 font-semibold"><?= $data['countPending'] ?></span> posts pending review</p>
</div>

<?php if (empty($data['pendingByFile'])): ?>
    <div class="text-center py-20 text-gray-600">
        <div class="text-5xl mb-4">🎉</div>
        <h2 class="text-gray-400 text-lg font-semibold mb-2">All posts reviewed!</h2>
        <p class="text-sm">No pending posts remaining.</p>
    </div>
<?php else: ?>
    <!-- File Filter -->
    <div class="flex flex-wrap gap-2 mb-6 pb-2">
        <button class="filter-btn active px-4 py-2 bg-surface-light border border-surface-border rounded-full text-gray-500 text-xs cursor-pointer transition-all duration-200 whitespace-nowrap
                       hover:border-violet-500/50 hover:text-gray-300"
                onclick="filterByFile('all', this)">
            📁 All (<?= $data['countPending'] ?>)
        </button>
        <?php foreach ($data['pendingByFile'] as $fileName => $posts): ?>
            <button class="filter-btn px-4 py-2 bg-surface-light border border-surface-border rounded-full text-gray-500 text-xs cursor-pointer transition-all duration-200 whitespace-nowrap
                           hover:border-violet-500/50 hover:text-gray-300"
                    onclick="filterByFile('<?= htmlspecialchars($fileName, ENT_QUOTES) ?>', this)">
                📄 <?= htmlspecialchars($fileName) ?> (<?= count($posts) ?>)
            </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($data['pendingByFile'] as $fileName => $posts): ?>
        <div class="file-group" data-file="<?= htmlspecialchars($fileName, ENT_QUOTES) ?>">
            <div class="text-gray-500 text-sm mt-5 mb-3">
                📄 <strong class="text-gray-400"><?= htmlspecialchars($fileName) ?></strong> — <span class="text-violet-400 font-semibold"><?= count($posts) ?></span> pending
            </div>
            <section class="posts-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
                <?php foreach ($posts as $post): ?>
                    <?php
                        $showActions = true;
                        $decisionLabel = null;
                        $sourceFile = $fileName;
                        include __DIR__ . '/post-card.php';
                    ?>
                <?php endforeach; ?>
            </section>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
