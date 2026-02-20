<?php /** Database Posted — list of all posts saved in MySQL */ ?>

<div class="mb-6">
    <h1 class="text-white text-2xl font-bold tracking-tight mb-1 flex items-center gap-2">
        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75"/></svg>
        Database Posted
    </h1>
    <p class="text-gray-500 text-sm"><span class="text-cyan-400 font-semibold" id="db-count">0</span> posts stored in database</p>
</div>

<div id="db-loading" class="text-center py-20 text-gray-600">
    <div class="text-5xl mb-4 animate-pulse">🔄</div>
    <h2 class="text-gray-400 text-lg font-semibold mb-2">Loading from database...</h2>
</div>

<div id="db-empty" class="text-center py-20 text-gray-600 hidden">
    <div class="text-5xl mb-4">📭</div>
    <h2 class="text-gray-400 text-lg font-semibold mb-2">No posts in database yet</h2>
    <p class="text-sm">Posts will appear here after publishing via Metricool.</p>
</div>

<div id="db-list" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Cards injected by JS -->
</div>

<!-- Detail Modal -->
<div class="fixed inset-0 bg-black/80 z-[1100] hidden items-center justify-center backdrop-blur-sm" id="dbDetailModal" onclick="closeDbDetail()">
    <div class="bg-[#16161e] rounded-2xl border border-surface-border w-[92%] max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl shadow-black/50"
         onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-surface-border sticky top-0 bg-[#16161e] z-10">
            <div class="flex items-center gap-3">
                <span id="detail-badge" class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-400"></span>
                <h3 class="text-white font-semibold text-base">Post Detail</h3>
            </div>
            <button onclick="closeDbDetail()" class="text-gray-600 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-all text-lg cursor-pointer">✕</button>
        </div>
        <!-- Body -->
        <div class="flex flex-col md:flex-row">
            <!-- Left: Image -->
            <div class="md:w-2/5 p-5 flex items-start justify-center border-b md:border-b-0 md:border-r border-surface-border bg-[#0d0d14]">
                <div id="detail-img" class="w-full rounded-xl overflow-hidden border border-surface-border"></div>
            </div>
            <!-- Right: Info -->
            <div class="md:w-3/5 p-5 space-y-3">
                <div id="detail-fields"></div>
            </div>
        </div>
        <!-- Footer: Metricool Response -->
        <div class="px-6 py-4 border-t border-surface-border">
            <button onclick="document.getElementById('detail-response').classList.toggle('hidden')" class="text-[11px] text-gray-500 hover:text-gray-300 cursor-pointer flex items-center gap-1 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                Metricool API Response
            </button>
            <pre id="detail-response" class="hidden text-[10px] text-gray-600 bg-black/30 rounded-lg p-3 overflow-x-auto max-h-48 font-mono"></pre>
        </div>
    </div>
</div>

<script>
var _dbPosts = [];

function openDbDetail(index) {
    var post = _dbPosts[index];
    if (!post) return;

    document.getElementById('detail-badge').textContent = 'DB #' + post.id;

    // Image
    var imgContainer = document.getElementById('detail-img');
    if (post.url_image) {
        imgContainer.innerHTML = '<img src="' + post.url_image + '" alt="" class="w-full object-cover cursor-pointer" onclick="event.stopPropagation(); var zm=document.getElementById(\'zoomModal\'); zm.style.zIndex=\'1200\'; document.getElementById(\'zoomImg\').src=this.src; zm.classList.add(\'active\');">';
    } else {
        imgContainer.innerHTML = '<div class="w-full aspect-square bg-gradient-to-br from-surface-border to-surface-light flex flex-col items-center justify-center text-gray-600 gap-2"><svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg><span class="text-xs">No image</span></div>';
    }

    // Fields
    var fields = [
        { label: 'Title', value: post.titulo, type: 'title' },
        { label: 'Category', value: post.categoria, type: 'badge' },
        { label: 'Summary', value: post.resumen, type: 'text' },
        { label: 'Content', value: post.contenido, type: 'text' },
        { label: 'Hashtags', value: post.hashtags, type: 'hashtags' },
        { label: 'Action', value: post.action, type: 'mono' },
        { label: 'Platforms', value: post.platforms, type: 'platforms' },
        { label: 'Slug', value: post.slug, type: 'mono' },
        { label: 'Source File', value: post.source_file, type: 'mono' },
        { label: 'Fecha Registro', value: post.fecha_registro, type: 'text' },
        { label: 'Scheduled Date', value: post.scheduled_date, type: 'text' },
        { label: 'Saved at', value: post.created_at, type: 'text' },
        { label: 'Blog ID', value: post.blog_id, type: 'mono' },
        { label: 'Row Number', value: post.row_number, type: 'mono' },
        { label: 'Status', value: post.status, type: 'mono' },
        { label: 'Image Prompt', value: post.promt_idea_image, type: 'text' },
    ];

    var html = '';
    fields.forEach(function(f) {
        if (!f.value) return;
        var valHtml = '';
        if (f.type === 'title') {
            valHtml = '<span class="text-white font-semibold text-sm">' + escHtml(f.value) + '</span>';
        } else if (f.type === 'badge') {
            valHtml = '<span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-violet-500/20 text-violet-400">' + escHtml(f.value) + '</span>';
        } else if (f.type === 'hashtags') {
            valHtml = '<span class="text-violet-400 text-xs">' + escHtml(f.value) + '</span>';
        } else if (f.type === 'mono') {
            valHtml = '<span class="font-mono text-gray-400 text-xs">' + escHtml(f.value) + '</span>';
        } else if (f.type === 'platforms') {
            valHtml = f.value.split(',').map(function(p) {
                p = p.trim();
                var colors = {
                    'facebook': 'bg-blue-600/20 text-blue-400',
                    'instagram': 'bg-pink-500/20 text-pink-400',
                    'linkedin': 'bg-sky-500/20 text-sky-400',
                    'gmb': 'bg-amber-500/20 text-amber-400',
                    'twitter': 'bg-white/10 text-white',
                    'threads': 'bg-white/10 text-gray-300',
                    'youtube': 'bg-red-500/20 text-red-400',
                    'tiktok': 'bg-teal-500/20 text-teal-400'
                };
                var cls = colors[p] || 'bg-gray-500/20 text-gray-400';
                return '<span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold ' + cls + '">' + p + '</span>';
            }).join(' ');
        } else {
            valHtml = '<span class="text-gray-400 text-xs whitespace-pre-wrap">' + escHtml(f.value) + '</span>';
        }
        html += '<div class="mb-2.5"><div class="text-gray-600 text-[10px] font-semibold uppercase tracking-wider mb-0.5">' + f.label + '</div>' + valHtml + '</div>';
    });

    document.getElementById('detail-fields').innerHTML = html;

    // Metricool response
    var responseEl = document.getElementById('detail-response');
    responseEl.classList.add('hidden');
    try {
        var parsed = JSON.parse(post.metricool_response);
        responseEl.textContent = JSON.stringify(parsed, null, 2);
    } catch(e) {
        responseEl.textContent = post.metricool_response || 'N/A';
    }

    document.getElementById('dbDetailModal').classList.add('active');
}

function closeDbDetail() {
    document.getElementById('dbDetailModal').classList.remove('active');
}

function escHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

(function() {
    fetch('../database/list_posts.php')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('db-loading').classList.add('hidden');
            document.getElementById('db-count').textContent = data.total || 0;

            if (!data.posts || data.posts.length === 0) {
                document.getElementById('db-empty').classList.remove('hidden');
                return;
            }

            _dbPosts = data.posts;
            var list = document.getElementById('db-list');
            list.classList.remove('hidden');

            data.posts.forEach(function(post, idx) {
                var platformBadges = (post.platforms || '').split(',').map(function(p) {
                    p = p.trim();
                    if (!p) return '';
                    var colors = {
                        'facebook': 'bg-blue-600/20 text-blue-400',
                        'instagram': 'bg-pink-500/20 text-pink-400',
                        'linkedin': 'bg-sky-500/20 text-sky-400',
                        'gmb': 'bg-amber-500/20 text-amber-400',
                        'twitter': 'bg-white/10 text-white',
                        'threads': 'bg-white/10 text-gray-300',
                        'youtube': 'bg-red-500/20 text-red-400',
                        'tiktok': 'bg-teal-500/20 text-teal-400'
                    };
                    var cls = colors[p] || 'bg-gray-500/20 text-gray-400';
                    return '<span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold ' + cls + '">' + p + '</span>';
                }).join('');

                var imgHtml = post.url_image
                    ? '<img src="' + post.url_image + '" alt="" class="w-14 h-14 rounded-lg object-cover shrink-0 border border-surface-border">'
                    : '<div class="w-14 h-14 rounded-lg bg-surface-light border border-surface-border flex items-center justify-center text-gray-600 shrink-0"><svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg></div>';

                var date = post.created_at || '';
                var cat = post.categoria ? '<span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold bg-violet-500/20 text-violet-400">' + escHtml(post.categoria) + '</span>' : '';

                var card = document.createElement('div');
                card.className = 'flex items-start gap-3 p-4 bg-surface-light rounded-xl border border-surface-border hover:border-violet-500/30 hover:bg-surface-light/80 transition-all cursor-pointer';
                card.setAttribute('onclick', 'openDbDetail(' + idx + ')');
                card.innerHTML =
                    imgHtml +
                    '<div class="flex-1 min-w-0">' +
                        '<div class="flex items-center gap-2 flex-wrap mb-1">' +
                            '<h3 class="text-white text-sm font-semibold truncate">' + escHtml(post.titulo || 'Untitled') + '</h3>' +
                            cat +
                        '</div>' +
                        '<p class="text-gray-500 text-xs line-clamp-2 mb-2">' + escHtml((post.resumen || post.contenido || '').substring(0, 150)) + '</p>' +
                        '<div class="flex items-center gap-2 flex-wrap">' +
                            '<span class="text-[10px] text-gray-600 font-mono">' + escHtml(post.action || '') + '</span>' +
                            '<span class="text-gray-700">·</span>' +
                            platformBadges +
                            '<span class="text-gray-700">·</span>' +
                            '<span class="text-[10px] text-gray-600">' + escHtml(date) + '</span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="shrink-0 flex items-center">' +
                        '<span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-400">DB #' + post.id + '</span>' +
                    '</div>';
                list.appendChild(card);
            });
        })
        .catch(function(err) {
            document.getElementById('db-loading').classList.add('hidden');
            document.getElementById('db-empty').classList.remove('hidden');
            document.getElementById('db-empty').querySelector('h2').textContent = 'Error loading data';
            document.getElementById('db-empty').querySelector('p').textContent = err.message;
        });
})();
</script>
