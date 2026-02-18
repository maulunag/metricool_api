<?php /** Sent to Metricool posts view */ ?>

<div class="mb-6">
    <h1 class="text-white text-2xl font-bold tracking-tight mb-1 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
        Sent to Metricool
    </h1>
    <p class="text-gray-500 text-sm"><span class="text-blue-400 font-semibold"><?= $data['countSent'] ?></span> posts sent</p>
</div>

<?php if (empty($data['sentPosts'])): ?>
    <div class="text-center py-20 text-gray-600">
        <div class="text-5xl mb-4">📭</div>
        <h2 class="text-gray-400 text-lg font-semibold mb-2">No sent posts yet</h2>
        <p class="text-sm">Post approved content from the Approved view.</p>
    </div>
<?php else: ?>
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-5">
        <?php foreach ($data['sentPosts'] as $post): ?>
            <?php
                $showActions = false;
                $decisionLabel = 'sent';
                $sourceFile = $post['source_file'] ?? '';
                include __DIR__ . '/post-card.php';
            ?>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
