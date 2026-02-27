<?php /** Topics Gen — Manage content topics for AI generation */ ?>

<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-white text-2xl font-bold tracking-tight mb-1 flex items-center gap-2">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
            Topics Gen
        </h1>
        <p class="text-gray-500 text-sm"><span class="text-amber-400 font-semibold" id="topics-count">0</span> topics for content generation</p>
    </div>
    <button onclick="openTopicModal()" id="btn-new-topic"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                   bg-gradient-to-r from-amber-500 to-orange-500 text-white
                   hover:from-amber-400 hover:to-orange-400
                   shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30
                   transition-all duration-200 hover:scale-[1.02] active:scale-95 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        New Topic
    </button>
</div>

<!-- Filter bar -->
<div class="flex items-center gap-2 mb-5 flex-wrap">
    <button onclick="filterTopics('all')" data-filter="all"
            class="topic-filter active px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer
                   bg-white/10 text-white">All</button>
    <button onclick="filterTopics('draft')" data-filter="draft"
            class="topic-filter px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer
                   text-gray-500 hover:bg-white/5 hover:text-gray-300">Draft</button>
    <button onclick="filterTopics('ready')" data-filter="ready"
            class="topic-filter px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer
                   text-gray-500 hover:bg-white/5 hover:text-gray-300">Ready</button>
    <button onclick="filterTopics('used')" data-filter="used"
            class="topic-filter px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer
                   text-gray-500 hover:bg-white/5 hover:text-gray-300">Used</button>
</div>
</div>

<!-- Date Filters Bar -->
<div class="flex items-center gap-4 mb-6 flex-wrap bg-surface-light/50 p-4 rounded-xl border border-surface-border">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 text-gray-500 font-bold text-[10px] uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
            From
        </div>
        <input type="date" id="filter-start-date" onchange="applyDateFilters()"
               class="bg-surface border border-surface-border rounded-lg px-3 py-1.5 text-xs text-white outline-none focus:border-violet-500/50 transition-all">
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 text-gray-500 font-bold text-[10px] uppercase tracking-wider">To</div>
        <input type="date" id="filter-end-date" onchange="applyDateFilters()"
               class="bg-surface border border-surface-border rounded-lg px-3 py-1.5 text-xs text-white outline-none focus:border-violet-500/50 transition-all">
    </div>

    <div class="flex items-center gap-2 ml-auto">
        <button onclick="setDatePreset(7)" data-preset="7"
                class="date-preset px-4 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer border border-surface-border text-gray-400 hover:text-white hover:bg-white/5">7 DAYS</button>
        <button onclick="setDatePreset(30)" data-preset="30"
                class="date-preset px-4 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer border border-surface-border text-gray-400 hover:text-white hover:bg-white/5">30 DAYS</button>
        <button onclick="setDatePreset(90)" data-preset="90"
                class="date-preset px-4 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer border border-surface-border text-gray-400 hover:text-white hover:bg-white/5">90 DAYS</button>
        <button onclick="setDatePreset(0)" data-preset="0"
                class="date-preset active px-4 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer border border-surface-border text-gray-400 hover:text-white hover:bg-white/5">ALL</button>
    </div>
</div>

<!-- Loading state -->
<div id="topics-loading" class="text-center py-20 text-gray-600">
    <div class="text-5xl mb-4 animate-pulse">💡</div>
    <h2 class="text-gray-400 text-lg font-semibold mb-2">Loading topics...</h2>
</div>

<!-- Empty state -->
<div id="topics-empty" class="text-center py-20 text-gray-600 hidden">
    <div class="text-5xl mb-4">📝</div>
    <h2 class="text-gray-400 text-lg font-semibold mb-2">No topics yet</h2>
    <p class="text-sm mb-6">Create your first topic to start generating content with AI.</p>
    <button onclick="openTopicModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                   bg-gradient-to-r from-amber-500 to-orange-500 text-white
                   hover:from-amber-400 hover:to-orange-400 shadow-lg shadow-amber-500/20
                   transition-all cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Create first topic
    </button>
</div>

<!-- Topics grid -->
<div id="topics-list" class="hidden grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <!-- Cards injected by JS -->
</div>

