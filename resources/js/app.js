

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (typeof window !== 'undefined' && 'serviceWorker' in navigator && (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
	navigator.serviceWorker.getRegistrations().then((registrations) => {
		for (const registration of registrations) {
			registration.unregister();
		}
	});
}

document.addEventListener('DOMContentLoaded', () => {
	const menuToggles = document.querySelectorAll('[data-menu-toggle]');
	const menuClosers = document.querySelectorAll('[data-menu-close]');
	const sidebars = document.querySelectorAll('.portal-sidebar, .app-sidebar');

	const setMenuState = (isOpen) => {
		sidebars.forEach((sidebar) => sidebar.classList.toggle('is-open', isOpen));
		document.body.classList.toggle('menu-open', isOpen);
		menuToggles.forEach((btn) => btn.setAttribute('aria-expanded', String(isOpen)));
	};

	menuToggles.forEach((btn) => {
		btn.addEventListener('click', (e) => {
			e.preventDefault();
			const currentlyOpen = document.body.classList.contains('menu-open');
			setMenuState(!currentlyOpen);
		});
	});

	menuClosers.forEach((closer) => {
		closer.addEventListener('click', (e) => {
			e.preventDefault();
			setMenuState(false);
		});
	});

	sidebars.forEach((sidebar) => {
		sidebar.querySelectorAll('a').forEach((link) => {
			link.addEventListener('click', () => setMenuState(false));
		});
	});

	const currentPath = window.location.pathname;
	document.querySelectorAll('.sidebar-link').forEach((link) => {
		if (link.pathname === currentPath || (link.pathname !== '/' && currentPath.startsWith(link.pathname))) {
			link.setAttribute('aria-current', 'page');
		}
	});

	// Instant Navigation Engine: préchargement automatique au survol et au toucher
	const prefetchedUrls = new Set();
	const prefetchLink = (url) => {
		if (!url || prefetchedUrls.has(url) || url.startsWith('javascript:') || url.includes('#')) return;
		try {
			const parsed = new URL(url, window.location.origin);
			if (parsed.origin !== window.location.origin) return;
			if (parsed.pathname.includes('logout') || parsed.pathname.includes('/pdf')) return;
			prefetchedUrls.add(parsed.href);
			const link = document.createElement('link');
			link.rel = 'prefetch';
			link.href = parsed.href;
			link.as = 'document';
			document.head.appendChild(link);
		} catch (_) {}
	};

	document.querySelectorAll('a[href]').forEach((link) => {
		link.addEventListener('mouseenter', () => prefetchLink(link.href), { passive: true });
		link.addEventListener('touchstart', () => prefetchLink(link.href), { passive: true });
	});

	document.querySelectorAll('[data-toast]').forEach((toast) => {
		toast.querySelector('[data-toast-close]')?.addEventListener('click', () => toast.remove());
		window.setTimeout(() => toast.remove(), 5200);
	});

	document.querySelectorAll('form').forEach((form) => {
		form.addEventListener('submit', () => {
			if (form.method && form.method.toLowerCase() === 'get') return;
			if (form.hasAttribute('data-no-spinner')) return;
			const submit = form.querySelector('button[type="submit"], button:not([type])');
			if (!submit || submit.dataset.loading !== undefined) return;
			submit.dataset.loading = 'true';
			submit.dataset.label = submit.innerHTML;
			submit.innerHTML = '<span class="button-spinner" aria-hidden="true"></span> Traitement...';
			submit.disabled = true;
		});
	});

	document.querySelectorAll('.analysis-choice input[type="checkbox"]').forEach((input) => {
		input.addEventListener('change', () => input.closest('.analysis-choice')?.classList.toggle('is-selected', input.checked));
	});
});
