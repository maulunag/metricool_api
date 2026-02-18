<?php /** Rejected posts view */ ?>

<div class="mb-6">
    <h1 class="text-white text-2xl font-bold tracking-tight mb-1 flex items-center gap-2">
        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        Rejected Posts
    </h1>
    <p class="text-gray-500 text-sm"><span class="text-red-400 font-semibold"><?= $data['countRejected'] ?></span> posts rejected</p>
</div>

<?php if (empty($data['rejectedPosts'])): ?>
    <div class="text-center py-20 text-gray-600">
        <div class="text-5xl mb-4">📭</div>
        <h2 class="text-gray-400 text-lg font-semibold mb-2">No rejected posts yet</h2>
        <p class="text-sm">Reject posts from the Pending view.</p>
    </div>
<?php else: ?>
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
        <?php foreach ($data['rejectedPosts'] as $post): ?>
            <?php
                $showActions = false;
                $decisionLabel = 'rejected';
                $sourceFile = $post['source_file'] ?? '';
                include __DIR__ . '/post-card.php';
            ?>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
