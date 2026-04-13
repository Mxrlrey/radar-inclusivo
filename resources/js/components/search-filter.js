let debounceTimer;

async function fetchFilteredResults(element) {
    const wrapper = element.closest(".search-wrapper");
    if (!wrapper) return;

    const url = wrapper.dataset.url || window.location.pathname;
    const targetSelector = wrapper.dataset.target || "#features-table";
    const targetElement = document.querySelector(targetSelector);

    if (targetElement) {
        targetElement.style.opacity = "0.5";
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
        const response = await fetch(`${url}?${params}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });

        if (!response.ok) throw new Error("Erro na busca");

        const html = await response.text();

        if (targetElement) {
            targetElement.innerHTML = html;
            targetElement.style.opacity = "1";
            updateLiveRegion("Resultados atualizados.");
        }
    } catch (error) {
        console.error(error);
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

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchFilteredResults(e.target);
    }, 300); // Aguarda 300ms
});

document.addEventListener("change", (e) => {
    if (e.target.tagName === "SELECT" && e.target.hasAttribute("data-filter-input")) {
        fetchFilteredResults(e.target);
    }
});
