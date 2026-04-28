let debounceTimer;
let activeController;

function syncFilterState(input) {
    if (!input.classList.contains("filter-select")) {
        return;
    }

    input.classList.toggle("is-active", input.value !== "");
}

async function fetchFilteredResults(element) {
    const wrapper = element.closest(".search-wrapper");
    if (!wrapper) return;

    const url = wrapper.dataset.url || window.location.pathname;
    const targetSelector = wrapper.dataset.target || "#features-table";
    const targetElement = document.querySelector(targetSelector);

    if (targetElement) {
        targetElement.style.minHeight = `${targetElement.offsetHeight}px`;
        targetElement.setAttribute("aria-busy", "true");
        updateLiveRegion("Buscando resultados...");
    }

    const params = new URLSearchParams();
    const inputs = wrapper.querySelectorAll("[data-filter-input]");

    inputs.forEach(input => {
        if (input.value) {
            params.append(input.name, input.value);
        }
    });

    try {
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();

        const response = await fetch(`${url}?${params}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
            signal: activeController.signal
        });

        if (!response.ok) throw new Error("Erro na busca");

        const html = await response.text();

        if (targetElement) {
            targetElement.innerHTML = html;
            targetElement.setAttribute("aria-busy", "false");
            targetElement.style.minHeight = "";
            updateLiveRegion("Resultados atualizados.");
        }
    } catch (error) {
        if (error.name === "AbortError") {
            return;
        }

        console.error(error);
        if (targetElement) {
            targetElement.setAttribute("aria-busy", "false");
            targetElement.style.minHeight = "";
        }
        updateLiveRegion("Erro ao carregar resultados.");
    }
}

function updateLiveRegion(message) {
    let region = document.getElementById("search-live-announcer");
    if (!region) {
        region = document.createElement("div");
        region.id = "search-live-announcer";
        region.setAttribute("aria-live", "polite");
        region.classList.add("sr-only");
        document.body.appendChild(region);
    }
    region.textContent = message;
}

document.addEventListener("input", (e) => {
    if (!e.target.hasAttribute("data-filter-input")) return;
    if (e.target.tagName === "SELECT") return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchFilteredResults(e.target);
    }, 300); // Aguarda 300ms
});

document.addEventListener("change", (e) => {
    if (e.target.tagName === "SELECT" && e.target.hasAttribute("data-filter-input")) {
        syncFilterState(e.target);
        fetchFilteredResults(e.target);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".filter-select[data-filter-input]").forEach(syncFilterState);
});
