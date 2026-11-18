/* global wp, dkmPanel */
(function () {
	'use strict';

	function $$(sel, root) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	/* ------------------------------------------------------------------ */
	/* Pictures                                                            */
	/* ------------------------------------------------------------------ */

	function koppelAfbeelding(veld) {
		var knop = veld.querySelector('[data-dkm-image-pick]');
		var weg = veld.querySelector('[data-dkm-image-clear]');
		var waarde = veld.querySelector('[data-dkm-image-value]');
		var prev = veld.querySelector('.dkmp__img-prev');
		var kiezer = null;

		function toon(src) {
			prev.innerHTML = '';
			if (src) {
				var img = document.createElement('img');
				img.src = src;
				img.alt = '';
				prev.appendChild(img);
			}
			weg.hidden = !src;
		}

		knop.addEventListener('click', function () {
			if (!kiezer) {
				kiezer = wp.media({
					title: dkmPanel.kies,
					button: { text: dkmPanel.gebruik },
					multiple: false,
					library: { type: 'image' }
				});
				kiezer.on('select', function () {
					var gekozen = kiezer.state().get('selection').first().toJSON();
					waarde.value = gekozen.id;
					var klein = gekozen.sizes && gekozen.sizes.medium ? gekozen.sizes.medium.url : gekozen.url;
					toon(klein);
				});
			}
			kiezer.open();
		});

		weg.addEventListener('click', function () {
			waarde.value = '';
			toon('');
		});
	}

	/* ------------------------------------------------------------------ */
	/* Lists you can add rows to                                           */
	/* ------------------------------------------------------------------ */

	function hernummer(lijst) {
		$$('[data-dkm-rep-row]', lijst).forEach(function (rij, i) {
			$$('input, textarea, select', rij).forEach(function (veld) {
				if (!veld.name) {
					return;
				}
				veld.name = veld.name.replace(/\[(?:\d+|__i__)\]/, '[' + i + ']');
			});
		});
	}

	function koppelLijst(lijst) {
		var rijen = lijst.querySelector('[data-dkm-rep-rows]');
		var sjabloon = lijst.querySelector('[data-dkm-rep-template]');
		var toevoegen = lijst.querySelector('[data-dkm-rep-add]');

		toevoegen.addEventListener('click', function () {
			var nieuw = sjabloon.content.cloneNode(true);
			rijen.appendChild(nieuw);
			hernummer(lijst);
			var laatste = rijen.lastElementChild;
			var eerste = laatste ? laatste.querySelector('input, textarea, select') : null;
			if (eerste) {
				eerste.focus();
			}
		});

		lijst.addEventListener('click', function (e) {
			var weg = e.target.closest('[data-dkm-rep-remove]');
			if (!weg || !lijst.contains(weg)) {
				return;
			}
			var rij = weg.closest('[data-dkm-rep-row]');
			if (rij) {
				rij.remove();
				hernummer(lijst);
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		$$('[data-dkm-image]').forEach(koppelAfbeelding);
		$$('[data-dkm-rep]').forEach(koppelLijst);
	});
})();
