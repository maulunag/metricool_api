/* =============================================
   Posts JSON Viewer - JavaScript
   ============================================= */

// --- Image Zoom Modal ---
function openZoom(src) {
    document.getElementById('zoomImg').src = src;
    document.getElementById('zoomModal').classList.add('active');
}

function closeZoom() {
    document.getElementById('zoomModal').classList.remove('active');
    document.getElementById('zoomImg').src = '';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeZoom();
        closePostModal();
    }
});

// --- Expandable Content ---
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('contenido')) {
        e.target.classList.toggle('expanded');
    }
});

// --- File Filter ---
function filterByFile(fileName, btn) {
    // Update active button
    var buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(function (b) { b.classList.remove('active'); });
    btn.classList.add('active');

    // Show/hide file groups
    var groups = document.querySelectorAll('.file-group');
    groups.forEach(function (group) {
        if (fileName === 'all' || group.getAttribute('data-file') === fileName) {
            group.classList.remove('hidden');
        } else {
            group.classList.add('hidden');
        }
    });
}

// --- Approve / Reject Decision ---
function decide(rowNumber, decision, slug, titulo, sourceFile) {
    var card = document.getElementById('card-' + sourceFile + '-' + rowNumber);

    fetch(window.location.pathname, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            row_number: rowNumber,
            decision: decision,
            slug: slug,
            titulo: titulo,
            source_file: sourceFile || ''
        })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success && card) {
                card.style.transition = 'opacity 0.4s, transform 0.4s';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(function () {
                    card.remove();

                    // Update sidebar badges
                    var badges = document.querySelectorAll('.badge');
                    var pending = document.querySelectorAll('.post-card').length;
                    badges[0].textContent = pending;
                    if (decision === 'approved') {
                        badges[1].textContent = parseInt(badges[1].textContent) + 1;
                    } else {
                        badges[2].textContent = parseInt(badges[2].textContent) + 1;
                    }

                    // Update stats
                    var statsSpan = document.querySelector('.stats span');
                    if (statsSpan) statsSpan.textContent = pending;

                    // Show empty state if no posts left
                    if (pending === 0) {
                        var grid = document.querySelector('.posts-grid');
                        if (grid) {
                            grid.innerHTML = '<div class="empty-state"><h2>All posts reviewed!</h2><p>No pending posts remaining.</p></div>';
                        }
                    }
                }, 400);
            }
        });
}

// --- Post to Metricool Modal ---
var _currentPostRowNumber = null;
var _currentPostSourceFile = null;
var _currentPostData = {};

function openPostModal(postData) {
    _currentPostRowNumber = postData.row_number || null;
    _currentPostSourceFile = postData.source_file || '';
    _currentPostData = postData;
    document.getElementById('pm-titulo').value = postData.titulo || '';
    document.getElementById('pm-texto').value = postData.texto || '';
    document.getElementById('pm-hashtags').value = postData.hashtags || '';

    var fotoUrl = (postData.fotos && postData.fotos.length > 0) ? postData.fotos[0] : '';
    document.getElementById('pm-foto').value = fotoUrl;

    // Image preview
    updateModalPreview(fotoUrl);

    // Target label
    var isTwitter = _currentPostSourceFile && _currentPostSourceFile.indexOf('twitter_posts_') === 0;
    var target = document.getElementById('pm-target');
    target.textContent = isTwitter ? '→ X (Twitter) + Threads' : '→ Facebook, Instagram, LinkedIn, Google';

    // Platform badges
    var badges = document.getElementById('pm-platforms');
    if (isTwitter) {
        badges.innerHTML =
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white/10 text-white text-[10px] font-semibold">𝕏 X</span>' +
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white/10 text-white text-[10px] font-semibold">🔗 Threads</span>';
    } else {
        badges.innerHTML =
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-blue-600/20 text-blue-400 text-[10px] font-semibold">f Facebook</span>' +
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-pink-500/20 text-pink-400 text-[10px] font-semibold">📷 Instagram</span>' +
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-sky-500/20 text-sky-400 text-[10px] font-semibold">in LinkedIn</span>' +
            '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-amber-500/20 text-amber-400 text-[10px] font-semibold">G Google</span>';
    }

    // Reset status
    var status = document.getElementById('pm-status');
    status.className = 'pm-status hidden';
    status.textContent = '';

    // Enable submit
    document.getElementById('pm-submit').disabled = false;

    document.getElementById('postModal').classList.add('active');
}

