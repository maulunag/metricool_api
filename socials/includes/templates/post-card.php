<?php
/**
 * Reusable post card component with TailwindCSS.
 * 
 * Expected variables:
 *   $post          - Post data array
 *   $showActions   - (bool) Show approve/reject buttons
 *   $decisionLabel - (string|null) 'approved', 'rejected', 'sent', or null
 *   $sourceFile    - (string) Source JSON filename
 */

$rowId = $post['row_number'] ?? 0;
$sf = $sourceFile ?? ($post['source_file'] ?? '');

// Determine platform info based on source file prefix
$isTwitter = str_starts_with($sf, 'twitter_posts_');

// SVG icons for platforms (inline, 12x12)
$iconFb   = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
$iconIg   = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>';
$iconLi   = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
$iconGmb  = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 11.5A2.5 2.5 0 0 1 9.5 9 2.5 2.5 0 0 1 12 6.5 2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7z"/></svg>';
$iconX    = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
$iconTh   = '<svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.773.752c-1.097-3.9-3.727-5.665-7.563-5.686h-.01c-2.625.016-4.625.87-5.946 2.535C4.94 6.73 4.26 8.986 4.237 11.99v.025c.023 3.005.703 5.26 2.023 6.706 1.321 1.447 3.326 2.2 5.956 2.244h.004c2.187-.018 3.985-.62 5.244-1.705.724-.625 1.252-1.41 1.607-2.377-1.037.334-2.213.468-3.467.385-3.098-.205-5.41-1.467-6.687-3.648-.901-1.54-1.313-3.545-1.127-5.488l2.855.311c-.122 1.273.132 2.636.737 3.671.85 1.452 2.377 2.27 4.415 2.405.946.063 1.83-.032 2.632-.271-.068-.618-.307-1.19-.794-1.68-.58-.583-1.478-.994-2.676-1.222l.557-2.81c1.702.337 3.09 1.023 4.074 2.014.833.838 1.35 1.885 1.502 3.082.77-.598 1.343-1.367 1.643-2.335l2.733.862c-.527 1.698-1.584 3.053-3.04 3.937-1.574.955-3.537 1.488-5.663 1.54z"/></svg>';

$platforms = $isTwitter
    ? [
        ['name' => 'X',       'icon' => $iconX,  'class' => 'bg-white/10 text-white'],
        ['name' => 'Threads', 'icon' => $iconTh, 'class' => 'bg-white/10 text-white'],
      ]
    : [
        ['name' => 'Facebook',  'icon' => $iconFb,  'class' => 'bg-blue-600/20 text-blue-400'],
        ['name' => 'Instagram', 'icon' => $iconIg,  'class' => 'bg-pink-500/20 text-pink-400'],
        ['name' => 'LinkedIn',  'icon' => $iconLi,  'class' => 'bg-sky-500/20 text-sky-400'],
        ['name' => 'Google',    'icon' => $iconGmb, 'class' => 'bg-amber-500/20 text-amber-400'],
      ];

