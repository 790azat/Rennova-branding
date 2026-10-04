/*
 * Styled dropdowns. Every native <select> on the page gets a custom trigger and
 * option list in the Rennova style. The native select stays in the DOM (hidden)
 * and remains the source of truth, so forms, wire:model and wire:change keep
 * working: picking an option sets the select's value and fires input/change.
 *
 * The trigger is inserted right after the select as <rn-select>. Livewire morphs
 * would remove it (it is not in the server HTML), so the morph hooks below keep
 * it and re-apply the hiding attributes the morph strips from the select.
 * Add data-native to a select to keep the browser's own control.
 */
(() => {
    const TAG = 'rn-select';
    const CHEVRON = '<svg class="sel-ic" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3.5 6l4.5 4.5L12.5 6"/></svg>';
    let uid = 0;
    let current = null; // { sel, btn, list, active }
    let typed = '', typedAt = 0;

    const skip = (sel) => sel.multiple || sel.size > 1 || sel.hasAttribute('data-native');
    const options = (sel) => Array.from(sel.options);
    const labelText = (sel) => {
        const prev = sel.previousElementSibling;
        const label = (sel.labels && sel.labels[0]) || (prev && prev.tagName === 'LABEL' ? prev : null);
        if (!label) return sel.getAttribute('aria-label') || '';
        const copy = label.cloneNode(true);
        copy.querySelectorAll('select, ' + TAG).forEach((n) => n.remove());
        return copy.textContent.replace(/\s+/g, ' ').trim();
    };

    function hideNative(sel) {
        sel.classList.add('sel-native');
        sel.tabIndex = -1;
        sel.setAttribute('aria-hidden', 'true');
    }

    function enhance(sel) {
        if (skip(sel)) return;
        let ui = sel._rnUi;
        if (!ui) {
            ui = document.createElement(TAG);
            ui.innerHTML = '<button type="button" aria-haspopup="listbox" aria-expanded="false"><span class="sel-val"></span>' + CHEVRON + '</button>';
            ui._rnSel = sel;
            sel._rnUi = ui;
            const btn = ui.firstElementChild;
            btn.addEventListener('click', () => (current && current.sel === sel ? close() : open(sel)));
            btn.addEventListener('keydown', (e) => onKey(e, sel));
            sel.addEventListener('focus', () => btn.focus());
            sel.addEventListener('change', () => sync(sel));
        }
        if (ui.previousElementSibling !== sel || !ui.isConnected) sel.after(ui);
        sync(sel);
    }

    function sync(sel) {
        const ui = sel._rnUi;
        if (!ui) return;
        hideNative(sel);
        const btn = ui.firstElementChild;
        const opt = sel.options[sel.selectedIndex];
        const classes = Array.from(sel.classList).filter((c) => c !== 'sel-native');
        btn.className = ['sel-btn'].concat(classes).join(' ');
        btn.classList.toggle('is-empty', !opt || opt.value === '');
        btn.disabled = sel.disabled;
        btn.querySelector('.sel-val').textContent = opt ? opt.textContent.trim() : '';
        const label = labelText(sel);
        if (label) btn.setAttribute('aria-label', label + ': ' + (opt ? opt.textContent.trim() : ''));
        if (current && current.sel === sel) render();
    }

    function open(sel) {
        close();
        const btn = sel._rnUi.firstElementChild;
        if (btn.disabled) return;
        const list = document.createElement('div');
        list.className = 'sel-list';
        list.id = 'sel-list-' + (++uid);
        list.setAttribute('role', 'listbox');
        list.addEventListener('mousedown', (e) => e.preventDefault());
        document.body.appendChild(list);
        current = { sel, btn, list, active: Math.max(sel.selectedIndex, 0) };
        btn.setAttribute('aria-expanded', 'true');
        btn.setAttribute('aria-controls', list.id);
        render();
        place();
        scrollToActive();
    }

    function close() {
        if (!current) return;
        current.list.remove();
        current.btn.setAttribute('aria-expanded', 'false');
        current.btn.removeAttribute('aria-activedescendant');
        current = null;
    }

    function render() {
        const { sel, list, btn } = current;
        list.innerHTML = '';
        let group = null;
        options(sel).forEach((opt, i) => {
            if (opt.hidden) return;
            const parent = opt.parentElement;
            if (parent.tagName === 'OPTGROUP' && parent !== group) {
                group = parent;
                const head = document.createElement('div');
                head.className = 'sel-group';
                head.textContent = parent.label;
                list.appendChild(head);
            }
            const item = document.createElement('div');
            item.className = 'sel-opt';
            item.id = list.id + '-' + i;
            item.setAttribute('role', 'option');
            item.setAttribute('aria-selected', String(i === sel.selectedIndex));
            item.textContent = opt.textContent.trim();
            if (opt.disabled) {
                item.classList.add('is-disabled');
                item.setAttribute('aria-disabled', 'true');
            } else {
                item.addEventListener('mousemove', () => setActive(i, false));
                item.addEventListener('click', () => choose(i));
            }
            if (opt.value === '') item.classList.add('is-placeholder');
            list.appendChild(item);
        });
        setActive(current.active, false);
        btn.focus({ preventScroll: true });
    }

    function place() {
        if (!current) return;
        const { btn, list } = current;
        const r = btn.getBoundingClientRect();
        const below = window.innerHeight - r.bottom - 12;
        const above = r.top - 12;
        list.style.minWidth = r.width + 'px';
        list.style.left = Math.max(8, Math.min(r.left, window.innerWidth - list.offsetWidth - 8)) + 'px';
        const up = below < Math.min(list.scrollHeight, 220) && above > below;
        list.style.maxHeight = Math.min(320, up ? above : below) + 'px';
        list.style.top = (up ? r.top - list.offsetHeight - 4 : r.bottom + 4) + 'px';
        list.classList.toggle('is-up', up);
    }

    function setActive(i, scroll = true) {
        if (!current) return;
        current.active = i;
        current.list.querySelectorAll('.sel-opt.is-active').forEach((n) => n.classList.remove('is-active'));
        const item = document.getElementById(current.list.id + '-' + i);
        if (item) {
            item.classList.add('is-active');
            current.btn.setAttribute('aria-activedescendant', item.id);
            if (scroll) scrollToActive();
        }
    }

    function scrollToActive() {
        const item = current && document.getElementById(current.list.id + '-' + current.active);
        if (item) item.scrollIntoView({ block: 'nearest' });
    }

    function choose(i) {
        const sel = current ? current.sel : null;
        if (!sel) return;
        const btn = current.btn;
        close();
        btn.focus();
        if (sel.selectedIndex === i) return;
        sel.selectedIndex = i;
        sel.dispatchEvent(new Event('input', { bubbles: true }));
        sel.dispatchEvent(new Event('change', { bubbles: true }));
        sync(sel);
    }

    function step(sel, from, dir) {
        const opts = options(sel);
        for (let i = from + dir; i >= 0 && i < opts.length; i += dir) {
            if (!opts[i].disabled && !opts[i].hidden) return i;
        }
        return from;
    }

    function typeahead(sel, key) {
        const now = Date.now();
        typed = now - typedAt > 600 ? key : typed + key;
        typedAt = now;
        const opts = options(sel);
        const start = current ? current.active : sel.selectedIndex;
        for (let n = 1; n <= opts.length; n++) {
            const i = (start + (typed.length > 1 ? n - 1 : n) + opts.length) % opts.length;
            if (!opts[i].disabled && opts[i].textContent.trim().toLowerCase().startsWith(typed.toLowerCase())) return i;
        }
        return -1;
    }

    function onKey(e, sel) {
        const isOpen = current && current.sel === sel;
        const k = e.key;
        if (!isOpen) {
            if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(k)) {
                e.preventDefault();
                open(sel);
            } else if (k.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                const i = typeahead(sel, k);
                if (i >= 0) {
                    open(sel);
                    setActive(i);
                }
            }
            return;
        }
        const a = current.active;
        if (k === 'ArrowDown') setActive(step(sel, a, 1));
        else if (k === 'ArrowUp') setActive(step(sel, a, -1));
        else if (k === 'Home') setActive(step(sel, -1, 1));
        else if (k === 'End') setActive(step(sel, sel.options.length, -1));
        else if (k === 'PageDown') setActive(Math.min(step(sel, a + 7, 1), sel.options.length - 1));
        else if (k === 'PageUp') setActive(Math.max(step(sel, a - 7, -1), 0));
        else if (k === 'Enter' || k === ' ') { if (k === ' ' && Date.now() - typedAt < 600) { const i = typeahead(sel, k); if (i >= 0) setActive(i); } else choose(a); }
        else if (k === 'Escape') { close(); }
        else if (k === 'Tab') { close(); return; }
        else if (k.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) { const i = typeahead(sel, k); if (i >= 0) setActive(i); }
        else return;
        e.preventDefault();
    }

    function enhanceAll(root = document) {
        root.querySelectorAll('select').forEach(enhance);
        document.querySelectorAll(TAG).forEach((ui) => {
            if (!ui._rnSel || !ui._rnSel.isConnected) ui.remove();
        });
    }

    let queued = false;
    function queue() {
        if (queued) return;
        queued = true;
        requestAnimationFrame(() => {
            queued = false;
            enhanceAll();
        });
    }

    document.addEventListener('mousedown', (e) => {
        if (current && !current.list.contains(e.target) && !current.btn.contains(e.target)) close();
    });
    // Follow the trigger when the page scrolls; close once it leaves the screen.
    const follow = (e) => {
        if (!current || (e && e.target === current.list)) return;
        const r = current.btn.getBoundingClientRect();
        if (r.bottom < 0 || r.top > window.innerHeight) close();
        else place();
    };
    window.addEventListener('scroll', follow, true);
    window.addEventListener('resize', () => follow());
    document.addEventListener('livewire:navigating', close);
    document.addEventListener('livewire:navigated', () => enhanceAll());

    new MutationObserver((records) => {
        for (const r of records) {
            for (const n of r.addedNodes) {
                if (n.nodeType === 1 && (n.tagName === 'SELECT' || n.tagName === 'OPTION' || n.querySelector?.('select'))) return queue();
            }
            for (const n of r.removedNodes) {
                if (n.nodeType === 1 && (n.tagName === 'SELECT' || n.tagName === 'OPTION')) return queue();
            }
        }
    }).observe(document.documentElement, { childList: true, subtree: true });

    function hookLivewire() {
        if (!window.Livewire || hookLivewire.done) return;
        hookLivewire.done = true;
        Livewire.hook('morph.removing', ({ el, skip }) => {
            if (el.tagName === TAG.toUpperCase() && el._rnSel && el._rnSel.isConnected) skip();
        });
        Livewire.hook('morph.updated', ({ el }) => {
            if (el.tagName === 'SELECT' && el._rnUi) {
                hideNative(el);
                queue();
            }
        });
        Livewire.hook('commit', ({ succeed }) => succeed(() => queue()));
    }
    hookLivewire();
    document.addEventListener('livewire:init', hookLivewire);

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => enhanceAll());
    else enhanceAll();
})();
