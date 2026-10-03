import * as bootstrap from 'bootstrap';

/**
 * AppUI — vanilla JS interactivity for the Windows 11 Explorer shell.
 * Progressive enhancement: every feature no-ops when its element is absent.
 */
const AppUI = (() => {
    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function toast(message, variant = 'info') {
        const wrap = $('#appToastContainer');
        if (!wrap) return;
        const el = document.createElement('div');
        el.className = `win-toast ${variant}`;
        el.setAttribute('role', 'status');
        const dot = document.createElement('span');
        dot.className = 'dot';
        const body = document.createElement('div');
        body.className = 'small';
        body.textContent = message;
        el.append(dot, body);
        wrap.appendChild(el);
        autoDismiss(el);
    }

    function autoDismiss(el, delay = 4200) {
        setTimeout(() => {
            el.classList.add('hide');
            el.addEventListener('animationend', () => el.remove(), { once: true });
        }, delay);
    }

    function initFlashToasts() {
        $$('#appToastContainer .win-toast[data-autodismiss]').forEach((el) => {
            el.removeAttribute('data-autodismiss');
            autoDismiss(el);
        });
    }

    function initNav() {
        const nav = $('#winNav');
        const overlay = $('#winOverlay');
        const toggle = $('#navToggle');
        if (!nav || !toggle) return;

        const open = () => {
            nav.classList.add('open');
            overlay?.classList.add('show');
        };
        const close = () => {
            nav.classList.remove('open');
            overlay?.classList.remove('show');
        };

        toggle.addEventListener('click', () => (nav.classList.contains('open') ? close() : open()));
        overlay?.addEventListener('click', close);
        document.addEventListener('keydown', (e) => e.key === 'Escape' && close());
        $$('.win-navitem', nav).forEach((a) => a.addEventListener('click', close));
        window.addEventListener('resize', () => window.innerWidth > 991.98 && close());
    }

    function initTheme() {
        const btns = $$('[data-theme-toggle]');
        if (!btns.length) return;
        const sync = () => {
            const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            btns.forEach((b) => b.setAttribute('aria-pressed', String(dark)));
        };
        btns.forEach((btn) =>
            btn.addEventListener('click', () => {
                const next =
                    document.documentElement.getAttribute('data-bs-theme') === 'dark'
                        ? 'light'
                        : 'dark';
                document.documentElement.setAttribute('data-bs-theme', next);
                try {
                    localStorage.setItem('app-theme', next);
                } catch (e) {
                    /* ignore */
                }
                sync();
                document.dispatchEvent(new CustomEvent('app:theme', { detail: next }));
            })
        );
        sync();
    }

    function initViewToggle() {
        const wrap = $('.win-listwrap');
        const btns = $$('[data-view-toggle]');
        if (!wrap || !btns.length) return;
        btns.forEach((btn) =>
            btn.addEventListener('click', () => {
                wrap.classList.toggle('view-tiles');
                btns.forEach((b) => b.classList.toggle('active', wrap.classList.contains('view-tiles')));
            })
        );
    }

    function initRefresh() {
        $$('[data-refresh]').forEach((btn) =>
            btn.addEventListener('click', () => window.location.reload())
        );
    }

    function initRowSelect() {
        const rows = $$('.win-list tbody tr').filter((r) => !r.dataset.emptyRow);
        rows.forEach((row) =>
            row.addEventListener('click', (e) => {
                if (e.target.closest('a, button, input, select, form, label')) return;
                const multi = e.ctrlKey || e.metaKey;
                if (!multi) rows.forEach((r) => r.classList.remove('selected'));
                row.classList.toggle('selected');
            })
        );
    }

    function initTableSearch() {
        $$('[data-table-search]').forEach((input) => {
            const target = $(input.dataset.tableSearch);
            if (!target) return;
            const rows = $$('tr', target).filter((r) => !r.dataset.emptyRow);
            const card = input.closest('.win-content') || document;
            const empty = $('[data-search-empty]', card);

            const run = () => {
                const q = input.value.trim().toLowerCase();
                let visible = 0;
                rows.forEach((row) => {
                    const match = !q || row.textContent.toLowerCase().includes(q);
                    row.hidden = !match;
                    if (match) visible++;
                });
                if (empty) empty.hidden = visible !== 0;
                document.dispatchEvent(new CustomEvent('app:filtered', { detail: visible }));
            };
            input.addEventListener('input', run);
            const clear = $('[data-search-clear]', input.closest('.win-search') || document);
            clear?.addEventListener('click', () => {
                input.value = '';
                run();
                input.focus();
            });
            run();
        });
    }

    function initTableFilter() {
        $$('[data-table-filter]').forEach((sel) => {
            const tbody = $(sel.dataset.tableFilter);
            if (!tbody) return;
            sel.addEventListener('change', () => {
                const val = sel.value;
                $$('tr', tbody).forEach((row) => {
                    if (row.dataset.emptyRow) return;
                    if (val) {
                        row.hidden = row.dataset.filterValue !== val;
                    }
                });
            });
        });
    }

    async function copyText(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (e) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            let ok = false;
            try {
                ok = document.execCommand('copy');
            } catch (err) {
                ok = false;
            }
            ta.remove();
            return ok;
        }
    }

    function initCopy() {
        $$('[data-copy]').forEach((btn) =>
            btn.addEventListener('click', async () => {
                const ok = await copyText(btn.dataset.copy);
                toast(ok ? 'Berhasil disalin ke clipboard' : 'Gagal menyalin', ok ? 'success' : 'danger');
            })
        );
    }

    function initConfirm() {
        const modalEl = $('#confirmModal');
        let pending = null;

        $$('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (e) => {
                if (form.dataset.confirmed === '1') return;
                e.preventDefault();
                const msg = form.dataset.confirm || 'Yakin ingin melanjutkan?';
                if (!modalEl) {
                    if (window.confirm(msg)) {
                        form.dataset.confirmed = '1';
                        form.submit();
                    }
                    return;
                }
                pending = form;
                const label = $('#confirmModalMessage');
                if (label) label.textContent = msg;
                const ok = $('#confirmModalOk');
                if (ok) ok.textContent = form.dataset.confirmButton || 'Ya, lanjutkan';
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        });

        const okBtn = $('#confirmModalOk');
        okBtn?.addEventListener('click', () => {
            if (!pending) return;
            pending.dataset.confirmed = '1';
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            pending.requestSubmit ? pending.requestSubmit() : pending.submit();
            pending = null;
        });
    }

    function initPasswordToggle() {
        $$('[data-toggle-password]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = $(btn.dataset.togglePassword);
                if (!input) return;
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                btn.classList.toggle('active', show);
            });
        });
    }

    function initFormLoading() {
        $$('form[data-loading]').forEach((form) => {
            form.addEventListener('submit', () => {
                if (form.dataset.confirmed === '1' || form.checkValidity()) {
                    const btn = $('[type=submit]', form);
                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.dataset.label = btn.innerHTML;
                        btn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>Memproses…';
                    }
                }
            });
        });
    }

    function initCounters() {
        if (reduceMotion) return;
        $$('[data-count]').forEach((el) => {
            const target = Number(el.dataset.count || 0);
            if (!Number.isFinite(target) || target <= 0) return;
            const duration = 800;
            const start = performance.now();
            const step = (now) => {
                const p = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        });
    }

    function initReveal() {
        const items = $$('.reveal');
        if (!items.length) return;
        if (reduceMotion || !('IntersectionObserver' in window)) {
            items.forEach((el) => el.classList.add('is-visible'));
            return;
        }
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );
        items.forEach((el, i) => {
            el.style.animationDelay = `${Math.min(i * 35, 210)}ms`;
            io.observe(el);
        });
    }

    function boot() {
        initNav();
        initTheme();
        initFlashToasts();
        initViewToggle();
        initRefresh();
        initRowSelect();
        initTableSearch();
        initTableFilter();
        initCopy();
        initConfirm();
        initPasswordToggle();
        initFormLoading();
        initCounters();
        initReveal();
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', boot)
        : boot();

    return { toast, copyText };
})();

window.AppUI = AppUI;