// Card theme
$cardBg      = $isTwitter ? 'bg-[#0a0a0a]' : 'bg-surface-light';
$cardBorder  = $isTwitter ? 'border-gray-800' : 'border-surface-border';
$cardHover   = $isTwitter ? 'hover:shadow-white/5 hover:border-gray-600' : 'hover:shadow-violet-500/10 hover:border-violet-500/30';
$noImgGrad   = $isTwitter ? 'from-gray-800 to-[#0a0a0a]' : 'from-surface-border to-surface-light';
?>
<article class="group <?= $cardBg ?> rounded-2xl overflow-hidden border <?= $cardBorder ?>
               transition-all duration-300 hover:-translate-y-1 hover:shadow-xl <?= $cardHover ?>"
    <?= $showActions ? 'id="card-' . $sf . '-' . $rowId . '"' : '' ?>>
    
    <?php if (!empty($post['url_image'])): ?>
        <div class="relative overflow-hidden">
            <img src="<?= htmlspecialchars($post['url_image']) ?>" 
                 alt="<?= htmlspecialchars($post['Titulo'] ?? '') ?>"
                 loading="lazy"
                 decoding="async"
                 onclick="openZoom(this.src)"
                 onerror="this.onerror=null;this.outerHTML='<div class=\'w-full aspect-square bg-gradient-to-br <?= $noImgGrad ?> flex items-center justify-center text-gray-600 text-4xl\'>🖼</div>'"
                 class="w-full aspect-square object-cover cursor-pointer transition-transform duration-500 group-hover:scale-105">
        </div>
    <?php else: ?>
        <div class="w-full aspect-square bg-gradient-to-br <?= $noImgGrad ?> flex items-center justify-center text-gray-600 text-4xl">🖼</div>
    <?php endif; ?>

    <div class="p-4">
        <!-- Platform labels -->
        <div class="flex flex-wrap gap-1 mb-2.5">
            <?php foreach ($platforms as $p): ?>
                <span class="inline-flex items-center gap-0.5 <?= $p['class'] ?> px-2 py-0.5 rounded-full text-[10px] font-semibold">
                    <?= $p['icon'] ?> <?= $p['name'] ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-wrap items-center gap-1.5 mb-2">
            <?php if (!empty($post['categoria'])): ?>
                <span class="inline-block <?= $isTwitter ? 'bg-white/10 text-gray-300' : 'bg-violet-500/15 text-violet-400' ?> px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase">
                    <?= htmlspecialchars($post['categoria']) ?>
                </span>
            <?php endif; ?>

            <?php if (!empty($post['status'])): ?>
                <?php
                    $statusColors = [
                        'status-done'    => 'bg-emerald-500/15 text-emerald-400',
                        'status-pending' => 'bg-amber-400/15 text-amber-400',
                        'status-default' => 'bg-slate-400/15 text-slate-400',
                    ];
                    $sc = getStatusClass($post['status']);
                    $sClass = $statusColors[$sc] ?? $statusColors['status-default'];
                ?>
                <span class="inline-block <?= $sClass ?> px-2.5 py-0.5 rounded-full text-[10px] font-semibold">
                    <?= htmlspecialchars($post['status']) ?>
                </span>
            <?php endif; ?>
        </div>

        <h2 class="text-white text-sm font-semibold leading-snug my-2 line-clamp-2">
            <?= htmlspecialchars($post['Titulo'] ?? 'Sin título') ?>
        </h2>

        <?php if (!empty($post['Resumen'])): ?>
            <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 mb-2">
                <?= htmlspecialchars($post['Resumen']) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($post['Contenido'])): ?>
            <p class="contenido text-gray-500 text-xs leading-relaxed line-clamp-3 cursor-pointer transition-all duration-300 mb-2 hover:text-gray-400">
                <?= htmlspecialchars($post['Contenido']) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($post['hashtags'])): ?>
            <p class="<?= $isTwitter ? 'text-gray-500' : 'text-violet-600' ?> text-xs break-words"><?= htmlspecialchars($post['hashtags']) ?></p>
        <?php endif; ?>

        <p class="mt-1.5">
            <a href="https://www.tampateks.com" target="_blank" class="<?= $isTwitter ? 'text-gray-400' : 'text-violet-400' ?> text-xs no-underline hover:text-violet-300 transition-colors">
                🌐 www.tampateks.com
            </a>
        </p>

        <div class="flex justify-between items-center mt-3 pt-3 border-t <?= $isTwitter ? 'border-gray-800' : 'border-surface-border' ?>">
            <span class="text-[11px] text-gray-600"><?= htmlspecialchars($post['Fecha_Registro'] ?? '') ?></span>
            <?php if (isset($post['row_number'])): ?>
                <span class="<?= $isTwitter ? 'bg-gray-800 text-gray-400' : 'bg-surface-border text-violet-400' ?> w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold">
                    <?= (int)$post['row_number'] ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($showActions): ?>
            <div class="flex gap-2 mt-3">
                <button class="flex-1 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-all duration-200
                               bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                               hover:bg-emerald-400 hover:text-black hover:border-emerald-400"
                        onclick="decide(<?= $rowId ?>, 'approved', '<?= htmlspecialchars(addslashes($post['Slug'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($post['Titulo'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($sf), ENT_QUOTES) ?>')">
                    ✅ Approve
                </button>
                <button class="flex-1 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-all duration-200
                               bg-red-400/20 text-red-400 border border-red-400/30
                               hover:bg-red-400 hover:text-black hover:border-red-400"
                        onclick="decide(<?= $rowId ?>, 'rejected', '<?= htmlspecialchars(addslashes($post['Slug'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($post['Titulo'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($sf), ENT_QUOTES) ?>')">
                    ❌ Reject
                </button>
            </div>
        <?php endif; ?>

        <?php if ($decisionLabel): ?>
            <?php
                $labelConfig = [
                    'approved' => ['icon' => '✅', 'text' => 'Approved', 'class' => 'bg-emerald-500/20 text-emerald-400'],
                    'rejected' => ['icon' => '❌', 'text' => 'Rejected', 'class' => 'bg-red-400/20 text-red-400'],
                    'sent'     => ['icon' => '🚀', 'text' => 'Sent to Metricool', 'class' => 'bg-blue-400/20 text-blue-400'],
                ];
                $cfg = $labelConfig[$decisionLabel] ?? ['icon' => '', 'text' => $decisionLabel, 'class' => 'bg-gray-500/20 text-gray-400'];
            ?>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mt-3 <?= $cfg['class'] ?>">
                <?= $cfg['icon'] ?> <?= $cfg['text'] ?>
            </span>
            <?php if ($decisionLabel === 'approved'): ?>
                <?php
                    $parts = [];
                    if (!empty($post['Resumen'])) $parts[] = $post['Resumen'];
                    if (!empty($post['Contenido'])) $parts[] = $post['Contenido'];
                    $parts[] = 'www.tampateks.com';
                    $fullText = implode("\n\n", $parts);
                ?>
                <div class="mt-2">
                    <?php
                        $modalData = json_encode([
                            "row_number"  => $post["row_number"] ?? 0,
                            "source_file" => $sf,
                            "titulo"      => $post["Titulo"] ?? "",
                            "texto"       => $fullText,
                            "hashtags"    => $post["hashtags"] ?? "",
                            "fotos"       => !empty($post["url_image"]) ? [$post["url_image"]] : []
                        ], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
                    ?>
                    <button class="w-full py-2 rounded-lg text-xs font-semibold cursor-pointer transition-all duration-200
                                   bg-violet-500/20 text-violet-400 border border-violet-500/30
                                   hover:bg-violet-500 hover:text-black hover:border-violet-500"
                            data-postdata="<?= htmlspecialchars($modalData, ENT_QUOTES, 'UTF-8') ?>"
                            onclick="openPostModal(JSON.parse(this.getAttribute('data-postdata')))">
                        🚀 Post
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</article>
