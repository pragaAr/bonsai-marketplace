import "./bootstrap";
import TomSelect from "tom-select";

// ---------------------------------------------------------------------------
// Lifecycle states — digunakan sebagai guard, bukan boolean flag.
// Memudahkan async flow (remote search, dll.) di masa depan.
// ---------------------------------------------------------------------------
const TS_STATE = Object.freeze({
  UNINITIALIZED: "UNINITIALIZED",
  INITIALIZING: "INITIALIZING",
  READY: "READY",
  DESTROYED: "DESTROYED",
});

// ---------------------------------------------------------------------------
// Global registry — secondary role only.
// Primary: element.tomselect (native TomSelect property)
// Registry digunakan hanya untuk:
//   1. Bulk cleanup saat wire:navigate
//   2. Debug
// ---------------------------------------------------------------------------
window.__plugins = window.__plugins || {};
window.__plugins.tomSelect = window.__plugins.tomSelect || {
  // Simpan elemen DOM, bukan instance — akses instance via el.tomselect
  _elements: new Map(),

  register(id, el) {
    this._elements.set(id, el);
  },

  unregister(id) {
    const el = this._elements.get(id);
    if (el && el.tomselect) {
      el.tomselect.destroy();
    }
    this._elements.delete(id);
  },

  // Debug helper — list semua instance aktif di console
  debug() {
    console.group("TomSelect Registry");
    this._elements.forEach((el, id) => {
      console.log(id, "→", el.tomselect?._state ?? "no instance", el);
    });
    console.groupEnd();
  },
};