<!-- ===== Create / Edit Modal ===== -->
<div class="fixed inset-0 bg-black/80 z-[1100] hidden items-center justify-center backdrop-blur-sm" id="topicModal" onclick="closeTopicModal()">
    <div class="bg-[#16161e] w-full h-full overflow-y-auto shadow-2xl shadow-black/50"
         onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-surface-border">
            <h3 class="text-white font-semibold text-base flex items-center gap-2" id="modal-title">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                New Topic
            </h3>
            <button onclick="closeTopicModal()" class="text-gray-600 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-all text-lg cursor-pointer">✕</button>
        </div>
        <!-- Form -->
        <form id="topicForm" onsubmit="saveTopic(event)" class="p-6 space-y-4">
            <input type="hidden" id="topic-id" value="">

            <div>
                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Title *</label>
                <input type="text" id="topic-title" required placeholder="e.g. Digital Marketing Trends 2026"
                       class="w-full bg-surface border border-surface-border rounded-xl px-4 py-2.5 text-sm text-white
                              placeholder-gray-600 focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30
                              outline-none transition-all">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider">Description</label>
                    <button type="button" onclick="generateDescription()"
                            id="btn-ai-generate"
                            style="background: linear-gradient(135deg, rgba(139,92,246,0.25), rgba(217,70,239,0.25)); border: 1px solid rgba(139,92,246,0.4);"
                            class="flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-semibold
                                   text-violet-300 hover:text-violet-200
                                   transition-all cursor-pointer
                                   disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                        <span id="btn-ai-text">✨ Generate with AI</span>
                    </button>
                </div>
                <textarea id="topic-description" rows="3" placeholder="Describe the topic and key points to cover..."
                          class="w-full bg-surface border border-surface-border rounded-xl px-4 py-2.5 text-sm text-white
                                 placeholder-gray-600 focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30
                                 outline-none transition-all resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Keywords</label>
                    <input type="text" id="topic-keywords" placeholder="seo, marketing, ai"
                           class="w-full bg-surface border border-surface-border rounded-xl px-4 py-2.5 text-sm text-white
                                  placeholder-gray-600 focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30
                                  outline-none transition-all">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Category</label>
                    <input type="text" id="topic-category" placeholder="Marketing"
                           class="w-full bg-surface border border-surface-border rounded-xl px-4 py-2.5 text-sm text-white
                                  placeholder-gray-600 focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30
                                  outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Status</label>
                <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="topic-status" value="draft" checked class="hidden peer">
                        <div class="text-center px-3 py-2 rounded-lg text-xs font-semibold border border-surface-border
                                    text-gray-500 peer-checked:bg-gray-500/15 peer-checked:text-gray-300 peer-checked:border-gray-500/40
                                    transition-all hover:bg-white/5">Draft</div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="topic-status" value="ready" class="hidden peer">
                        <div class="text-center px-3 py-2 rounded-lg text-xs font-semibold border border-surface-border
                                    text-gray-500 peer-checked:bg-emerald-500/15 peer-checked:text-emerald-400 peer-checked:border-emerald-500/40
                                    transition-all hover:bg-white/5">Ready</div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="topic-status" value="used" class="hidden peer">
                        <div class="text-center px-3 py-2 rounded-lg text-xs font-semibold border border-surface-border
                                    text-gray-500 peer-checked:bg-violet-500/15 peer-checked:text-violet-400 peer-checked:border-violet-500/40
                                    transition-all hover:bg-white/5">Used</div>
                    </label>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="button" onclick="closeTopicModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-400
                               border border-surface-border hover:bg-white/5 transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="btn-save-topic"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white
                               bg-gradient-to-r from-amber-500 to-orange-500
                               hover:from-amber-400 hover:to-orange-400
                               shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== Delete Confirmation Modal ===== -->
