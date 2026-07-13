import "./bootstrap";
import TomSelect from "tom-select";

// Global plugin registry to manage instances and prevent conflicts
window.__plugins = window.__plugins || {};
window.__plugins.tomSelect = window.__plugins.tomSelect || {
  instances: new Map(),
  register(id, instance) {
    this.instances.set(id, instance);
  },
  unregister(id) {
    const instance = this.instances.get(id);
    if (instance) {
      instance.destroy();
      this.instances.delete(id);
    }
  },
  get(id) {
    return this.instances.get(id);
  },
};

const registerAlpinePlugins = () => {
  if (!window.Alpine) {
    console.warn(
      "TomSelect could not be registered because Alpine is not available.",
    );
    return;
  }

  window.Alpine.data("tomSelect", (config = {}) => ({
    instanceId: null,
    tomselect: null,
    value: config.value !== undefined ? config.value : null,
    show: config.show,

    setTomValue(value) {
      if (value === null || value === "" || value === undefined) {
        this.tomselect.clear(true); // silent, tidak memicu onChange
        return;
      }

      this.tomselect.setValue(String(value), true);
    },

    init() {
      // Generate unique instance ID
      this.instanceId = "ts_" + Math.random().toString(36).substring(2, 9);
      // Determine which x-ref to use for the select element. Default to 'select',
      // but allow overriding via `config.ref` (e.g., 'selectCategory', 'selectVendor').
      const refName = config.ref || "select";
      let selectEl = this.$refs[refName];

      // Fallback: if a specific ref isn't present, try to find a select inside component root
      if (!selectEl) {
        selectEl = this.$el.querySelector("select");
      }

      if (!selectEl) {
        console.warn(
          `TomSelect error: Target select element (x-ref="${refName}" or <select> in scope) not found.`,
        );
        return;
      }

      // Sync initial value from select element if not set by config/entangle
      if (this.value === null || this.value === undefined) {
        this.value = selectEl.value;
      }

      // Merge default configurations
      const defaultLabel = config.options ? "label" : "text";
      const options = {
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
        // Allow customized dropdown styling and parenting
        // dropdownParent: config.dropdownParent || "body",
        plugins: config.plugins || [],
        onChange: (val) => {
          this.value = val;
          selectEl.value = val ?? "";
          // Trigger input and change event for native listener / Livewire morphing compatibility
          selectEl.dispatchEvent(new Event("change", { bubbles: true }));
          selectEl.dispatchEvent(new Event("input", { bubbles: true }));
        },
      };

      // Support on-demand dynamic option loading (AJAX search / lazy-load)
      if (config.onDemand) {
        options.load = (query, callback) => {
          // Call the provided on-demand handler
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
                const results = config.mapResponse
                  ? config.mapResponse(data)
                  : data;
                callback(results);
              })
              .catch(() => callback());
          } else {
            callback();
          }
        };
      }

      // If options are supplied directly through config
      if (config.options) {
        options.options = config.options;
      }

      // Initialize Tom Select
      this.tomselect = new TomSelect(selectEl, options);

      // Prevent Enter key in TomSelect search input from submitting the parent form
      if (this.tomselect.control_input) {
        this.tomselect.control_input.addEventListener("keydown", (e) => {
          if (e.key === "Enter") {
            e.preventDefault();
            e.stopPropagation();
          }
        });
      }

      // Register instance
      window.__plugins.tomSelect.register(this.instanceId, this.tomselect);

      // Set initial selection
      this.setTomValue(this.value);

      // React to value updates from Alpine/Livewire (@entangle)
      this.$watch("value", (newValue) => {
        this.setTomValue(newValue);
      });

      this.$watch("show", (opened) => {
        if (!opened || !this.tomselect) return;

        this.$nextTick(() => {
          this.tomselect.blur();
          this.tomselect.clearActiveOption();
        });
      });
    },

    destroy() {
      // Clean up when Alpine component is removed
      window.__plugins.tomSelect.unregister(this.instanceId);
    },
  }));

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