// ---------------------------------------------------------------------------
// Alpine component registration
// ---------------------------------------------------------------------------
const registerAlpinePlugins = () => {
  if (!window.Alpine) {
    console.warn(
      "TomSelect could not be registered because Alpine is not available.",
    );
    return;
  }

  window.Alpine.data("tomSelect", (config = {}) => ({
    // -----------------------------------------------------------------------
    // State
    // -----------------------------------------------------------------------
    instanceId: null,
    _state: TS_STATE.UNINITIALIZED,
    value: config.value !== undefined ? config.value : null,
    show: config.show,

    // -----------------------------------------------------------------------
    // Public getter: expose TomSelect instance for existing Alpine templates
    // -----------------------------------------------------------------------
    get tomselect() {
      return this._selectEl()?.tomselect ?? null;
    },

    // -----------------------------------------------------------------------
    // Public: set value with guard — tidak memanggil setValue jika sama
    // -----------------------------------------------------------------------
    _syncValue(value) {
      const el = this._selectEl();
      if (!el || !el.tomselect || this._state !== TS_STATE.READY) return;

      const ts = el.tomselect;
      const normalized = value === null || value === undefined ? "" : String(value);
      const current = ts.getValue();

      // Skip jika nilai sudah sama — hindari flicker & event duplikat
      if (normalized === "" || normalized === null) {
        if (current !== "") ts.clear(true);
      } else if (current !== normalized) {
        ts.setValue(normalized, true);
      }
    },

    // -----------------------------------------------------------------------
    // Private: resolve select element via x-ref atau querySelector fallback
    // -----------------------------------------------------------------------
    _selectEl() {
      const refName = config.ref || "select";
      return this.$refs[refName] || this.$el.querySelector("select");
    },

    // -----------------------------------------------------------------------
    // Private: build TomSelect options object
    // -----------------------------------------------------------------------
    _buildOptions(selectEl) {
      const defaultLabel = config.options ? "label" : "text";
      const opts = {
        valueField: config.valueField || "value",
        labelField: config.labelField || defaultLabel,
        searchField: config.searchField || [defaultLabel],
        placeholder:
          config.placeholder ||
          selectEl.getAttribute("placeholder") ||
          "Pilih...",
        allowEmptyOption:
          config.allowEmptyOption !== undefined
            ? config.allowEmptyOption
            : true,
        maxItems: config.maxItems || 1,
        plugins: config.plugins || [],
        onChange: (val) => {
          this.value = val;
          selectEl.value = val ?? "";
          // Trigger native events untuk Livewire morphing compatibility
          selectEl.dispatchEvent(new Event("change", { bubbles: true }));
          selectEl.dispatchEvent(new Event("input", { bubbles: true }));
        },
      };

      // Remote/on-demand loading
      if (config.onDemand) {
        opts.load = (query, callback) => {
          if (config.onSearch) {
            config.onSearch(query, callback);
          } else if (config.loadUrl) {
            const url = config.loadUrl.replace(
              "{query}",
              encodeURIComponent(query),
            );
            fetch(url)
              .then((res) => res.json())
              .then((data) => {
                callback(config.mapResponse ? config.mapResponse(data) : data);
              })
              .catch(() => callback());
          } else {
            callback();
          }
        };
      }

      // Static options dari config
      if (config.options) {
        opts.options = config.options;
      }

      return opts;
    },

    // -----------------------------------------------------------------------
    // Private: create TomSelect instance — dipanggil maksimal 1x
    // -----------------------------------------------------------------------
    _doInit() {
      // Guard: jangan init ulang jika sudah atau sedang diinisialisasi
      if (this._state !== TS_STATE.UNINITIALIZED) return;

      const selectEl = this._selectEl();
      if (!selectEl) {
        console.warn(
          `TomSelect: select element not found (ref="${config.ref || "select"}")`,
        );
        return;
      }

      this._state = TS_STATE.INITIALIZING;

      // --- Phase 0: Instrumentation ---
      const markStart = `ts-init-start:${this.instanceId}`;
      const markEnd = `ts-init-end:${this.instanceId}`;
      performance.mark(markStart);

      // Sync initial value jika belum di-set via config/entangle
      if (this.value === null || this.value === undefined) {
        this.value = selectEl.value;
      }

      const ts = new TomSelect(selectEl, this._buildOptions(selectEl));

      // --- Phase 0: Measure ---
      performance.mark(markEnd);
      performance.measure(
        `TomSelect Init [${config.ref || "select"}]`,
        markStart,
        markEnd,
      );

      // Cegah Enter di search input menyubmit form induk
      if (ts.control_input) {
        ts.control_input.addEventListener("keydown", (e) => {
          if (e.key === "Enter") {
            e.preventDefault();
            e.stopPropagation();
          }
        });
      }

      // Register — simpan elemen DOM, bukan instance
      window.__plugins.tomSelect.register(this.instanceId, selectEl);

      this._state = TS_STATE.READY;

      // Sync nilai awal setelah instance siap
      this._syncValue(this.value);

      // Watch perubahan value dari Alpine/@entangle
      this.$watch("value", (newValue) => {
        this._syncValue(newValue);
      });

      // Watch show — reset focus/active-option saat modal dibuka ulang
      this.$watch("show", (opened) => {
        if (!opened || this._state !== TS_STATE.READY) return;
        const el = this._selectEl();
        if (!el?.tomselect) return;
        this.$nextTick(() => {
          el.tomselect.blur();
          el.tomselect.clearActiveOption();
        });
      });
    },

    // -----------------------------------------------------------------------
    // Alpine lifecycle: init
    // -----------------------------------------------------------------------
    init() {
      this.instanceId = "ts_" + Math.random().toString(36).substring(2, 9);

      if (!config.lazy) {
        // lazy: false (default) — init langsung, cocok untuk select yang selalu visible
        this._doInit();
        return;
      }

      // Edge case: modal sudah terbuka saat component dimount (misal wire:navigate)
      if (this.show) {
        this._doInit();
        return;
      }

      // lazy: true — tunggu sampai show pertama kali true.
      // Satu strategi: show watcher. Tidak ada IntersectionObserver.
      // Tidak menggunakan return value $watch (tidak reliable di semua versi Alpine).
      // Guard double-init sudah ditangani oleh _state lifecycle di _doInit().
      this.$watch("show", (val) => {
        if (!val) return;
        this._doInit(); // _state guard mencegah double-init jika watcher masih aktif
      });
    },

    // -----------------------------------------------------------------------
    // Alpine lifecycle: destroy
    // -----------------------------------------------------------------------
    destroy() {
      if (this._state === TS_STATE.DESTROYED) return;
      this._state = TS_STATE.DESTROYED;
      window.__plugins.tomSelect.unregister(this.instanceId);
    },
  }));

  // -------------------------------------------------------------------------
  // Layout state (tidak berubah)
  // -------------------------------------------------------------------------
  window.Alpine.data("layoutState", () => ({
    sidebarOpen: false,
    currentTime: "",

    initTime() {
      this.updateTime();
      setInterval(() => this.updateTime(), 1000);
    },

    updateTime() {
      const now = new Date();
      const pad = (value) => String(value).padStart(2, "0");
      const monthNames = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des",
      ];
      this.currentTime = `${pad(now.getDate())} ${monthNames[now.getMonth()]} ${now.getFullYear()} - ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    },
  }));
};

if (window.Alpine) {
  registerAlpinePlugins();
} else {
  document.addEventListener("alpine:init", registerAlpinePlugins, {
    once: true,
  });
}

const initHomeLoader = () => {
  const loader = document.querySelector("[data-page-loader]");
  const loaderSvg = loader?.querySelector("[data-page-loader-svg]");

  if (
    !loader ||
    !loaderSvg ||
    !document.documentElement.classList.contains("home-loader-active")
  ) {
    return;
  }

  const reducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  const removeLoader = () => {
    if (loader.isConnected) {
      loader.remove();
    }
  };

  const finalizeHide = () => {
    if (loader.dataset.hidden === "true") {
      return;
    }

    loader.dataset.hidden = "true";
    loader.classList.add("page-loader--hidden");
    document.documentElement.classList.remove("home-loader-active");
    delete document.documentElement.dataset.homeLoaderActive;

    try {
      sessionStorage.setItem("home-loader-seen", "1");
    } catch (error) {
      // Session storage can be blocked in private contexts; ignore safely.
    }

    if (reducedMotion) {
      removeLoader();
    }
  };

  const handleTransitionEnd = (event) => {
    if (event.target !== loader || event.propertyName !== "opacity") {
      return;
    }

    removeLoader();
  };

  const hideLoader = () => {
    finalizeHide();
  };

  loader.addEventListener("transitionend", handleTransitionEnd);

  if (reducedMotion) {
    hideLoader();
    return;
  }

  if (
    typeof loaderSvg.getAnimations === "function" &&
    loaderSvg.getAnimations().some((animation) => animation.playState === "finished")
  ) {
    hideLoader();
    return;
  }

  loaderSvg.addEventListener("animationend", hideLoader, { once: true });
  loaderSvg.addEventListener("animationcancel", hideLoader, { once: true });
};

initHomeLoader();