<div class="fixed inset-0 bg-black/80 z-[1200] hidden items-center justify-center backdrop-blur-sm" id="deleteModal" onclick="closeDeleteModal()">
    <div class="bg-[#16161e] rounded-2xl border border-red-500/20 w-[92%] max-w-sm shadow-2xl shadow-black/50 p-6 text-center"
         onclick="event.stopPropagation()">
        <div class="text-4xl mb-3">🗑️</div>
        <h3 class="text-white font-semibold text-base mb-2">Delete this topic?</h3>
        <p class="text-gray-500 text-sm mb-5">This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-400
                           border border-surface-border hover:bg-white/5 transition-all cursor-pointer">
                Cancel
            </button>
            <button onclick="confirmDelete()" id="btn-confirm-delete"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white
                           bg-gradient-to-r from-red-500 to-red-600
                           hover:from-red-400 hover:to-red-500
                           shadow-lg shadow-red-500/20 transition-all cursor-pointer">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
var _topics = [];
var _currentFilter = 'all';
var _datePreset = 0; // 0=all, 7, 30, 90
var _deleteTopicId = null;
var API_URL = '../database/topics_api.php';
var AI_PROXY_URL = '../database/ai_proxy.php';

// ── STATUS HELPERS ──────────────────────────────────────────────
function statusBadge(status) {
    var map = {
        'draft': { cls: 'bg-gray-500/15 text-gray-400 border-gray-500/30', label: 'Draft' },
        'ready': { cls: 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', label: 'Ready' },
        'used':  { cls: 'bg-violet-500/15 text-violet-400 border-violet-500/30', label: 'Used' }
    };
    var s = map[status] || map['draft'];
    return '<span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border ' + s.cls + '">' + s.label + '</span>';
}

function escHtml(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function timeAgo(dateStr) {
    if (!dateStr) return '';
    var d = new Date(dateStr);
    var now = new Date();
    var diff = Math.floor((now - d) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'min ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    if (diff < 2592000) return Math.floor(diff / 86400) + 'd ago';
    return d.toLocaleDateString('en');
}

// ── AI CONTENT GENERATION ───────────────────────────────────────
function generateDescription() {
    var title = document.getElementById('topic-title').value.trim();
    if (!title) {
        alert('Please enter a title first.');
        document.getElementById('topic-title').focus();
        return;
    }

    var btn = document.getElementById('btn-ai-generate');
    var btnText = document.getElementById('btn-ai-text');

    btn.disabled = true;
    btnText.textContent = '⏳ Generating...';

    var prompt = 'You are a social media content strategist. Given the following topic title, generate:\n1. A concise and compelling description (2-3 sentences) that outlines the key points to cover when creating content about this topic. Be specific and actionable.\n2. A list of 4-6 relevant keywords (single words or short phrases), comma-separated.\n3. A single category that best classifies this topic.\n\nRespond ONLY with valid JSON in this exact format, no markdown, no code fences:\n{"description": "...", "keywords": "keyword1, keyword2, keyword3", "category": "..."}\n\nTopic: ' + title;

    fetch(AI_PROXY_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            contents: [{ parts: [{ text: prompt }] }],
            generationConfig: {
                temperature: 0.7,
                maxOutputTokens: 512,
                responseMimeType: 'application/json'
            }
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.disabled = false;
        btnText.textContent = '✨ Generate with AI';

        if (data.candidates && data.candidates[0] && data.candidates[0].content) {
            var rawText = data.candidates[0].content.parts[0].text.trim();
            try {
                var result = JSON.parse(rawText);
                document.getElementById('topic-description').value = (result.description || '').trim();
                document.getElementById('topic-keywords').value = (result.keywords || '').trim();
                document.getElementById('topic-category').value = (result.category || '').trim();
                document.getElementById('topic-description').focus();
            } catch(e) {
                // Fallback: if JSON parsing fails, put raw text in description
                document.getElementById('topic-description').value = rawText;
                document.getElementById('topic-description').focus();
            }
        } else {
            var errMsg = (data.error && data.error.message) ? data.error.message : 'No response from AI';
            alert('AI Error: ' + errMsg);
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btnText.textContent = '✨ Generate with AI';
        alert('Error: ' + err.message);
    });
}

// ── MODAL CONTROLS ──────────────────────────────────────────────
function openTopicModal(topic) {
    document.getElementById('topic-id').value = topic ? topic.id : '';
    document.getElementById('topic-title').value = topic ? topic.title : '';
    document.getElementById('topic-description').value = topic ? (topic.description || '') : '';
    document.getElementById('topic-keywords').value = topic ? (topic.keywords || '') : '';
    document.getElementById('topic-category').value = topic ? (topic.category || '') : '';

    var status = topic ? topic.status : 'draft';
    var radios = document.querySelectorAll('input[name="topic-status"]');
    radios.forEach(function(r) { r.checked = r.value === status; });

    document.getElementById('modal-title').lastChild.textContent = topic ? ' Edit Topic' : ' New Topic';
    document.getElementById('btn-save-topic').textContent = topic ? 'Update' : 'Save';
    document.getElementById('topicModal').classList.add('active');
}

function closeTopicModal() {
    document.getElementById('topicModal').classList.remove('active');
    document.getElementById('topicForm').reset();
    document.getElementById('topic-id').value = '';
}

function openDeleteModal(id) {
    _deleteTopicId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    _deleteTopicId = null;
}

// ── API CALLS ───────────────────────────────────────────────────
function loadTopics() {
    fetch(API_URL)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('topics-loading').classList.add('hidden');
            document.getElementById('topics-count').textContent = data.total || 0;

            if (!data.topics || data.topics.length === 0) {
                document.getElementById('topics-empty').classList.remove('hidden');
                document.getElementById('topics-list').classList.add('hidden');
                return;
            }

            _topics = data.topics;
            document.getElementById('topics-empty').classList.add('hidden');
            renderTopics();
        })
        .catch(function(err) {
            document.getElementById('topics-loading').classList.add('hidden');
            document.getElementById('topics-empty').classList.remove('hidden');
            document.getElementById('topics-empty').querySelector('h2').textContent = 'Error loading';
            document.getElementById('topics-empty').querySelector('p').textContent = err.message;
        });
}

function saveTopic(e) {
    e.preventDefault();
    var id = document.getElementById('topic-id').value;
    var statusRadio = document.querySelector('input[name="topic-status"]:checked');

    var payload = {
        action: id ? 'update' : 'create',
        title: document.getElementById('topic-title').value.trim(),
        description: document.getElementById('topic-description').value.trim(),
        keywords: document.getElementById('topic-keywords').value.trim(),
        category: document.getElementById('topic-category').value.trim(),
        status: statusRadio ? statusRadio.value : 'draft'
    };

    if (id) payload.id = parseInt(id);

    var btn = document.getElementById('btn-save-topic');
    btn.textContent = 'Saving...';
    btn.disabled = true;

    fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.disabled = false;
        if (data.success) {
            closeTopicModal();
            loadTopics();
        } else {
            btn.textContent = id ? 'Update' : 'Save';
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.textContent = id ? 'Update' : 'Save';
        alert('Error: ' + err.message);
    });
}

