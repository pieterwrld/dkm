/**
 * DKM front end
 *
 * Plain JavaScript, no framework and no jQuery. Every panel is driven by a
 * hidden attribute plus an is-on class, so nothing that is closed can take up
 * layout space and stretch the page sideways.
 */
(function () {
	'use strict';

	var cfg = window.DKM || {};
	var i18n = cfg.i18n || {};
	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function $(sel, root) {
		return (root || document).querySelector(sel);
	}

	function $$(sel, root) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	function on(el, type, fn, opts) {
		if (el) {
			el.addEventListener(type, fn, opts);
		}
	}

	/* ====================================================================
	   Panels
	   ==================================================================== */

	var openPanels = [];

	function openPanel(el, opts) {
		if (!el || el.classList.contains('is-on')) {
			return;
		}
		opts = opts || {};
		el.hidden = false;
		// Let the browser apply the pre-transition state before animating.
		requestAnimationFrame(function () {
			el.classList.add('is-on');
		});
		if (opts.lock) {
			document.body.classList.add('dkm-locked');
		}
		if (opts.scrim) {
			showScrim();
		}
		if (opts.toggle) {
			opts.toggle.setAttribute('aria-expanded', 'true');
		}
		el._dkmOpts = opts;
		openPanels.push(el);

		if (opts.focus !== false) {
			var target = el.querySelector('[autofocus], input, button, a[href]');
			if (target) {
				setTimeout(function () {
					target.focus();
				}, 60);
			}
		}
	}

	function closePanel(el) {
		if (!el || !el.classList.contains('is-on')) {
			return;
		}
		var opts = el._dkmOpts || {};
		el.classList.remove('is-on');
		if (opts.toggle) {
			opts.toggle.setAttribute('aria-expanded', 'false');
			if (opts.returnFocus !== false) {
				opts.toggle.focus();
			}
		}
		openPanels = openPanels.filter(function (p) {
			return p !== el;
		});
		if (!openPanels.some(function (p) {
			return (p._dkmOpts || {}).lock;
		})) {
			document.body.classList.remove('dkm-locked');
		}
		if (!openPanels.some(function (p) {
			return (p._dkmOpts || {}).scrim;
		})) {
			hideScrim();
		}

		var done = function () {
			if (!el.classList.contains('is-on')) {
				el.hidden = true;
			}
		};
		if (reduced) {
			done();
		} else {
			setTimeout(done, 420);
		}
	}

	function closeAllPanels() {
		openPanels.slice().forEach(closePanel);
	}

	var scrim = $('#scrim');

	function showScrim() {
		if (!scrim) {
			return;
		}
		scrim.hidden = false;
		requestAnimationFrame(function () {
			scrim.classList.add('is-on');
		});
	}

	function hideScrim() {
		if (!scrim) {
			return;
		}
		scrim.classList.remove('is-on');
		setTimeout(function () {
			if (!scrim.classList.contains('is-on')) {
				scrim.hidden = true;
			}
		}, 380);
	}

	on(scrim, 'click', closeAllPanels);

	/* ---- keyboard inside an open panel ----
	   A panel with a scrim covers the page, so the keyboard has to stay inside
	   it. Without this Tab walks on into the content behind the scrim and the
	   focus ring disappears off screen, which leaves someone on a keyboard
	   stuck with no way back. */

	var FOCUSABLE = [
		'a[href]',
		'button:not([disabled])',
		'input:not([disabled]):not([type="hidden"])',
		'select:not([disabled])',
		'textarea:not([disabled])',
		'[tabindex]:not([tabindex="-1"])'
	].join(',');

	function focusables(el) {
		return $$(FOCUSABLE, el).filter(function (node) {
			// A hidden slide or a collapsed row has no box, so it is skipped.
			return node.offsetWidth || node.offsetHeight || node.getClientRects().length;
		});
	}

	on(document, 'keydown', function (e) {
		if (!openPanels.length) {
			return;
		}
		var panel = openPanels[openPanels.length - 1];

		if (e.key === 'Escape') {
			closePanel(panel);
			return;
		}

		if (e.key !== 'Tab' || !(panel._dkmOpts || {}).trap) {
			return;
		}

		var items = focusables(panel);
		if (!items.length) {
			return;
		}
		var first = items[0];
		var last = items[items.length - 1];

		// Opened with the mouse, so focus is still on the button outside. The
		// first Tab moves into the panel rather than past it.
		if (!panel.contains(document.activeElement)) {
			e.preventDefault();
			(e.shiftKey ? last : first).focus();
			return;
		}
		if (e.shiftKey && document.activeElement === first) {
			e.preventDefault();
			last.focus();
		} else if (!e.shiftKey && document.activeElement === last) {
			e.preventDefault();
			first.focus();
		}
	});

	$$('[data-dkm-close]').forEach(function (btn) {
		on(btn, 'click', function () {
			var which = btn.getAttribute('data-dkm-close');
			var map = {
				cart: '#cart-drawer',
				search: '#search-panel',
				modal: '#dkm-modal',
				mega: '#mega',
				mob: '#mobile-drawer'
			};
			closePanel($(map[which] || which));
		});
	});

	/* ====================================================================
	   Header
	   ==================================================================== */

	var header = $('#hd');
	if (header) {
		var onScroll = function () {
			header.classList.toggle('is-sc', window.scrollY > 10);
		};
		on(window, 'scroll', onScroll, { passive: true });
		onScroll();
	}

	var megaToggle = $('#mega-toggle');
	var mega = $('#mega');
	if (megaToggle && mega) {
		on(megaToggle, 'click', function (e) {
			e.stopPropagation();
			if (mega.classList.contains('is-on')) {
				closePanel(mega);
				return;
			}
			var rect = header.getBoundingClientRect();
			mega.style.setProperty('--mt', rect.bottom + 'px');
			// A click from the keyboard reports no pointer detail, and only then is
			// it right to move focus into the panel.
			openPanel(mega, { scrim: true, trap: true, toggle: megaToggle, focus: 0 === e.detail });
		});

		// A pointer leaving both the button and the panel closes it, which is
		// what a mega menu is expected to do.
		var leaveTimer;
		[megaToggle, mega].forEach(function (el) {
			on(el, 'mouseleave', function () {
				leaveTimer = setTimeout(function () {
					if (!megaToggle.matches(':hover') && !mega.matches(':hover')) {
						closePanel(mega);
					}
				}, 220);
			});
			on(el, 'mouseenter', function () {
				clearTimeout(leaveTimer);
			});
		});
	}

	var burger = $('#burger');
	var drawer = $('#mobile-drawer');
	if (burger && drawer) {
		on(burger, 'click', function () {
			if (drawer.classList.contains('is-on')) {
				closePanel(drawer);
			} else {
				openPanel(drawer, { lock: true, trap: true, toggle: burger });
			}
		});
	}

	var searchToggle = $('#search-toggle');
	var searchPanel = $('#search-panel');
	if (searchToggle && searchPanel) {
		on(searchToggle, 'click', function () {
			if (searchPanel.classList.contains('is-on')) {
				closePanel(searchPanel);
			} else {
				openPanel(searchPanel, { scrim: true, trap: true, toggle: searchToggle });
			}
		});
	}

	/* ====================================================================
	   Toast
	   ==================================================================== */

	var toastEl = $('#dkm-toast');
	var toastTimer;

	function toast(message) {
		if (!toastEl) {
			return;
		}
		toastEl.textContent = message;
		toastEl.classList.add('is-on');
		clearTimeout(toastTimer);
		toastTimer = setTimeout(function () {
			toastEl.classList.remove('is-on');
		}, 2600);
	}

	/* ====================================================================
	   Countdown
	   ==================================================================== */

	$$('[data-dkm-countdown]').forEach(function (el) {
		var ends = parseInt(el.getAttribute('data-dkm-countdown'), 10) * 1000;
		if (!ends) {
			return;
		}
		var pad = function (n) {
			return String(n).padStart(2, '0');
		};
		var tick = function () {
			var left = Math.max(0, ends - Date.now());
			var hours = Math.floor(left / 3600000);
			var mins = Math.floor(left / 60000) % 60;
			var secs = Math.floor(left / 1000) % 60;
			el.innerHTML = '<b>' + pad(hours) + '</b><b>' + pad(mins) + '</b><b>' + pad(secs) + '</b>';
			if (left <= 0) {
				clearInterval(timer);
			}
		};
		tick();
		var timer = setInterval(tick, 1000);
	});


	/* ====================================================================
	   The closing photograph, which opens up as it passes
	   ==================================================================== */

	(function () {
		var grower = $('[data-dkm-grow]');
		if (!grower || reduced) {
			return;
		}

		var ticking = false;

		function paint() {
			ticking = false;
			var box = grower.getBoundingClientRect();
			var reisbaar = box.height - window.innerHeight;
			if (reisbaar <= 0) {
				grower.style.setProperty('--p', '1');
				return;
			}
			// How far the section has travelled through the sticky stage.
			var gedaan = Math.min(1, Math.max(0, -box.top / reisbaar));
			grower.style.setProperty('--p', gedaan.toFixed(4));
		}

		function vraag() {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(paint);
			}
		}

		on(window, 'scroll', vraag, { passive: true });
		on(window, 'resize', vraag);
		paint();
	})();

	/* ====================================================================
	   A row that slides, driven by two buttons
	   ==================================================================== */

	$$('[data-dkm-track]').forEach(function (track) {
		var root = track.parentNode;
		var vorige = $('[data-dkm-track-prev]', root);
		var volgende = $('[data-dkm-track-next]', root);

		function stap(richting) {
			// One screen of the track at a time, so the pair in view is
			// replaced by the next pair.
			track.scrollBy({ left: richting * track.clientWidth, behavior: 'smooth' });
		}

		on(vorige, 'click', function () {
			stap(-1);
		});

		on(volgende, 'click', function () {
			stap(1);
		});
	});
	/* ====================================================================
	   Scroll reveal
	   ==================================================================== */

	if (reduced || !('IntersectionObserver' in window)) {
		$$('.rv').forEach(function (el) {
			el.classList.add('is-in');
		});
	} else {
		var revealer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-in');
						revealer.unobserve(entry.target);
					}
				});
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.05 }
		);
		var observeReveals = function (root) {
			$$('.rv:not(.is-in)', root).forEach(function (el) {
				revealer.observe(el);
			});
		};
		observeReveals(document);
		window.dkmObserveReveals = observeReveals;
	}

	/* ====================================================================
	   Counting numbers
	   ==================================================================== */

	// Each number runs up once, the first time its block comes into view, and
	// lands on the figure the page was built with. It used to restart on every
	// pass, so for a second the page claimed eighteen reviews.
	$$('[data-dkm-count]').forEach(function (el) {
		var target = parseFloat(el.getAttribute('data-dkm-count'));
		var suffix = el.getAttribute('data-dkm-suffix') || '';
		if (isNaN(target)) {
			return;
		}

		var write = function (value) {
			el.textContent = Math.round(value).toLocaleString('nl-NL') + suffix;
		};

		write(target);

		if (reduced || typeof IntersectionObserver === 'undefined') {
			return;
		}

		var done = false;

		var run = function () {
			if (done) {
				return;
			}
			done = true;

			var span = 1500;
			var start = 0;

			var step = function (now) {
				if (!start) {
					start = now;
				}
				var part = Math.min(1, (now - start) / span);
				// Quick at first, then easing into the final figure.
				var eased = 1 - Math.pow(1 - part, 3);
				if (part < 1) {
					write(target * eased);
					window.requestAnimationFrame(step);
					return;
				}
				write(target);
			};

			window.requestAnimationFrame(step);
		};

		var watcher = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}
				watcher.disconnect();
				run();
			});
		}, { threshold: 0.4 });

		watcher.observe(el);
	});
	/* ====================================================================
	   Carousels
	   ==================================================================== */

	$$('[data-dkm-carousel]').forEach(function (wrap) {
		var track = $('[data-dkm-track]', wrap);
		var prev = $('[data-dkm-prev]', wrap);
		var next = $('[data-dkm-next]', wrap);
		var dotsWrap = $('[data-dkm-dots]', wrap);
		if (!track) {
			return;
		}

		var slides = function () {
			return Array.prototype.slice.call(track.children);
		};

		var step = function () {
			var first = slides()[0];
			if (!first) {
				return track.clientWidth;
			}
			var gap = parseFloat(getComputedStyle(track).columnGap || '14') || 14;
			return first.getBoundingClientRect().width + gap;
		};

		var current = function () {
			return Math.round(track.scrollLeft / step());
		};

		if (dotsWrap) {
			dotsWrap.innerHTML = '';
			slides().forEach(function (slide, index) {
				var dot = document.createElement('button');
				dot.type = 'button';
				dot.setAttribute('aria-label', 'Ga naar item ' + (index + 1));
				on(dot, 'click', function () {
					track.scrollTo({ left: index * step(), behavior: reduced ? 'auto' : 'smooth' });
				});
				dotsWrap.appendChild(dot);
			});
		}

		var sync = function () {
			var index = current();
			if (dotsWrap) {
				Array.prototype.forEach.call(dotsWrap.children, function (dot, i) {
					dot.classList.toggle('is-on', i === index);
				});
			}
			if (prev) {
				prev.disabled = track.scrollLeft < 4;
			}
			if (next) {
				next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
			}
		};

		var go = function (dir) {
			track.scrollBy({ left: dir * step(), behavior: reduced ? 'auto' : 'smooth' });
		};

		on(prev, 'click', function () {
			go(-1);
		});
		on(next, 'click', function () {
			go(1);
		});
		on(track, 'scroll', function () {
			window.requestAnimationFrame(sync);
		}, { passive: true });
		on(window, 'resize', sync);

		// Featured slide first, where the markup asks for it.
		var featured = $('[data-dkm-featured]', track);
		if (featured) {
			track.scrollLeft = slides().indexOf(featured) * step();
		}
		sync();
	});

	/* ====================================================================
	   Accordions
	   ==================================================================== */

	$$('[data-dkm-accordion]').forEach(function (acc) {
		$$('.acc__h', acc).forEach(function (head) {
			var item = head.closest('.acc__i');
			head.setAttribute('aria-expanded', item.classList.contains('is-on') ? 'true' : 'false');
			on(head, 'click', function () {
				var open = item.classList.toggle('is-on');
				head.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		});
	});

	/* ====================================================================
	   Tooltips behind the i
	   ==================================================================== */

	$$('.inf').forEach(function (inf) {
		inf.setAttribute('aria-expanded', 'false');
		on(inf, 'click', function (e) {
			e.stopPropagation();
			var open = inf.getAttribute('aria-expanded') === 'true';
			$$('.inf').forEach(function (other) {
				other.setAttribute('aria-expanded', 'false');
			});
			inf.setAttribute('aria-expanded', open ? 'false' : 'true');
		});
	});

	on(document, 'click', function () {
		$$('.inf[aria-expanded="true"]').forEach(function (inf) {
			inf.setAttribute('aria-expanded', 'false');
		});
	});

	/* ====================================================================
	   Modal
	   ==================================================================== */

	var modal = $('#dkm-modal');
	var modalHead = $('#dkm-modal-head');
	var modalBody = $('#dkm-modal-body');

	function openModal(headHtml, bodyHtml, toggle) {
		if (!modal) {
			return;
		}
		if (modalHead) {
			modalHead.innerHTML = headHtml || '';
		}
		if (modalBody) {
			modalBody.innerHTML = bodyHtml || '';
		}
		openPanel(modal, { lock: true, trap: true, toggle: toggle });
	}

	$$('[data-dkm-modal]').forEach(function (trigger) {
		on(trigger, 'click', function (e) {
			e.preventDefault();
			var source = $('#' + trigger.getAttribute('data-dkm-modal'));
			if (!source) {
				return;
			}
			openModal(
				source.getAttribute('data-modal-head') || '',
				source.innerHTML,
				trigger
			);
		});
	});

	/* ====================================================================
	   Anatomy hotspots
	   ==================================================================== */

	(function () {
		var section = $('[data-dkm-anatomy]');
		if (!section) {
			return;
		}
		var spots = $$('[data-dkm-spot]', section);
		var items = $$('[data-dkm-spot-item]', section);

		function select(index) {
			spots.forEach(function (spot) {
				var active = spot.getAttribute('data-dkm-spot') === String(index);
				spot.classList.toggle('is-on', active);
				spot.setAttribute('aria-expanded', active ? 'true' : 'false');
			});
			items.forEach(function (item) {
				var active = item.getAttribute('data-dkm-spot-item') === String(index);
				item.classList.toggle('is-on', active);
				var head = $('.ana__t', item);
				if (head) {
					head.setAttribute('aria-expanded', active ? 'true' : 'false');
				}
			});
		}

		spots.concat(items).forEach(function (el) {
			var index = el.getAttribute('data-dkm-spot') || el.getAttribute('data-dkm-spot-item');
			var handler = function () {
				select(index);
			};
			var clickable = el.matches('button') ? el : $('.ana__t', el) || el;
			on(clickable, 'click', handler);
		});

		select('0');
	})();

	/* ====================================================================
	   UGC carousel: cards fan out in 3D
	   ==================================================================== */

	(function () {
		var stage = $('[data-dkm-ugc]');
		if (!stage) {
			return;
		}
		var cards = $$('[data-dkm-ugc-card]', stage);
		if (!cards.length) {
			return;
		}
		var label = $('[data-dkm-ugc-label]');
		var index = 0;

		function video(card) {
			return $('video', card);
		}

		// Only one clip may ever be heard, so every other one is stopped and
		// put back on silent, button and all. Two clips talking over each
		// other was the thing that made this section unusable.
		function stil(behalve) {
			cards.forEach(function (card) {
				if (card === behalve) {
					return;
				}
				var clip = video(card);
				if (clip) {
					clip.pause();
					clip.muted = true;
				}
				card.classList.remove('is-playing');
				var knop = $('[data-dkm-ugc-sound]', card);
				if (knop) {
					knop.classList.remove('is-on');
					knop.setAttribute('aria-pressed', 'false');
					knop.setAttribute('aria-label', i18n.soundOn || 'Geluid aanzetten');
				}
			});
		}

		function speel(card) {
			stil(card);
			var clip = video(card);
			if (!clip) {
				return;
			}
			var bezig = clip.play();
			if (bezig && bezig.catch) {
				bezig.catch(function () {
					card.classList.remove('is-playing');
				});
			}
			card.classList.add('is-playing');
		}

		function layout() {
			var aantal = cards.length;
			cards.forEach(function (card, i) {
				// The stack is a ring, so a card far ahead is really just
				// behind. Without this the fan only ever opens to one side.
				var offset = i - index;
				if (offset > aantal / 2) {
					offset -= aantal;
				}
				if (offset < -aantal / 2) {
					offset += aantal;
				}
				var abs = Math.abs(offset);
				var scale = Math.max(0.6, 1 - abs * 0.14);
				var x = offset * 56;
				var z = -abs * 160;
				var rotate = offset * -16;
				card.style.transform =
					'translate(-50%,-50%) translateX(' + x + '%) translateZ(' + z + 'px) rotateY(' + rotate + 'deg) scale(' + scale + ')';
				card.style.opacity = abs > 3 ? '0' : String(Math.max(0.25, 1 - abs * 0.26));
				card.style.filter = abs === 0 ? 'none' : 'brightness(' + Math.max(0.45, 1 - abs * 0.2) + ')';
				card.style.zIndex = String(50 - abs);
				card.setAttribute('aria-hidden', abs === 0 ? 'false' : 'true');
				card.tabIndex = abs === 0 ? 0 : -1;
			});
			if (label) {
				label.textContent = cards[index].getAttribute('data-dkm-ugc-caption') || '';
			}
		}

		// Moving on always leaves silence behind, whichever way you got there.
		function naar(doel) {
			var aantal = cards.length;
			index = ((doel % aantal) + aantal) % aantal;
			stil(null);
			layout();
		}

		on($('[data-dkm-ugc-prev]'), 'click', function () {
			naar(index - 1);
		});
		on($('[data-dkm-ugc-next]'), 'click', function () {
			naar(index + 1);
		});

		/* ---- Swiping ---- */

		var vanX = 0;
		var vanY = 0;
		var raakt = false;
		var sleept = false;
		var negeerKlik = false;

		on(stage, 'pointerdown', function (e) {
			if ('mouse' === e.pointerType && 0 !== e.button) {
				return;
			}
			raakt = true;
			sleept = false;
			vanX = e.clientX;
			vanY = e.clientY;
		});

		on(stage, 'pointermove', function (e) {
			if (!raakt || sleept) {
				return;
			}
			var dx = e.clientX - vanX;
			var dy = e.clientY - vanY;
			// Sideways beats up and down, otherwise the page may not scroll.
			if (Math.abs(dx) > 12 && Math.abs(dx) > Math.abs(dy)) {
				sleept = true;
			}
		});

		function los(e) {
			if (!raakt) {
				return;
			}
			raakt = false;
			if (!sleept) {
				return;
			}
			sleept = false;
			// The tap that follows a swipe must not also press play.
			negeerKlik = true;
			var dx = e.clientX - vanX;
			if (Math.abs(dx) >= 40) {
				naar(index + (dx < 0 ? 1 : -1));
			}
		}

		on(stage, 'pointerup', los);
		on(stage, 'pointercancel', function () {
			raakt = false;
			sleept = false;
		});

		on(stage, 'click', function (e) {
			if (!negeerKlik) {
				return;
			}
			negeerKlik = false;
			e.preventDefault();
			e.stopPropagation();
		}, true);

		/* ---- The two buttons on a card ---- */

		cards.forEach(function (card, i) {
			on($('[data-dkm-ugc-play]', card), 'click', function () {
				if (i !== index) {
					naar(i);
					return;
				}
				var clip = video(card);
				if (!clip) {
					return;
				}
				if (clip.paused) {
					speel(card);
				} else {
					clip.pause();
					card.classList.remove('is-playing');
				}
			});

			// The clips start silent, because a browser will not play a clip
			// with sound before someone asks for it. This button is the ask,
			// and it shows which of the two states it is in.
			on($('[data-dkm-ugc-sound]', card), 'click', function (e) {
				e.stopPropagation();
				var clip = video(card);
				if (!clip) {
					return;
				}
				var aanzetten = clip.muted;

				if (aanzetten) {
					if (clip.paused) {
						speel(card);
					} else {
						stil(card);
					}
					clip.muted = false;
				} else {
					clip.muted = true;
				}

				this.classList.toggle('is-on', aanzetten);
				this.setAttribute('aria-pressed', aanzetten ? 'true' : 'false');
				this.setAttribute(
					'aria-label',
					aanzetten ? (i18n.soundOff || 'Geluid uitzetten') : (i18n.soundOn || 'Geluid aanzetten')
				);
			});
		});

		layout();
	})();

	/* ====================================================================
	   A row of photos you can swipe through
	   ==================================================================== */

	// Used on the product page, where it carries arrows and thumbnails, and on
	// every card in the collection, where it is only the photos and the line
	// underneath. The track does the scrolling, so a finger behaves as it does
	// everywhere else and nothing has to be dragged by hand.

	function bindShots(scope) {
		$$('[data-dkm-shots]', scope || document).forEach(function (root) {
		if (root.dataset.dkmShotsOn) {
			return;
		}
		root.dataset.dkmShotsOn = '1';

		var track = $('[data-dkm-shots-track]', root);
		if (!track) {
			return;
		}

		var fotos = $$('img', track);
		if (fotos.length < 2) {
			return;
		}

		var streepjes = $$('.shots__bar i', root);
		var vorige = $('[data-dkm-shots-prev]', root);
		var volgende = $('[data-dkm-shots-next]', root);
		var duimen = $$('[data-dkm-shots-go]', root.parentNode || root);
		var nu = 0;
		var wacht = false;

		function breedte() {
			return track.clientWidth || 1;
		}

		function teken() {
			var i = Math.round(track.scrollLeft / breedte());
			i = Math.max(0, Math.min(fotos.length - 1, i));
			if (i === nu) {
				return;
			}
			nu = i;
			streepjes.forEach(function (streep, n) {
				streep.classList.toggle('is-on', n === i);
			});
			duimen.forEach(function (duim, n) {
				duim.classList.toggle('is-on', n === i);
			});
			if (vorige) {
				vorige.disabled = 0 === i;
			}
			if (volgende) {
				volgende.disabled = i === fotos.length - 1;
			}
		}

		function naar(i) {
			i = Math.max(0, Math.min(fotos.length - 1, i));
			track.scrollTo({ left: i * breedte(), behavior: reduced ? 'auto' : 'smooth' });
		}

		on(track, 'scroll', function () {
			if (wacht) {
				return;
			}
			wacht = true;
			window.requestAnimationFrame(function () {
				wacht = false;
				teken();
			});
		}, { passive: true });

		on(vorige, 'click', function (e) {
			e.preventDefault();
			naar(nu - 1);
		});

		on(volgende, 'click', function (e) {
			e.preventDefault();
			naar(nu + 1);
		});

		duimen.forEach(function (duim) {
			on(duim, 'click', function (e) {
				e.preventDefault();
				naar(parseInt(duim.getAttribute('data-dkm-shots-go'), 10) || 0);
			});
		});

		// The arrow keys move the photos when the track itself has focus.
		on(track, 'keydown', function (e) {
			if ('ArrowRight' === e.key) {
				e.preventDefault();
				naar(nu + 1);
			}
			if ('ArrowLeft' === e.key) {
				e.preventDefault();
				naar(nu - 1);
			}
		});

		if (vorige) {
			vorige.disabled = true;
		}
		});
	}

	bindShots(document);
	window.dkmBindShots = bindShots;

	/* ====================================================================
	   Cart
	   ==================================================================== */

	var cartDrawer = $('#cart-drawer');
	var cartToggle = $('#cart-toggle');

	if (cartToggle && cartDrawer) {
		on(cartToggle, 'click', function () {
			if (cartDrawer.classList.contains('is-on')) {
				closePanel(cartDrawer);
			} else {
				openPanel(cartDrawer, { lock: true, scrim: true, trap: true, toggle: cartToggle });
			}
		});
	}

	function cartRequest(payload, button) {
		if (!cfg.ajaxUrl) {
			return Promise.reject(new Error('no endpoint'));
		}
		var body = new FormData();
		body.append('action', 'dkm_cart');
		body.append('nonce', cfg.ajaxNonce || '');
		Object.keys(payload).forEach(function (key) {
			body.append(key, payload[key]);
		});

		if (button) {
			button.classList.add('is-busy');
		}

		return fetch(cfg.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: body
		})
			.then(function (res) {
				return res.json();
			})
			.then(function (json) {
				if (!json || !json.success) {
					var message = (json && json.data && json.data.message) || i18n.error;
					throw new Error(message);
				}
				applyCart(json.data);
				return json.data;
			})
			.catch(function (err) {
				toast(err.message || i18n.error);
				throw err;
			})
			.finally(function () {
				if (button) {
					button.classList.remove('is-busy');
				}
			});
	}

	function applyCart(data) {
		var count = $('[data-dkm-cart-count]');
		if (count) {
			count.textContent = data.count;
			count.classList.add('is-bump');
			setTimeout(function () {
				count.classList.remove('is-bump');
			}, 320);
		}
		var total = $('[data-dkm-cart-total]');
		if (total && data.total) {
			total.textContent = data.total;
		}
		var body = $('[data-dkm-cart-body]');
		if (body && typeof data.body === 'string') {
			body.innerHTML = data.body;
			bindCartBody();
		}
	}

	function bindCartBody() {
		$$('[data-dkm-cart-remove]').forEach(function (btn) {
			on(btn, 'click', function () {
				cartRequest({ task: 'remove', key: btn.getAttribute('data-dkm-cart-remove') }, btn);
			});
		});
		$$('[data-dkm-add-box]').forEach(function (btn) {
			on(btn, 'click', function () {
				cartRequest({ task: 'add_box', box_id: btn.getAttribute('data-dkm-add-box') }, btn).then(function () {
					toast(i18n.added || '');
				});
			});
		});
	}

	bindCartBody();

	$$('[data-dkm-add-to-cart]').forEach(function (form) {
		on(form, 'submit', function (e) {
			e.preventDefault();
			var button = $('[type="submit"]', form);
			var qtyField = $('[name="quantity"]', form);
			var boxField = $('[name="dkm_box"]:checked', form);
			cartRequest(
				{
					task: 'add',
					product_id: form.getAttribute('data-dkm-add-to-cart'),
					quantity: qtyField ? qtyField.value : 1,
					box_id: boxField ? boxField.value : 0
				},
				button
			).then(function () {
				toast(i18n.added || '');
				if (cartDrawer) {
					openPanel(cartDrawer, { lock: true, scrim: true, trap: true, toggle: cartToggle });
				}
			});
		});
	});

	/* ====================================================================
	   Quantity steppers
	   ==================================================================== */

	$$('[data-dkm-qty]').forEach(function (wrap) {
		var input = $('input', wrap);
		if (!input) {
			return;
		}
		$$('button', wrap).forEach(function (btn) {
			on(btn, 'click', function () {
				var dir = btn.getAttribute('data-dir') === 'up' ? 1 : -1;
				var min = parseInt(input.min || '1', 10);
				var max = input.max ? parseInt(input.max, 10) : Infinity;
				var next = Math.min(max, Math.max(min, (parseInt(input.value, 10) || min) + dir));
				input.value = next;
				input.dispatchEvent(new Event('change', { bubbles: true }));
			});
		});
	});

	/* ====================================================================
	   Box upsell on the product page
	   ==================================================================== */

	(function () {
		var ups = $('[data-dkm-upsell]');
		if (!ups) {
			return;
		}
		var base = parseFloat(ups.getAttribute('data-dkm-base')) || 0;
		var totalEl = $('[data-dkm-upsell-total]');
		var hint = $('[data-dkm-upsell-hint]');

		function money(value) {
			var whole = Math.abs(value - Math.round(value)) < 0.005;
			return '€' + value.toLocaleString('nl-NL', {
				minimumFractionDigits: whole ? 0 : 2,
				maximumFractionDigits: whole ? 0 : 2
			});
		}

		function sync() {
			var checked = $('[name="dkm_box"]:checked', ups);
			var extra = checked ? parseFloat(checked.getAttribute('data-price')) || 0 : 0;
			if (totalEl) {
				totalEl.textContent = money(base + extra);
			}
			if (hint) {
				hint.textContent = checked ? checked.getAttribute('data-label') : hint.getAttribute('data-default') || '';
			}
			$$('.uopt', ups).forEach(function (opt) {
				var input = $('input', opt);
				opt.classList.toggle('is-on', !!(input && input.checked));
			});
		}

		$$('[name="dkm_box"]', ups).forEach(function (input) {
			on(input, 'change', sync);
		});

		// A label with a button inside it needs two taps on a phone: the first
		// one only moves focus. Picking the row by hand makes one tap enough.
		$$('.uopt', ups).forEach(function (opt) {
			on(opt, 'click', function (e) {
				if (e.target.closest && e.target.closest('[data-dkm-box-preview]')) {
					return;
				}
				var input = $('input[name="dkm_box"]', opt);
				if (!input || input.checked) {
					return;
				}
				input.checked = true;
				sync();
			});
		});

		$$('[data-dkm-box-preview]', ups).forEach(function (btn) {
			on(btn, 'click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var source = $('#' + btn.getAttribute('data-dkm-box-preview'));
				if (source) {
					openModal(source.getAttribute('data-modal-head') || '', source.innerHTML, btn);
				}
			});
		});

		sync();
	})();

	/* ====================================================================
	   Faceted filtering
	   ==================================================================== */

	$$('[data-dkm-facets]').forEach(function (root) {
		var grid = $('[data-dkm-grid]', root) || $(root.getAttribute('data-dkm-grid-target') || '#dkm-grid');
		if (!grid) {
			return;
		}

		var countEl = $('[data-dkm-count-total]', root);
		var moreBtn = $('[data-dkm-more]', root);
		var endEl = $('[data-dkm-end]', root);
		var busyEl = $('[data-dkm-more-busy]', root);
		// The next page comes in by itself as you reach the end. The button
		// stays for anyone whose browser cannot watch the page scroll.
		var vanzelf = typeof IntersectionObserver !== 'undefined' && !!endEl;
		var sortEl = $('[data-dkm-sort]', root);
		var priceEl = $('[data-dkm-price]', root);
		var priceOut = $('[data-dkm-price-value]', root);
		var activeEl = $('[data-dkm-active-count]', root);

		var state = {
			cat: root.getAttribute('data-dkm-cat') || '',
			tier: [],
			model: [],
			maat: [],
			max: 0,
			sort: 'rec',
			page: 1,
			per_page: parseInt(root.getAttribute('data-dkm-per-page'), 10) || 24
		};

		var pages = 1;
		var busy = false;

		function selected(group) {
			return $$('[data-dkm-facet="' + group + '"].is-on', root).map(function (btn) {
				return btn.getAttribute('data-value');
			});
		}

		function readState() {
			state.tier = selected('tier');
			state.model = selected('model');
			state.maat = selected('maat');
			state.max = priceEl ? parseInt(priceEl.value, 10) || 0 : 0;
			state.sort = sortEl ? sortEl.value : 'rec';
		}

		function query() {
			var params = new URLSearchParams();
			params.set('cat', state.cat);
			['tier', 'model', 'maat'].forEach(function (key) {
				if (state[key].length) {
					params.set(key, state[key].join(','));
				}
			});
			if (state.max) {
				params.set('max', state.max);
			}
			params.set('sort', state.sort);
			params.set('page', state.page);
			params.set('per_page', state.per_page);
			return params.toString();
		}

		function paintCounts(facets) {
			if (!facets) {
				return;
			}
			Object.keys(facets).forEach(function (group) {
				facets[group].forEach(function (option) {
					var btn = $('[data-dkm-facet="' + group + '"][data-value="' + option.slug + '"]', root);
					if (!btn) {
						return;
					}
					var badge = $('b', btn);
					if (badge) {
						badge.textContent = option.count;
					}
					btn.disabled = option.count === 0 && !btn.classList.contains('is-on');
				});
			});
		}

		function load(append, countsOnly) {
			if (busy) {
				return;
			}
			busy = true;
			readState();
			if (!append) {
				state.page = 1;
			}
			grid.setAttribute('aria-busy', 'true');

			fetch(cfg.restUrl + 'products?' + query(), {
				credentials: 'same-origin',
				headers: { 'X-WP-Nonce': cfg.nonce || '' }
			})
				.then(function (res) {
					return res.json();
				})
				.then(function (data) {
					// The first pass only syncs the counts, because PHP has
					// already rendered this exact grid.
					if (!countsOnly) {
						if (append) {
							grid.insertAdjacentHTML('beforeend', data.html);
						} else {
							grid.innerHTML = data.html || '<p class="empty">' + (i18n.noResults || '') + '</p>';
						}
						if (window.dkmObserveReveals) {
							window.dkmObserveReveals(grid);
						} else {
							$$('.rv', grid).forEach(function (el) {
								el.classList.add('is-in');
							});
						}
					}
					pages = data.pages || 1;
					if (countEl) {
						countEl.textContent = data.total;
					}
					var meer = state.page < pages;
					if (moreBtn) {
						moreBtn.hidden = vanzelf || !meer;
					}
					if (busyEl) {
						busyEl.hidden = !vanzelf || !meer;
					}
					if (window.dkmBindShots) {
						window.dkmBindShots(grid);
					}
					paintCounts(data.facets);
					if (activeEl) {
						// The price slider sits at its maximum until someone
						// moves it, so a slider at the top is not a filter and
						// should not light up the badge.
						var prijsGezet = priceEl && state.max > 0 &&
							state.max < (parseInt(priceEl.max, 10) || 0);
						var active = state.tier.length + state.model.length +
							state.maat.length + (prijsGezet ? 1 : 0);
						activeEl.textContent = active;
						activeEl.hidden = active === 0;
					}
				})
				.catch(function () {
					toast(i18n.error || '');
				})
				.finally(function () {
					busy = false;
					grid.removeAttribute('aria-busy');
				});
		}

		// Each group has one button that stands for "no choice made". It lights
		// up when nothing in its group is picked, and pressing it lets go of
		// whatever was picked there.
		function paintAllButtons() {
			$$('[data-dkm-facet-all]', root).forEach(function (btn) {
				var group = btn.getAttribute('data-dkm-facet-all');
				var gekozen = $$('[data-dkm-facet="' + group + '"].is-on', root).length;
				btn.classList.toggle('is-on', gekozen === 0);
				btn.setAttribute('aria-pressed', gekozen === 0 ? 'true' : 'false');
			});
		}

		$$('[data-dkm-facet]', root).forEach(function (btn) {
			on(btn, 'click', function () {
				btn.classList.toggle('is-on');
				paintAllButtons();
				load(false);
			});
		});

		$$('[data-dkm-facet-all]', root).forEach(function (btn) {
			on(btn, 'click', function () {
				var group = btn.getAttribute('data-dkm-facet-all');
				$$('[data-dkm-facet="' + group + '"].is-on', root).forEach(function (other) {
					other.classList.toggle('is-on', false);
				});
				paintAllButtons();
				load(false);
			});
		});

		paintAllButtons();

		on(sortEl, 'change', function () {
			load(false);
		});

		if (priceEl) {
			var priceLabel = function () {
				if (priceOut) {
					priceOut.textContent = '€' + (parseInt(priceEl.value, 10) || 0).toLocaleString('nl-NL');
				}
			};
			on(priceEl, 'input', priceLabel);
			on(priceEl, 'change', function () {
				load(false);
			});
			priceLabel();
		}

		function volgendePagina() {
			if (busy || state.page >= pages) {
				return;
			}
			state.page += 1;
			load(true);
		}

		on(moreBtn, 'click', volgendePagina);

		if (vanzelf) {
			moreBtn.hidden = true;
			var kijker = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						volgendePagina();
					}
				});
			}, { rootMargin: '600px 0px' });
			kijker.observe(endEl);
		}

		on($('[data-dkm-clear]', root), 'click', function () {
			$$('[data-dkm-facet].is-on', root).forEach(function (btn) {
				btn.classList.toggle('is-on', false);
			});
			paintAllButtons();
			if (priceEl) {
				priceEl.value = priceEl.max;
			}
			load(false);
		});

		var filterToggle = $('[data-dkm-filter-toggle]', root);
		var filterPanel = $('[data-dkm-filter-panel]', root);
		if (filterToggle && filterPanel) {
			var vanafY = 0;

			var sluit = function () {
				if (!filterPanel.classList.contains('is-on')) {
					return;
				}
				filterPanel.classList.remove('is-on');
				filterToggle.setAttribute('aria-expanded', 'false');
			};

			on(filterToggle, 'click', function () {
				var open = filterPanel.classList.toggle('is-on');
				filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				vanafY = window.scrollY || window.pageYOffset || 0;
			});

			// The bar it sits in is pinned to the top, so an open panel kept
			// riding along over the watches. Scrolling away closes it.
			on(window, 'scroll', function () {
				if (!filterPanel.classList.contains('is-on')) {
					return;
				}
				var nu = window.scrollY || window.pageYOffset || 0;
				if (Math.abs(nu - vanafY) > 40) {
					sluit();
				}
			}, { passive: true });

			on(document, 'keydown', function (e) {
				if ('Escape' === e.key) {
					sluit();
				}
			});

			on(document, 'click', function (e) {
				if (!filterPanel.classList.contains('is-on')) {
					return;
				}
				if (filterPanel.contains(e.target) || filterToggle.contains(e.target)) {
					return;
				}
				sluit();
			});
		}

		// The first paint comes from PHP, so only the counts are synced.
		load(false, true);
	});
})();
