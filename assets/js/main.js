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

	$$('[data-dkm-count]').forEach(function (el) {
		var target = parseFloat(el.getAttribute('data-dkm-count'));
		var suffix = el.getAttribute('data-dkm-suffix') || '';
		if (isNaN(target)) {
			return;
		}
		if (reduced || !('IntersectionObserver' in window)) {
			el.textContent = target.toLocaleString('nl-NL') + suffix;
			return;
		}
		var seen = false;
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting || seen) {
					return;
				}
				seen = true;
				var start = performance.now();
				var run = function (now) {
					var p = Math.min(1, (now - start) / 1100);
					var eased = 1 - Math.pow(1 - p, 3);
					el.textContent = Math.round(target * eased).toLocaleString('nl-NL') + suffix;
					if (p < 1) {
						requestAnimationFrame(run);
					}
				};
				requestAnimationFrame(run);
				io.unobserve(entry.target);
			});
		});
		io.observe(el);
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

		function go(dir) {
			index = Math.min(cards.length - 1, Math.max(0, index + dir));
			layout();
		}

		on($('[data-dkm-ugc-prev]'), 'click', function () {
			go(-1);
		});
		on($('[data-dkm-ugc-next]'), 'click', function () {
			go(1);
		});

		cards.forEach(function (card, i) {
			on(card, 'click', function () {
				if (i !== index) {
					index = i;
					layout();
					return;
				}
				var video = $('video', card);
				if (!video) {
					return;
				}
				if (video.paused) {
					video.play();
					card.classList.add('is-playing');
				} else {
					video.pause();
					card.classList.remove('is-playing');
				}
			});
		});

		layout();
	})();

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
					if (moreBtn) {
						moreBtn.hidden = state.page >= pages;
					}
					paintCounts(data.facets);
					if (activeEl) {
						var active = state.tier.length + state.model.length + state.maat.length + (state.max ? 1 : 0);
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

		on(moreBtn, 'click', function () {
			if (state.page < pages) {
				state.page += 1;
				load(true);
			}
		});

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
			on(filterToggle, 'click', function () {
				var open = filterPanel.classList.toggle('is-on');
				filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		}

		// The first paint comes from PHP, so only the counts are synced.
		load(false, true);
	});
})();