function confirmDelete() {
    if (!_deleteTopicId) return;

    var btn = document.getElementById('btn-confirm-delete');
    btn.textContent = 'Deleting...';
    btn.disabled = true;

    fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: _deleteTopicId })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.textContent = 'Delete';
        btn.disabled = false;
        if (data.success) {
            closeDeleteModal();
            loadTopics();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(function(err) {
        btn.textContent = 'Delete';
        btn.disabled = false;
        alert('Error: ' + err.message);
    });
}

// ── RENDER & FILTER ─────────────────────────────────────────────
function filterTopics(status) {
    _currentFilter = status;
    document.querySelectorAll('.topic-filter').forEach(function(btn) {
        if (btn.dataset.filter === status) {
            btn.classList.add('bg-white/10', 'text-white');
            btn.classList.remove('text-gray-500');
        } else {
            btn.classList.remove('bg-white/10', 'text-white');
            btn.classList.add('text-gray-500');
        }
    });
    renderTopics();
}

function setDatePreset(days) {
    _datePreset = days;
    var startInput = document.getElementById('filter-start-date');
    var endInput = document.getElementById('filter-end-date');

    document.querySelectorAll('.date-preset').forEach(function(btn) {
        btn.classList.remove('active');
        if (parseInt(btn.dataset.preset) === days) btn.classList.add('active');
    });

    if (days === 0) {
        startInput.value = '';
        endInput.value = '';
    } else {
        var end = new Date();
        var start = new Date();
        start.setDate(end.getDate() - days);

        startInput.value = start.toISOString().split('T')[0];
        endInput.value = end.toISOString().split('T')[0];
    }
    renderTopics();
}

