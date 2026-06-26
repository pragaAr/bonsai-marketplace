<div
  x-data="{
    toasts: [],
    _timers: {},

    addToast(detail) {
      const toast = typeof detail === 'string' ? { message: detail } : detail;
      const id = Date.now() + Math.random();
      const item = {
        id,
        message: toast.message ?? '',
        duration: toast.duration ?? 3000,
        remaining: toast.duration ?? 3000,
        startedAt: null,
        isPaused: false,
        actionUrl: toast.actionUrl ?? null,
        actionText: toast.actionText ?? null,
      };

      this.toasts.push(item);
      this._schedule(item);
    },

    _schedule(item) {
      if (item.remaining <= 0) return;

      item.startedAt = Date.now();
      this._timers[item.id] = setTimeout(() => this._remove(item.id), item.remaining);
    },

    _pause(id) {
      const item = this.toasts.find(t => t.id === id);
      if (!item || item.isPaused || !this._timers[id]) return;

      clearTimeout(this._timers[id]);
      delete this._timers[id];
      item.remaining = Math.max(item.remaining - (Date.now() - item.startedAt), 0);
      item.isPaused = true;
    },

    _resume(id) {
      const item = this.toasts.find(t => t.id === id);
      if (!item || !item.isPaused || item.remaining <= 0) return;

      item.isPaused = false;
      this._schedule(item);
    },

    _remove(id) {
      if (this._timers[id]) {
        clearTimeout(this._timers[id]);
        delete this._timers[id];
      }
      const index = this.toasts.findIndex(t => t.id === id);
      if (index !== -1) this.toasts.splice(index, 1);
    },
  }"
  @toast.window="addToast($event.detail)"
  class="fixed top-20 left-4 right-4 sm:left-auto sm:right-4 z-[100] flex flex-col gap-3 pointer-events-none"
>
  <template x-for="toast in toasts" :key="toast.id">
    <div
      class="toast pointer-events-auto bg-primary text-cream text-sm px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 w-full sm:max-w-sm"
      @mouseenter="_pause(toast.id)"
      @mouseleave="_resume(toast.id)"
    >
      <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="flex-1 min-w-0 break-words" x-text="toast.message"></span>
      <a x-show="toast.actionUrl" :href="toast.actionUrl" class="flex-shrink-0 rounded-full bg-cream px-3 py-1 text-xs font-semibold text-primary transition-colors hover:bg-accent">
        <span x-text="toast.actionText ?? 'Buka'"></span>
      </a>
      <button @click="_remove(toast.id)" class="text-cream/70 hover:text-cream flex-shrink-0 transition-colors p-1 rounded-full hover:bg-white/10" aria-label="Tutup toast">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </template>
</div>

