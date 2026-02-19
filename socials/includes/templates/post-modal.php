<?php /** Post to Metricool Modal — Two-column layout */ ?>

<div class="fixed inset-0 bg-black/80 z-[1000] hidden items-center justify-center backdrop-blur-sm" id="postModal">
    <div class="bg-surface-light rounded-2xl border border-surface-border w-[92%] max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl shadow-black/50"
         onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-surface-border">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                <div>
                    <h3 class="text-white font-semibold text-base">Post to Metricool</h3>
                    <p id="pm-target" class="text-gray-500 text-[11px] mt-0.5"></p>
                </div>
            </div>
            <button onclick="closePostModal()"
                    class="text-gray-600 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-all text-lg cursor-pointer">✕</button>
        </div>

        <!-- Body: Two columns -->
        <div class="flex flex-col md:flex-row">
            
            <!-- Left: Image Preview -->
            <div class="md:w-2/5 p-5 flex items-start justify-center border-b md:border-b-0 md:border-r border-surface-border bg-[#0d0d14]">
                <div id="pm-preview-area" class="w-full">
                    <div id="pm-preview-img" class="rounded-xl overflow-hidden border border-surface-border bg-surface-light">
                        <div class="w-full aspect-square bg-gradient-to-br from-surface-border to-surface-light flex flex-col items-center justify-center text-gray-600 gap-2">
                            <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                            <span class="text-xs">No image</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-gray-500 text-[10px] font-semibold mb-1 uppercase tracking-wider">Image URL</label>
                        <input type="text" id="pm-foto" placeholder="https://..."
                               class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3 py-2 text-xs
                                      focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
                    </div>
                </div>
            </div>

            <!-- Right: Form Fields -->
            <div class="md:w-3/5 p-5 space-y-3.5">
                <div>
                    <label class="block text-gray-500 text-[10px] font-semibold mb-1 uppercase tracking-wider">Title</label>
                    <input type="text" id="pm-titulo" placeholder="Post title..."
                           class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                                  focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
                </div>
                <div>
                    <label class="block text-gray-500 text-[10px] font-semibold mb-1 uppercase tracking-wider">Content</label>
                    <textarea id="pm-texto" rows="7" placeholder="Post content..."
                              class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                                     resize-y min-h-[120px] focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all font-['Inter',sans-serif]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-500 text-[10px] font-semibold mb-1 uppercase tracking-wider">Hashtags</label>
                    <input type="text" id="pm-hashtags" placeholder="#hashtag1 #hashtag2"
                           class="w-full bg-[#12121a] border border-surface-border rounded-lg text-gray-200 px-3.5 py-2.5 text-sm
                                  focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all">
                </div>

                <div id="pm-status" class="pm-status hidden rounded-lg text-sm px-4 py-2.5"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex gap-3 px-6 py-4 border-t border-surface-border justify-between items-center">
            <div id="pm-platforms" class="flex gap-1.5"></div>
            <div class="flex gap-3">
                <button onclick="closePostModal()"
                        class="px-5 py-2.5 bg-transparent border border-surface-border rounded-lg text-gray-500 text-sm cursor-pointer
                               hover:border-gray-500 hover:text-gray-300 transition-all">
                    Cancel
                </button>
                <button id="pm-submit" onclick="submitPost()"
                        class="px-6 py-2.5 bg-violet-500 border-none rounded-lg text-black text-sm font-semibold cursor-pointer
                               hover:bg-violet-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                    Publish Now
                </button>
            </div>
        </div>
    </div>
</div>
