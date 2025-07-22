document.addEventListener("DOMContentLoaded", function () {
	const selects = document.querySelectorAll("select[id^='ctrl_styleManager']");

	selects.forEach(select => {
		// Falls bereits von Chosen verarbeitet → zurücksetzen
		// If already processed by Chosen → reset
		if (select.nextElementSibling && select.nextElementSibling.classList.contains("chzn-container")) {
			select.nextElementSibling.remove(); // Chosen-UI entfernen / Remove Chosen UI
			select.classList.remove("chzn-done"); // Kennzeichnung entfernen / Remove Chosen marker
		}

		const options = Array.from(select.querySelectorAll("option"));

		// Erste (leere) Option separat behalten
		// Keep the first (empty) option separate
		const firstOption = options.find(opt => opt.value === "");
		const remainingOptions = options.filter(opt => opt !== firstOption);

		// Optionen nach Text-Präfix gruppieren (z. B. "2 Spalten")
		// Group options by text prefix (e.g. "2 Spalten")
		const groups = {};

		remainingOptions.forEach(option => {
			const labelText = option.textContent.trim();
			const match = labelText.match(/^(\d+ Spalten)/);
			const groupLabel = match ? match[1] : "Andere"; // "Other" if no match

			if (!groups[groupLabel]) {
				groups[groupLabel] = [];
			}
			groups[groupLabel].push(option);
		});

		// <select> zurücksetzen und neu befüllen
		// Reset <select> and rebuild
		select.innerHTML = "";
		if (firstOption) select.appendChild(firstOption);

		for (const label in groups) {
			const optgroup = document.createElement("optgroup");
			optgroup.label = label;
			groups[label].forEach(opt => optgroup.appendChild(opt));
			select.appendChild(optgroup);
		}

		// Chosen neu initialisieren
		// Re-initialize Chosen
		if (typeof Chosen === "function" && typeof select.chosen === "function") {
			select.chosen();
		} else if (typeof Elements !== "undefined" && typeof Elements.implement === "function") {
			new Chosen(select);
		}

		// Optional: Event für Aktualisierung (z. B. wenn Listener existiert)
		// Optional: Fire update event (in case listeners exist)
		select.dispatchEvent(new CustomEvent("liszt:updated", { bubbles: true }));
	});
});
