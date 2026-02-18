<?php /** Post to Metricool Modal — TailwindCSS */ ?>

<div class="fixed inset-0 bg-black/80 z-[1000] hidden items-center justify-center backdrop-blur-sm" id="postModal">
    <div class="bg-surface-light rounded-2xl border border-surface-border w-[90%] max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl shadow-black/50"
         onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-5 border-b border-surface-border">
            <h3 class="text-white font-semibold text-lg">🚀 Post to Metricool</h3>
            <button onclick="closePostModal()"
                    class="text-gray-600 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-all text-lg cursor-pointer">✕</button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-gray-500 text-xs font-semibold mb-1.5 uppercase tracking-wide">Title</label>
                <input type="text" id="pm-titulo" placeholder="Post title..."
                       class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                              focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
            </div>
            <div>
                <label class="block text-gray-500 text-xs font-semibold mb-1.5 uppercase tracking-wide">Content</label>
                <textarea id="pm-texto" rows="8" placeholder="Post content..."
                          class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                                 resize-y min-h-[100px] focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all font-['Inter',sans-serif]"></textarea>
            </div>
            <div>
                <label class="block text-gray-500 text-xs font-semibold mb-1.5 uppercase tracking-wide">Hashtags</label>
                <input type="text" id="pm-hashtags" placeholder="#hashtag1 #hashtag2"
                       class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                              focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
            </div>
            <div>
                <label class="block text-gray-500 text-xs font-semibold mb-1.5 uppercase tracking-wide">Image URL</label>
                <input type="text" id="pm-foto" placeholder="https://..."
                       class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                              focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
                <div id="pm-preview" class="mt-2 [&>img]:max-w-full [&>img]:max-h-36 [&>img]:rounded-lg [&>img]:border [&>img]:border-surface-border"></div>
            </div>

            <div id="pm-status" class="pm-status hidden rounded-lg text-sm px-4 py-2.5"></div>
        </div>

        <!-- Footer -->
        <div class="flex gap-3 px-6 py-4 border-t border-surface-border justify-end">
            <button onclick="closePostModal()"
                    class="px-5 py-2.5 bg-transparent border border-surface-border rounded-lg text-gray-500 text-sm cursor-pointer
                           hover:border-gray-500 hover:text-gray-300 transition-all">
                Cancel
            </button>
            <button id="pm-submit" onclick="submitPost()"
                    class="px-6 py-2.5 bg-violet-500 border-none rounded-lg text-black text-sm font-semibold cursor-pointer
                           hover:bg-violet-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                🚀 Publish Now
            </button>
        </div>
    </div>
</div>
