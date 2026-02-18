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

function openPostModal(postData) {
    _currentPostRowNumber = postData.row_number || null;
    _currentPostSourceFile = postData.source_file || '';
    document.getElementById('pm-titulo').value = postData.titulo || '';
    document.getElementById('pm-texto').value = postData.texto || '';
    document.getElementById('pm-hashtags').value = postData.hashtags || '';

    var fotoUrl = (postData.fotos && postData.fotos.length > 0) ? postData.fotos[0] : '';
    document.getElementById('pm-foto').value = fotoUrl;

    // Image preview
    var preview = document.getElementById('pm-preview');
    if (fotoUrl) {
        preview.innerHTML = '<img src="' + fotoUrl + '" alt="Preview">';
    } else {
        preview.innerHTML = '';
    }

    // Reset status
    var status = document.getElementById('pm-status');
    status.className = 'pm-status';
    status.textContent = '';

    // Enable submit
    document.getElementById('pm-submit').disabled = false;

    document.getElementById('postModal').classList.add('active');
}

function closePostModal() {
    document.getElementById('postModal').classList.remove('active');
    _currentPostRowNumber = null;
    _currentPostSourceFile = null;
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

    // Show loading
    status.className = 'pm-status loading';
    status.textContent = '⏳ Publishing to Metricool...';
    submitBtn.disabled = true;

    var payload = {
        action: 'post_general',
        titulo: titulo,
        texto: texto,
        hashtags: hashtags,
        fotos: foto ? [foto] : []
    };

    fetch('../post_now.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
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
    var preview = document.getElementById('pm-preview');
    var url = this.value.trim();
    if (url) {
        preview.innerHTML = '<img src="' + url + '" alt="Preview" onerror="this.parentElement.innerHTML=\'\'">';
    } else {
        preview.innerHTML = '';
    }
});