function updateModalPreview(url) {
    var container = document.getElementById('pm-preview-img');
    if (url) {
        container.innerHTML = '<img src="' + url + '" alt="Preview" class="w-full object-cover" onerror="this.parentElement.innerHTML=\'<div class=\\\'w-full aspect-square bg-gradient-to-br from-surface-border to-surface-light flex flex-col items-center justify-center text-gray-600 gap-2\\\'><span class=\\\'text-xs\\\'>Image failed to load</span></div>\'">';
    } else {
        container.innerHTML = '<div class="w-full aspect-square bg-gradient-to-br from-surface-border to-surface-light flex flex-col items-center justify-center text-gray-600 gap-2">' +
            '<svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>' +
            '<span class="text-xs">No image</span></div>';
    }
}

function closePostModal() {
    document.getElementById('postModal').classList.remove('active');
    _currentPostRowNumber = null;
    _currentPostSourceFile = null;
    _currentPostData = {};
}

function submitPost() {
    var titulo = document.getElementById('pm-titulo').value.trim();
    var texto = document.getElementById('pm-texto').value.trim();
    var hashtags = document.getElementById('pm-hashtags').value.trim();
    var foto = document.getElementById('pm-foto').value.trim();
    var status = document.getElementById('pm-status');
    var submitBtn = document.getElementById('pm-submit');

    if (!titulo && !texto) {
        status.className = 'pm-status error';
        status.textContent = '⚠️ Title or content is required.';
        return;
    }

    // Determine action based on source file
    var isTwitter = _currentPostSourceFile && _currentPostSourceFile.indexOf('twitter_posts_') === 0;
    var action = isTwitter ? 'post_twitter' : 'post_general';
    var endpoint = '../post_now.php';

    var payload = {
        action: action,
        titulo: titulo,
        texto: texto,
        hashtags: hashtags,
        fotos: foto ? [foto] : [],
        // Extra fields for MySQL storage
        row_number: _currentPostData.row_number || null,
        source_file: _currentPostSourceFile,
        slug: _currentPostData.slug || '',
        resumen: _currentPostData.resumen || '',
        contenido: _currentPostData.contenido || '',
        categoria: _currentPostData.categoria || '',
        url_image: _currentPostData.url_image || '',
        promt_idea_image: _currentPostData.promt_idea_image || '',
        fecha_registro: _currentPostData.fecha_registro || '',
        status: _currentPostData.status || ''
    };

    // Log endpoint and payload to console
    console.log('=== POST TO METRICOOL ===');
    console.log('Endpoint:', endpoint);
    console.log('Action:', action);
    console.log('Source file:', _currentPostSourceFile);
    console.log('Payload:', JSON.stringify(payload, null, 2));
    console.log('=========================');

    // Show loading
    status.className = 'pm-status loading';
    status.textContent = '⏳ Publishing to Metricool...';
    submitBtn.disabled = true;

    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            console.log('Response:', data);
            if (data.error) {
                status.className = 'pm-status error';
                status.textContent = '❌ Error: ' + data.error;
                submitBtn.disabled = false;
            } else {
                status.className = 'pm-status success';
                status.textContent = '✅ Posted successfully! Updating status...';

                // Update decision to send_metricool
                if (_currentPostRowNumber) {
                    fetch(window.location.pathname, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            row_number: _currentPostRowNumber,
                            source_file: _currentPostSourceFile || '',
                            decision: 'send_metricool'
                        })
                    })
                        .then(function (r) { return r.json(); })
                        .then(function () {
                            status.textContent = '✅ Posted & marked as sent!';
                            setTimeout(function () { window.location.reload(); }, 1500);
                        })
                        .catch(function () {
                            status.textContent = '✅ Posted! (status update failed)';
                            setTimeout(function () { window.location.reload(); }, 1500);
                        });
                } else {
                    setTimeout(function () { window.location.reload(); }, 2000);
                }
            }
        })
        .catch(function (err) {
            status.className = 'pm-status error';
            status.textContent = '❌ Network error: ' + err.message;
            submitBtn.disabled = false;
        });
}

// Update preview when image URL changes
document.getElementById('pm-foto').addEventListener('input', function () {
    updateModalPreview(this.value.trim());
});
