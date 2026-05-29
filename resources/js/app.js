import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	const notificationToggle = document.getElementById('notificationToggle');
	const notificationBadge = document.getElementById('notificationBadge');
	const notificationMenu = document.getElementById('notificationMenu');
	const notificationSummaryLabel = document.getElementById('notificationSummaryLabel');

	if (!notificationToggle || !notificationBadge || !notificationMenu || !notificationSummaryLabel) {
		return;
	}

	const notificationReadKey = 'posyandu-pintar-notification-last-seen-pending-total';
	const apiUrl = '/api/wargas?status_verifikasi=pending';
	let currentPendingTotal = 0;

	const getToken = () => window.localStorage.getItem('jwt_token');

	const getLastSeenTotal = () => {
		const storedValue = Number(window.localStorage.getItem(notificationReadKey));
		return Number.isFinite(storedValue) ? storedValue : 0;
	};

	const setLastSeenTotal = (value) => {
		window.localStorage.setItem(notificationReadKey, String(value));
	};

	const updateNotificationUi = (pendingTotal) => {
		const lastSeenTotal = getLastSeenTotal();
		const unreadCount = Math.max(pendingTotal - lastSeenTotal, 0);

		notificationBadge.textContent = String(unreadCount);
		notificationBadge.classList.toggle('d-none', unreadCount === 0);
		notificationSummaryLabel.textContent = pendingTotal > 0
			? `Ada ${pendingTotal} warga baru mendaftar`
			: 'Tidak ada warga baru mendaftar';
	};

	const loadPendingNotificationCount = async () => {
		const token = getToken();

		if (!token) {
			updateNotificationUi(0);
			return;
		}

		try {
			const response = await window.axios.get(apiUrl, {
				headers: {
					Authorization: `Bearer ${token}`,
				},
			});

			const pendingTotal = Number(response?.data?.total ?? 0);
			currentPendingTotal = Number.isFinite(pendingTotal) ? pendingTotal : 0;
			updateNotificationUi(currentPendingTotal);
		} catch (error) {
			updateNotificationUi(0);
		}
	};

	loadPendingNotificationCount();

	const refreshNotificationCount = () => {
		loadPendingNotificationCount();
	};

	window.addEventListener('focus', refreshNotificationCount);
	document.addEventListener('visibilitychange', () => {
		if (document.visibilityState === 'visible') {
			refreshNotificationCount();
		}
	});

	const notificationRefreshInterval = window.setInterval(refreshNotificationCount, 15000);

	notificationMenu.addEventListener('click', (event) => {
		const notificationItem = event.target.closest('.notification-item');

		if (!notificationItem) {
			return;
		}

		// mark as seen locally and update UI
		setLastSeenTotal(currentPendingTotal);
		updateNotificationUi(currentPendingTotal);
		// navigate to role-aware verifikasi page
		try {
			const target = window.location.pathname.startsWith('/kader') ? '/kader/verifikasi-data' : '/admin/verifikasi-data';
			window.location.href = target;
		} catch (err) {
			// fallback: do nothing
		}
	});

	window.addEventListener('beforeunload', () => {
		window.clearInterval(notificationRefreshInterval);
	});
});