function applyDateFilters() {
    // If user manually changes dates, clear the preset highlights
    document.querySelectorAll('.date-preset').forEach(function(btn) {
        btn.classList.remove('active');
    });
    _datePreset = -1; // custom
    renderTopics();
}

function renderTopics() {
    var list = document.getElementById('topics-list');
    list.innerHTML = '';
    list.classList.remove('hidden');

    var startVal = document.getElementById('filter-start-date').value;
    var endVal = document.getElementById('filter-end-date').value;
    var startDate = startVal ? new Date(startVal + 'T00:00:00') : null;
    var endDate = endVal ? new Date(endVal + 'T23:59:59') : null;

    var filtered = _topics.filter(function(t) {
        // Status filter
        var matchStatus = (_currentFilter === 'all' || t.status === _currentFilter);
        if (!matchStatus) return false;

        // Date filter
        if (startDate || endDate) {
            var topicDate = new Date(t.created_at);
            if (startDate && topicDate < startDate) return false;
            if (endDate && topicDate > endDate) return false;
        }

        return true;
    });

    if (filtered.length === 0) {
        list.innerHTML = '<div class="col-span-full text-center py-12 text-gray-600">' +
            '<div class="text-3xl mb-2">🔍</div>' +
            '<p class="text-sm">No topics match this filter</p></div>';
        return;
    }

    filtered.forEach(function(topic, idx) {
        var kwHtml = '';
        if (topic.keywords) {
            kwHtml = topic.keywords.split(',').map(function(kw) {
                kw = kw.trim();
                if (!kw) return '';
                return '<span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold bg-amber-500/10 text-amber-400/80">' + escHtml(kw) + '</span>';
            }).join(' ');
        }

        var catHtml = topic.category
            ? '<span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-violet-500/20 text-violet-400">' + escHtml(topic.category) + '</span>'
            : '';

        var card = document.createElement('div');
        card.className = 'group relative flex flex-col p-4 bg-surface-light rounded-xl border border-surface-border hover:border-amber-500/30 hover:bg-surface-light/80 transition-all';
        card.innerHTML =
            '<div class="flex items-start justify-between gap-2 mb-2">' +
                '<h3 class="text-white text-sm font-semibold leading-snug line-clamp-2 flex-1">' + escHtml(topic.title) + '</h3>' +
                statusBadge(topic.status) +
            '</div>' +
            (topic.description
                ? '<p class="text-gray-500 text-xs line-clamp-2 mb-3 leading-relaxed">' + escHtml(topic.description) + '</p>'
                : '<div class="mb-3"></div>') +
            '<div class="flex items-center gap-1.5 flex-wrap mb-3">' + catHtml + ' ' + kwHtml + '</div>' +
            '<div class="mt-auto flex items-center justify-between pt-3 border-t border-surface-border">' +
                '<span class="text-[10px] text-gray-600">' + timeAgo(topic.created_at) + '</span>' +
                '<div class="flex items-center gap-1">' +
                    '<button onclick="openTopicModal(_topics[' + _topics.indexOf(topic) + '])" title="Edit" ' +
                        'class="p-1.5 rounded-lg text-gray-600 hover:text-amber-400 hover:bg-amber-500/10 transition-all cursor-pointer">' +
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>' +
                    '</button>' +
                    '<button onclick="openDeleteModal(' + topic.id + ')" title="Delete" ' +
                        'class="p-1.5 rounded-lg text-gray-600 hover:text-red-400 hover:bg-red-500/10 transition-all cursor-pointer">' +
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>' +
                    '</button>' +
                '</div>' +
            '</div>';

        list.appendChild(card);
    });
}

// ── MODAL SHOW/HIDE CSS HELPER ──────────────────────────────────
// Reuse existing .active pattern from other modals
(function() {
    var style = document.createElement('style');
    style.textContent =
        '#topicModal.active, #deleteModal.active { display: flex !important; }' +
        '#topicModal, #deleteModal { display: none; }' +
        '.date-preset.active { background-color: #bef264 !important; border-color: #bef264 !important; color: #000 !important; }';
    document.head.appendChild(style);
})();

// ── INIT ────────────────────────────────────────────────────────
loadTopics();
</script>
