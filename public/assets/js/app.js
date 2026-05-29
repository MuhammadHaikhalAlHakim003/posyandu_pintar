const APP_STORAGE = {
    token: "jwt_token",
    role: "user_role",
    name: "user_name",
    email: "user_email",
    apiKey: "api_key",
    trackedWargaQuery: "tracked_warga_query",
    trackedWargaStatus: "tracked_warga_status",
    publicNotifications: "public_notifications",
    jadwalPosyandu: "jadwal_posyandu_items",
    jadwalAssignments: "jadwal_assignments",
};

const getApiBase = () => document.body?.dataset.apiBase || "/api";

const buildUrl = (path) => {
    if (path.startsWith("http")) {
        return path;
    }
    const base = getApiBase().replace(/\/+$/, "");
    const suffix = path.startsWith("/") ? path : `/${path}`;
    return `${base}${suffix}`;
};

const getToken = () => localStorage.getItem(APP_STORAGE.token);

const setToken = (token) => {
    localStorage.setItem(APP_STORAGE.token, token);
};

const clearAuth = () => {
    localStorage.removeItem(APP_STORAGE.token);
    localStorage.removeItem(APP_STORAGE.role);
    localStorage.removeItem(APP_STORAGE.name);
    localStorage.removeItem(APP_STORAGE.email);
};

const setUserMeta = (user) => {
    if (!user) {
        return;
    }
    const role = user.role;
    const name = user.name || user.nama_lengkap || user.email;
    if (role) {
        localStorage.setItem(APP_STORAGE.role, role);
    }
    if (name) {
        localStorage.setItem(APP_STORAGE.name, name);
    }
    if (user.email) {
        localStorage.setItem(APP_STORAGE.email, user.email);
    }
};

const getUserRole = () => localStorage.getItem(APP_STORAGE.role);

const getUserName = () => localStorage.getItem(APP_STORAGE.name) || "User";

const getUserEmail = () => localStorage.getItem(APP_STORAGE.email) || "-";

const getTrackedWargaQuery = () => localStorage.getItem(APP_STORAGE.trackedWargaQuery);

const setTrackedWargaQuery = (query) => {
    if (query) {
        localStorage.setItem(APP_STORAGE.trackedWargaQuery, query);
    }
};

const getTrackedWargaStatus = () => localStorage.getItem(APP_STORAGE.trackedWargaStatus) || "";

const setTrackedWargaStatus = (status) => {
    if (status) {
        localStorage.setItem(APP_STORAGE.trackedWargaStatus, status);
    }
};

const getPublicNotifications = () => {
    try {
        return JSON.parse(localStorage.getItem(APP_STORAGE.publicNotifications) || "[]");
    } catch (error) {
        return [];
    }
};

const setPublicNotifications = (items) => {
    localStorage.setItem(APP_STORAGE.publicNotifications, JSON.stringify(items.slice(0, 10)));
};

const addPublicNotification = (notification) => {
    const items = getPublicNotifications();
    const nextNotification = {
        ...notification,
        createdAt: notification.createdAt || new Date().toISOString(),
    };
    if (items.some((item) => item.id === nextNotification.id)) {
        return items;
    }
    const nextItems = [nextNotification, ...items];
    setPublicNotifications(nextItems);
    return nextItems;
};

const markPublicNotificationsSeen = () => {
    const items = getPublicNotifications().map((item) => ({
        ...item,
        seen: true,
    }));
    setPublicNotifications(items);
    return items;
};

const renderPublicNotifications = () => {
    const badge = document.getElementById("publicNotifBadge");
    const list = document.getElementById("publicNotifList");
    if (!badge || !list) {
        return;
    }

    const items = getPublicNotifications();
    const unreadCount = items.filter((item) => !item.seen).length;

    if (unreadCount > 0) {
        badge.classList.remove("d-none");
        badge.textContent = unreadCount > 9 ? "9+" : String(unreadCount);
    } else {
        badge.classList.add("d-none");
        badge.textContent = "";
    }

    if (!items.length) {
        list.innerHTML = `
            <li>
                <span class="dropdown-item-text text-muted">Belum ada notifikasi.</span>
            </li>
        `;
        return;
    }

    const formatTimestamp = (value) => {
        if (!value) {
            return "";
        }
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return "";
        }
        return date.toLocaleString("id-ID", {
            day: "numeric",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    list.innerHTML = items
        .map((item) => {
            return `
                <li>
                    <a class="dropdown-item py-2 ${item.seen ? "" : "fw-semibold"}" href="${escapeHtml(item.link || "/cek-status")}">
                        <div class="d-flex align-items-start gap-2">
                            <span class="mt-1 d-inline-block rounded-circle bg-${escapeHtml(item.variant || "primary")}" style="width: 8px; height: 8px;"></span>
                            <div>
                                <div>${escapeHtml(item.title || "Notifikasi")}</div>
                                <div class="small text-muted">${escapeHtml(item.message || "")}</div>
                                <div class="small text-muted mt-1">${escapeHtml(formatTimestamp(item.createdAt))}</div>
                                <div class="small text-primary mt-1">Lihat detail status</div>
                            </div>
                        </div>
                    </a>
                </li>
            `;
        })
        .join("");
};

const initStaffNotifications = () => {
    const notificationToggle = document.getElementById("notificationToggle");
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationMenu = document.getElementById("notificationMenu");
    const notificationSummaryLabel = document.getElementById("notificationSummaryLabel");

    if (!notificationToggle || !notificationBadge || !notificationMenu || !notificationSummaryLabel) {
        return;
    }

    const notificationReadKey = "posyandu-pintar-notification-last-seen-pending-total";
    const pendingUrl = "/wargas?status_verifikasi=pending";
    let currentPendingTotal = 0;

    const getLastSeenTotal = () => {
        const storedValue = Number(localStorage.getItem(notificationReadKey));
        return Number.isFinite(storedValue) ? storedValue : 0;
    };

    const setLastSeenTotal = (value) => {
        localStorage.setItem(notificationReadKey, String(value));
    };

    const renderBadge = () => {
        const unreadCount = Math.max(currentPendingTotal - getLastSeenTotal(), 0);
        notificationBadge.textContent = String(unreadCount);
        notificationBadge.classList.toggle("d-none", unreadCount === 0);
        notificationSummaryLabel.textContent = currentPendingTotal > 0
            ? `Ada ${currentPendingTotal} warga baru mendaftar`
            : "Tidak ada warga baru mendaftar";
    };

    const refreshBadge = async () => {
        const token = getToken();
        if (!token) {
            currentPendingTotal = 0;
            renderBadge();
            return;
        }

        try {
            const response = await apiRequest(pendingUrl, {}, { auth: true });
            currentPendingTotal = Number(response?.total ?? 0);
        } catch (error) {
            currentPendingTotal = 0;
        }

        renderBadge();
    };

    refreshBadge();

    const notificationRefreshInterval = window.setInterval(refreshBadge, 15000);

    window.addEventListener("focus", refreshBadge);
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === "visible") {
            refreshBadge();
        }
    });

    notificationMenu.addEventListener("click", (event) => {
        const notificationItem = event.target.closest(".notification-item");
        if (!notificationItem) {
            return;
        }

        setLastSeenTotal(currentPendingTotal);
        renderBadge();
        // Navigate to role-aware verifikasi page when a notification is clicked
        try {
            const role = getUserRole() || (window.location.pathname.startsWith('/kader') ? 'kader' : 'admin');
            const target = role === 'kader' ? '/kader/verifikasi-data' : '/admin/verifikasi-data';
            window.location.href = target;
        } catch (err) {
            // fallback: do nothing
        }
    });

    window.addEventListener("beforeunload", () => {
        window.clearInterval(notificationRefreshInterval);
    });
};

const syncPublicVerificationNotification = async () => {
    const query = getTrackedWargaQuery();
    const token = getToken();
    if (!query || !token) {
        renderPublicNotifications();
        return;
    }

    try {
        const response = await apiRequest(
            `/public/pendaftaran-warga/status?query=${encodeURIComponent(query)}`,
            {},
            { auth: false },
        );
        const warga = response?.data || response;
        const currentStatus = warga?.status_verifikasi || "pending";
        const previousStatus = getTrackedWargaStatus();

        if (currentStatus !== previousStatus) {
            setTrackedWargaStatus(currentStatus);

            if (currentStatus === "disetujui" || currentStatus === "ditolak") {
                addPublicNotification({
                    id: `warga-${query}-${currentStatus}`,
                    title:
                        currentStatus === "disetujui"
                            ? "Pendaftaran disetujui"
                            : "Pendaftaran ditolak",
                    message:
                        currentStatus === "disetujui"
                            ? "Data warga Anda telah diverifikasi."
                            : "Data warga Anda belum disetujui.",
                    link: `/cek-status?query=${encodeURIComponent(query)}`,
                    variant: currentStatus === "disetujui" ? "success" : "danger",
                    seen: false,
                });
            }
        }
    } catch (error) {
        // Keep the navbar usable even if status polling fails.
    } finally {
        renderPublicNotifications();
    }
};

const getInitials = (name) => {
    const parts = name.trim().split(/\s+/);
    const first = parts[0]?.[0] || "U";
    const second = parts[1]?.[0] || "";
    return `${first}${second}`.toUpperCase();
};

const formatMaybeUnit = (value, unit) => {
    if (value === null || value === undefined) return "-";
    if (typeof value === "string" && value.trim() === "") return "-";
    if (typeof value === "number") return `${value} ${unit}`;
    if (typeof value === "string") return value; // assume API already formatted
    return String(value);
};

const formatDisplayValue = (value, fallback = "-") => {
    if (value === null || value === undefined) {
        return fallback;
    }
    if (typeof value === "string" && value.trim() === "") {
        return fallback;
    }
    return value;
};

// Jadwal assignment helpers (localStorage fallback)
const getJadwalAssignments = () => {
    try {
        return JSON.parse(localStorage.getItem(APP_STORAGE.jadwalAssignments) || "[]");
    } catch (err) {
        return [];
    }
};

const saveJadwalAssignments = (items) => {
    localStorage.setItem(APP_STORAGE.jadwalAssignments, JSON.stringify(items || []));
};

const setJadwalAssignment = (wargaId, jadwal, meta = {}) => {
    if (!wargaId) return null;
    const items = getJadwalAssignments();
    const filtered = items.filter((it) => String(it.warga_id) !== String(wargaId));
    const next = [{
        warga_id: String(wargaId),
        warga_nik: meta.nik || meta.query || "",
        warga_no_hp: meta.no_hp || "",
        jadwal,
    }, ...filtered];
    saveJadwalAssignments(next);
    return jadwal;
};

const getAssignmentForWarga = (wargaId) => {
    if (!wargaId) return null;
    const items = getJadwalAssignments();
    const found = items.find((it) => String(it.warga_id) === String(wargaId));
    return found ? found.jadwal : null;
};

const getAssignmentForWargaKey = (key) => {
    if (!key) return null;
    const normalized = String(key).trim();
    if (!normalized) return null;
    const items = getJadwalAssignments();
    const found = items.find((it) => {
        return String(it.warga_id) === normalized || String(it.warga_nik || "") === normalized || String(it.warga_no_hp || "") === normalized;
    });
    return found ? found.jadwal : null;
};

const removeJadwalAssignment = (wargaId) => {
    const items = getJadwalAssignments().filter((it) => String(it.warga_id) !== String(wargaId));
    saveJadwalAssignments(items);
};

const updateUserUI = () => {
    const name = getUserName();
    const role = getUserRole() || "";
    const initials = getInitials(name);
    const sidebarName = document.getElementById("sidebarUserName");
    const sidebarRole = document.getElementById("sidebarUserRole");
    const sidebarAvatar = document.getElementById("sidebarAvatar");
    const topbarName = document.getElementById("topbarUserName");
    const topbarAvatar = document.getElementById("topbarAvatar");
    const dashboardLink = document.getElementById("dashboardLink");
    const publicAuthArea = document.getElementById("publicAuthArea");

    if (sidebarName) {
        sidebarName.textContent = name;
    }
    if (sidebarRole) {
        sidebarRole.textContent = role
            ? role.charAt(0).toUpperCase() + role.slice(1)
            : "";
    }
    if (sidebarAvatar) {
        sidebarAvatar.textContent = initials;
    }
    if (topbarName) {
        topbarName.textContent = name;
    }
    if (topbarAvatar) {
        topbarAvatar.textContent = initials;
    }
    if (dashboardLink) {
        dashboardLink.setAttribute(
            "href",
            role === "admin"
                ? "/admin/dashboard"
                : role === "kader"
                  ? "/kader/dashboard"
                  : "/",
        );
    }

    // If the user is a kader, adjust some sidebar admin links to their kader equivalents
    if (role === 'kader') {
        document.querySelectorAll('.sidebar-nav a').forEach((a) => {
            const raw = a.getAttribute('href') || '';
            let path = raw;
            try {
                path = new URL(raw, window.location.origin).pathname;
            } catch (err) {
                path = raw;
            }

            if (path.startsWith('/admin/penimbangan')) {
                a.setAttribute('href', window.location.origin + path.replace('/admin/penimbangan', '/kader/penimbangan'));
            }
            if (path.startsWith('/admin/imunisasi')) {
                a.setAttribute('href', window.location.origin + path.replace('/admin/imunisasi', '/kader/imunisasi'));
            }
            if (path.startsWith('/admin/jadwal-posyandu')) {
                a.setAttribute('href', window.location.origin + path.replace('/admin/jadwal-posyandu', '/kader/jadwal-posyandu'));
            }
            if (path.startsWith('/admin/verifikasi-data')) {
                a.setAttribute('href', window.location.origin + path.replace('/admin/verifikasi-data', '/kader/verifikasi-data'));
            }
            if (path.startsWith('/admin/warga')) {
                a.setAttribute('href', window.location.origin + path.replace('/admin/warga', '/kader/warga'));
            }
            
        });
        // Hide admin-only UI elements that are rendered server-side
        document.querySelectorAll('.admin-only').forEach((el) => {
            el.remove();
        });
        // Also ensure topbar notification links point to kader routes when appropriate
        document.querySelectorAll('.notification-item').forEach((a) => {
            try {
                const raw = a.getAttribute('href') || '';
                const path = new URL(raw, window.location.origin).pathname;
                if (path.startsWith('/admin/verifikasi-data')) {
                    a.setAttribute('href', window.location.origin + path.replace('/admin/verifikasi-data', '/kader/verifikasi-data'));
                }
            } catch (err) {
                // ignore malformed hrefs
            }
        });
    }

    if (publicAuthArea) {
        const token = getToken();
        if (token && name) {
            publicAuthArea.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-link nav-link text-decoration-none px-0 shadow-none border-0 position-relative" data-bs-toggle="dropdown" type="button" aria-label="Notifikasi">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none" id="publicNotifBadge"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" id="publicNotifList" style="min-width: 320px; max-height: 320px; overflow-y: auto;">
                            <li>
                                <span class="dropdown-item-text text-muted">Belum ada notifikasi.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle text-decoration-none fw-semibold px-0 shadow-none border-0" data-bs-toggle="dropdown" type="button">
                            ${escapeHtml(name)}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/profile">
                                    <i class="bi bi-person me-2"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-action="logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            `;

            const notifButton = publicAuthArea.querySelector('[aria-label="Notifikasi"]');
            if (notifButton) {
                notifButton.addEventListener("click", () => {
                    markPublicNotificationsSeen();
                    renderPublicNotifications();
                });
            }

            renderPublicNotifications();
            syncPublicVerificationNotification();
            if (!window.__publicNotifPoller) {
                window.__publicNotifPoller = window.setInterval(syncPublicVerificationNotification, 30000);
            }
        } else {
            publicAuthArea.innerHTML = `
                <a class="btn btn-primary btn-sm" href="/login" id="publicAuthLink">Login</a>
            `;
            if (window.__publicNotifPoller) {
                window.clearInterval(window.__publicNotifPoller);
                window.__publicNotifPoller = null;
            }
        }
    }

    document.querySelectorAll("[data-role]").forEach((item) => {
        const itemRole = item.getAttribute("data-role");
        if (!role || !itemRole) {
            return;
        }
        if (itemRole !== role) {
            item.classList.add("d-none");
        }
    });
};

const showAlert = (container, message, type = "danger") => {
    if (!container) {
        return;
    }
    container.textContent = message;
    container.className = `alert alert-${type}`;
    container.classList.remove("d-none");
};

const hideAlert = (container) => {
    if (!container) {
        return;
    }
    container.classList.add("d-none");
};

const setButtonLoading = (button, isLoading, loadingText = "Memproses...") => {
    if (!button) {
        return;
    }
    if (isLoading) {
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = loadingText;
        button.disabled = true;
    } else {
        button.innerHTML = button.dataset.originalText || button.innerHTML;
        button.disabled = false;
    }
};

const clearValidationErrors = (form) => {
    form.querySelectorAll(".is-invalid").forEach((el) => {
        el.classList.remove("is-invalid");
    });
    form.querySelectorAll(".invalid-feedback.dynamic").forEach((el) => {
        el.remove();
    });
};

const applyValidationErrors = (form, errors) => {
    if (!errors) {
        return;
    }
    Object.entries(errors).forEach(([field, messages]) => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) {
            return;
        }
        input.classList.add("is-invalid");
        const feedback = document.createElement("div");
        feedback.className = "invalid-feedback dynamic";
        feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
        const parent = input.closest(".input-group") || input.parentElement;
        parent.appendChild(feedback);
    });
};

const safeJsonParse = async (response) => {
    const text = await response.text();
    if (!text) {
        return null;
    }
    try {
        return JSON.parse(text);
    } catch (error) {
        return null;
    }
};

const apiRequest = async (
    path,
    options = {},
    { auth = true, apiKey = false } = {},
) => {
    const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        ...options.headers,
    };

    if (auth) {
        const token = getToken();
        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }
    }

    if (apiKey) {
        const key = localStorage.getItem(APP_STORAGE.apiKey);
        if (key) {
            headers["X-API-Key"] = key;
        } else {
            throw { status: 400, data: { message: "API key belum diatur." } };
        }
    }

    const response = await fetch(buildUrl(path), {
        ...options,
        headers,
    });

    const data = await safeJsonParse(response);

    if (!response.ok) {
        if (response.status === 401 && auth) {
            clearAuth();
            window.location.href = "/login";
        }
        throw { status: response.status, data };
    }

    return data;
};

const parseNumberLike = (v) => {
    if (v === null || v === undefined) return null;
    if (typeof v === 'number') return v;
    const m = String(v).match(/-?\d+[\d\.,]*/);
    if (!m) return null;
    const n = m[0].replace(/,/g, '.');
    const p = parseFloat(n);
    return Number.isFinite(p) ? p : null;
};

const getRoleHome = (role) => {
    if (role === "admin") {
        return "/admin/dashboard";
    }
    if (role === "kader") {
        return "/kader/dashboard";
    }
    return "/";
};

const getProtectedRole = () => {
    const path = window.location.pathname || "";
    // Allow certain admin-prefixed pages to be viewed by kader (we reuse the templates)
    const adminViewExceptions = [
        '/admin/warga',
        '/admin/penimbangan',
        '/admin/imunisasi',
        '/admin/jadwal-posyandu',
    ];
    for (const ex of adminViewExceptions) {
        if (path.startsWith(ex)) {
            return null;
        }
    }
    if (path.startsWith("/admin/")) {
        return "admin";
    }
    if (path.startsWith("/kader/")) {
        return "kader";
    }
    return null;
};

const redirectAfterAuth = (role) => {
    window.location.href = getRoleHome(role);
};

const ensureUser = async () => {
    try {
        const data = await apiRequest("/auth/me");
        const user = data?.data || data?.user || data;
        setUserMeta(user);
        updateUserUI();

        // If the logged-in user is a kader, attempt to sync any locally stored jadwal items
        if (user?.role === 'kader' && typeof window.syncLocalJadwalToServer === 'function') {
            try {
                await window.syncLocalJadwalToServer();
            } catch (err) {
                // ignore sync errors to avoid blocking the app
            }
        }
    } catch (error) {
        if (error.status === 401) {
            clearAuth();
            window.location.href = "/login";
        }
    }
};

const initAuthGuard = async () => {
    const authMode = document.body?.dataset.auth;
    if (!authMode) {
        return;
    }
    const token = getToken();
    if (authMode === "required") {
        if (!token) {
            window.location.href = "/login";
            return;
        }
        if (!getUserRole()) {
            await ensureUser();
        }
        const role = getUserRole();
        const requiredRole = getProtectedRole();
        if (requiredRole && role && role !== requiredRole) {
            window.location.href = getRoleHome(role);
            return;
        }
    }
    if (authMode === "guest" && token) {
        if (!getUserRole()) {
            await ensureUser();
        }
        redirectAfterAuth(getUserRole());
    }
};

const initLogin = () => {
    const form = document.getElementById("loginForm");
    if (!form) {
        return;
    }
    const alertBox = document.getElementById("loginAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);
        const formData = new FormData(form);
        const email = formData.get("email");
        const password = formData.get("password");
        setButtonLoading(submitButton, true, "Masuk...");

        try {
            const data = await apiRequest(
                "/auth/login",
                {
                    method: "POST",
                    body: JSON.stringify({ email, password }),
                },
                { auth: false },
            );

            const token = data?.access_token || data?.token;
            if (!token) {
                throw new Error("Token tidak ditemukan dari API.");
            }
            setToken(token);
            await ensureUser();
            redirectAfterAuth(getUserRole());
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            const message =
                error?.data?.message ||
                error?.message ||
                "Login gagal. Periksa email dan password.";
            showAlert(alertBox, message, "danger");
        } finally {
            setButtonLoading(submitButton, false);
        }
    });
};

const initRegister = () => {
    const form = document.getElementById("registerForm");
    if (!form) {
        return;
    }
    const alertBox = document.getElementById("registerAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);

        const formData = new FormData(form);
        const name = formData.get("name");
        const email = formData.get("email");
        const password = formData.get("password");
        const passwordConfirmation = formData.get("password_confirmation");

        setButtonLoading(submitButton, true, "Mendaftar...");

        try {
            const data = await apiRequest(
                "/auth/register",
                {
                    method: "POST",
                    body: JSON.stringify({
                        name,
                        email,
                        password,
                        password_confirmation: passwordConfirmation,
                    }),
                },
                { auth: false },
            );

            const token = data?.access_token || data?.token;
            const user = data?.user || data?.data?.user || null;

            if (!token) {
                throw new Error("Token tidak ditemukan dari API.");
            }

            setToken(token);
            setUserMeta(user);
            if (!user) {
                await ensureUser();
            }
            redirectAfterAuth(getUserRole());
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            const message =
                error?.data?.message ||
                error?.message ||
                "Registrasi gagal. Periksa kembali data yang diisi.";
            showAlert(alertBox, message, "danger");
        } finally {
            setButtonLoading(submitButton, false);
        }
    });
};

const initPublicWargaRegistration = () => {
    const form = document.getElementById("publicWargaForm");
    if (!form) {
        return;
    }

    const alertBox = document.getElementById("publicWargaAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const nikInput = form.querySelector('[name="nik"]');
    const nikHelpText = document.getElementById("nikHelpText");
    const nikLengthFeedback = document.getElementById("nikLengthFeedback");

    const updateNikHint = () => {
        if (!nikInput || !nikHelpText || !nikLengthFeedback) {
            return;
        }

        const digitsOnly = nikInput.value.replace(/\D/g, "");
        if (nikInput.value !== digitsOnly) {
            nikInput.value = digitsOnly;
        }

        const remaining = 16 - digitsOnly.length;
        const isValidLength = digitsOnly.length === 16;

        nikInput.classList.toggle("is-invalid", !isValidLength && digitsOnly.length > 0);
        nikLengthFeedback.classList.toggle("d-none", isValidLength || digitsOnly.length === 0);

        if (digitsOnly.length === 0) {
            nikHelpText.textContent = "16 digit nomor KTP/KK";
        } else if (isValidLength) {
            nikHelpText.textContent = "NIK sudah sesuai 16 digit.";
        } else {
            nikHelpText.textContent = `Kurang ${remaining} digit lagi agar NIK menjadi 16 digit.`;
        }
    };

    if (nikInput) {
        nikInput.addEventListener("input", updateNikHint);
        nikInput.addEventListener("blur", updateNikHint);
        updateNikHint();
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);

        updateNikHint();
        const nikValue = nikInput?.value.trim() || "";
        if (nikValue.length !== 16) {
            if (nikInput) {
                nikInput.classList.add("is-invalid");
            }
            if (nikLengthFeedback) {
                nikLengthFeedback.classList.remove("d-none");
            }
            showAlert(alertBox, "NIK harus terdiri dari 16 digit.", "danger");
            return;
        }

        const payload = serializeForm(form);
        if (payload.agree !== "1") {
            showAlert(alertBox, "Centang persetujuan terlebih dahulu.", "danger");
            return;
        }

        delete payload.agree;
        setButtonLoading(submitButton, true, "Mengirim...");

        try {
            const data = await apiRequest(
                "/public/pendaftaran-warga",
                {
                    method: "POST",
                    body: JSON.stringify(payload),
                },
                { auth: false },
            );

            const warga = data?.data || data;
            const query = warga?.nik || payload.nik || "";
            setTrackedWargaQuery(query);
            setTrackedWargaStatus("pending");
            window.location.href = `/cek-status?query=${encodeURIComponent(query)}`;
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            showAlert(
                alertBox,
                error?.data?.message ||
                    "Pendaftaran gagal. Periksa kembali data yang diisi.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    });
};

const initPublicWargaStatus = () => {
    const form = document.getElementById("cekStatusForm");
    const resultCard = document.getElementById("cekStatusCard");
    if (!form || !resultCard) {
        return;
    }

    const alertBox = document.getElementById("cekStatusAlert");
    const nameEl = document.getElementById("cekStatusName");
    const nikEl = document.getElementById("cekStatusNik");
    const badgeEl = document.getElementById("cekStatusBadge");
    const kategoriEl = document.getElementById("cekStatusKategori");
    const timelineEl = document.getElementById("cekStatusTimeline");
    const jadwalBoxEl = document.getElementById("cekStatusJadwalBox");
    const jadwalTextEl = document.getElementById("cekStatusJadwalText");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const queryInput = form.querySelector('[name="query"]');
    const params = new URLSearchParams(window.location.search);

    const getBadgeClass = (status) => {
        if (status === "disetujui") {
            return "bg-success";
        }
        if (status === "ditolak") {
            return "bg-danger";
        }
        return "bg-warning text-dark";
    };

    const getStatusLabel = (status) => {
        if (status === "disetujui") {
            return "Terverifikasi";
        }
        if (status === "ditolak") {
            return "Ditolak";
        }
        return "Menunggu Verifikasi";
    };

    const extractAssignedJadwal = (wargaDetail) => {
        if (!wargaDetail) {
            return null;
        }

        const assignments =
            wargaDetail.jadwal_assignments ||
            wargaDetail.jadwalAssignments ||
            [];

        if (!Array.isArray(assignments) || !assignments.length) {
            return null;
        }

        const firstAssignment = assignments[0] || null;
        if (!firstAssignment) {
            return null;
        }

        return (
            firstAssignment.jadwal_posyandu ||
            firstAssignment.jadwalPosyandu ||
            firstAssignment.jadwal ||
            null
        );
    };

    const renderTimeline = (warga) => {
        if (!timelineEl) {
            return;
        }

        const createdAt = formatDate(warga?.created_at);
        const verifiedAt = formatDate(warga?.verified_at);
        const status = warga?.status_verifikasi || "pending";
        const stepThreeLabel = status === "ditolak" ? "Ditolak" : "Terverifikasi";
        const stepThreeState =
            status === "pending"
                ? "pending"
                : status === "ditolak"
                  ? "rejected"
                  : "approved";

        const steps = [
            {
                title: "Pendaftaran Dikirim",
                date: createdAt,
                state: "submitted",
            },
            {
                title: "Sedang Ditinjau",
                date: status === "pending" ? "Sedang diproses" : verifiedAt,
                state: status === "pending" ? "review" : "approved",
            },
            {
                title: stepThreeLabel,
                date: status === "pending" ? "Menunggu keputusan kader" : verifiedAt,
                state: stepThreeState,
            },
        ];

        timelineEl.innerHTML = steps
            .map((step) => {
                return `
                    <div class="timeline-item">
                        <span class="timeline-dot ${step.state}"></span>
                        <div class="ms-4">
                            <div class="fw-bold">${escapeHtml(step.title)}</div>
                            <div class="small text-muted">${escapeHtml(step.date || "-")}</div>
                        </div>
                    </div>
                `;
            })
            .join("");
    };

    const renderJadwal = async (warga, query) => {
        if (!jadwalTextEl || !jadwalBoxEl) {
            return;
        }

        const localJadwal =
            getAssignmentForWarga(warga?.id) ||
            getAssignmentForWargaKey(warga?.nik) ||
            getAssignmentForWargaKey(warga?.no_hp) ||
            getAssignmentForWargaKey(query);

        const displayJadwal = (jadwal) => {
            if (jadwal) {
                jadwalBoxEl.classList.remove("alert-info");
                jadwalBoxEl.classList.add("alert-success");
                jadwalTextEl.innerHTML = `${escapeHtml(formatDate(jadwal.tanggal_pelaksanaan))} ${escapeHtml(jadwal.waktu_mulai || "-")}-${escapeHtml(jadwal.waktu_selesai || "-")} WIB · ${escapeHtml(jadwal.lokasi || "-")} · ${escapeHtml(jadwal.kategori_posyandu || "-")}`;
                return true;
            }

            jadwalBoxEl.classList.remove("alert-success");
            jadwalBoxEl.classList.add("alert-info");
            jadwalTextEl.textContent = "Jadwal akan tampil setelah kader menetapkannya.";
            return false;
        };

        if (displayJadwal(localJadwal)) {
            return;
        }

        if (!getToken() || !warga?.id) {
            return;
        }

        try {
            const detail = await apiRequest(`/wargas/${warga.id}`);
            const serverJadwal = extractAssignedJadwal(detail?.data || detail);
            displayJadwal(serverJadwal);
        } catch (error) {
            // Keep the page usable if the authenticated fallback cannot be loaded.
        }
    };

    const renderResult = (warga, query = "") => {
        if (!warga) {
            resultCard.classList.add("d-none");
            return;
        }

        resultCard.classList.remove("d-none");
        if (nameEl) {
            nameEl.textContent = warga.nama_lengkap || "-";
        }
        if (nikEl) {
            nikEl.textContent = `NIK: ${warga.nik || "-"}`;
        }
        if (badgeEl) {
            badgeEl.className = `badge ${getBadgeClass(warga.status_verifikasi)}`;
            badgeEl.textContent = getStatusLabel(warga.status_verifikasi);
        }
        if (kategoriEl) {
            kategoriEl.textContent = warga.kategori || "-";
            kategoriEl.className = `badge-category ${
                warga.kategori === "balita"
                    ? "badge-balita"
                    : warga.kategori === "ibu_hamil"
                      ? "badge-ibu"
                      : warga.kategori === "lansia"
                        ? "badge-lansia"
                        : "badge-balita"
            }`;
        }

        renderTimeline(warga);
        renderJadwal(warga, query);
    };

    const loadStatus = async (query) => {
        hideAlert(alertBox);
        if (!query) {
            showAlert(alertBox, "Masukkan NIK atau nomor WhatsApp terlebih dahulu.", "danger");
            resultCard.classList.add("d-none");
            return;
        }

        setButtonLoading(submitButton, true, "Mencari...");
        try {
            const response = await apiRequest(
                `/public/pendaftaran-warga/status?query=${encodeURIComponent(query)}`,
                {},
                { auth: false },
            );
            const warga = response?.data || response;
            renderResult(warga, query);
        } catch (error) {
            resultCard.classList.add("d-none");
            showAlert(
                alertBox,
                error?.data?.message || "Data pendaftaran tidak ditemukan.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    };

    if (queryInput && params.get("query")) {
        queryInput.value = params.get("query");
        setTrackedWargaQuery(params.get("query"));
        loadStatus(params.get("query"));
    }

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        loadStatus(formData.get("query")?.toString().trim());
    });
};

const initVerifikasiIndex = () => {
    const tableBody = document.getElementById("verifikasiTableBody");
    if (!tableBody) {
        return;
    }

    const alertBox = document.getElementById("verifikasiAlert");
    const summary = document.getElementById("verifikasiSummary");
    const pendingCount = document.getElementById("verifikasiPendingCount");
    const approvedCount = document.getElementById("verifikasiApprovedCount");
    const filterForm = document.getElementById("verifikasiFilterForm");
    const params = new URLSearchParams(window.location.search);

    if (filterForm) {
        filterForm.querySelectorAll("input, select").forEach((input) => {
            if (params.has(input.name)) {
                input.value = params.get(input.name);
            }
        });

        filterForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(filterForm);
            const nextParams = new URLSearchParams();
            formData.forEach((value, key) => {
                if (value) {
                    nextParams.set(key, value.toString());
                }
            });
            window.location.search = nextParams.toString();
        });
    }

    const statusLabel = (status) => {
        if (status === "disetujui") {
            return "Disetujui";
        }
        if (status === "ditolak") {
            return "Ditolak";
        }
        return "Menunggu";
    };

    const statusBadgeClass = (status) => {
        if (status === "disetujui") {
            return "bg-success";
        }
        if (status === "ditolak") {
            return "bg-danger";
        }
        return "bg-warning text-dark";
    };

    const categoryBadgeClass = (kategori) => {
        if (kategori === "ibu_hamil") {
            return "bg-danger";
        }
        if (kategori === "lansia") {
            return "bg-success";
        }
        return "bg-primary";
    };

    const renderRows = (items = []) => {
        if (!items.length) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pendaftaran.</td></tr>';
            return;
        }

        tableBody.innerHTML = items
            .map((item, index) => {
                const canVerify = item.status_verifikasi === "pending";
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${escapeHtml(item.nama_lengkap || "-")}</strong></td>
                        <td><code>${escapeHtml(item.nik || "-")}</code></td>
                        <td><span class="badge ${categoryBadgeClass(item.kategori)}">${escapeHtml(item.kategori || "-")}</span></td>
                        <td>${escapeHtml(formatDate(item.created_at))}</td>
                        <td><span class="badge ${statusBadgeClass(item.status_verifikasi)}">${escapeHtml(statusLabel(item.status_verifikasi))}</span></td>
                        <td class="text-center">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <a href="/admin/warga/${item.id}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye me-1"></i>Detail</a>
                                ${canVerify ? `<button type="button" class="btn btn-sm btn-success" data-action="approve-warga" data-id="${item.id}"><i class="bi bi-check-lg me-1"></i>Setujui</button>` : `<button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-check-lg me-1"></i>Sudah</button>`}
                            </div>
                        </td>
                    </tr>
                `;
            })
            .join("");

        tableBody.querySelectorAll("[data-action='approve-warga']").forEach((button) => {
            button.addEventListener("click", async () => {
                const id = button.getAttribute("data-id");
                if (!id) return;
                const selectedWarga = items.find((entry) => String(entry.id) === String(id));
                // open jadwal assign modal instead of immediate approve
                openJadwalAssignModal(id, async (assignedJadwal) => {
                            try {
                                // assign on server
                                if (assignedJadwal && assignedJadwal.id) {
                                    await apiRequest(`/wargas/${id}/assign-jadwal`, {
                                        method: 'POST',
                                        body: JSON.stringify({ jadwal_id: assignedJadwal.id }),
                                    });
                                }
                                // then approve on server
                                await apiRequest(`/wargas/${id}/approve`, { method: "POST" });
                                setJadwalAssignment(id, assignedJadwal, {
                                    nik: selectedWarga?.nik,
                                    query: selectedWarga?.nik,
                                    no_hp: selectedWarga?.no_hp,
                                });
                                if (alertBox) {
                                    showAlert(alertBox, 'Jadwal ditetapkan dan pendaftaran disetujui.', 'success');
                                }
                                await loadData();
                    } catch (error) {
                        showAlert(alertBox, error?.data?.message || "Gagal menyetujui data.", "danger");
                    }
                });
            });
        });

        // Note: reject action removed — 'Tolak' button intentionally hidden
    };

    const loadCounts = async () => {
        try {
            const [pending, approved] = await Promise.all([
                apiRequest("/wargas?status_verifikasi=pending&per_page=1"),
                apiRequest("/wargas?status_verifikasi=disetujui&per_page=1"),
            ]);

            if (pendingCount) {
                pendingCount.textContent = pending?.total ?? 0;
            }
            if (approvedCount) {
                approvedCount.textContent = approved?.total ?? 0;
            }
        } catch (error) {
            // Keep the page usable even if summary counts fail.
        }
    };

    const loadData = async () => {
        try {
            hideAlert(alertBox);
            const searchParams = new URLSearchParams();
            if (params.get("search")) {
                searchParams.set("search", params.get("search"));
            }
            if (params.get("kategori")) {
                searchParams.set("kategori", params.get("kategori"));
            }
            if (params.get("status_verifikasi")) {
                searchParams.set("status_verifikasi", params.get("status_verifikasi"));
            } else {
                searchParams.set("status_verifikasi", "pending");
            }

            const response = await apiRequest(`/wargas?${searchParams.toString()}`);
            const items = response?.data || [];
            renderRows(items);
            if (summary) {
                summary.textContent = `Menampilkan ${items.length} dari ${response?.total || 0} data`;
            }
        } catch (error) {
            showAlert(alertBox, error?.data?.message || "Gagal memuat data verifikasi.", "danger");
        }
    };

    loadCounts();
    loadData();
};

// Modal-based jadwal assign flow
let _jadwalAssignCallback = null;
let _jadwalAssignWargaId = null;

const openJadwalAssignModal = async (wargaId, callback) => {
    _jadwalAssignCallback = callback;
    _jadwalAssignWargaId = wargaId;
    const modalEl = document.getElementById('jadwalAssignModal');
    const listEl = document.getElementById('jadwalList');
    const alertEl = document.getElementById('jadwalAssignAlert');
    const createForm = document.getElementById('jadwalCreateForm');
    const confirmBtn = document.getElementById('jadwalAssignConfirm');

    if (!modalEl || !listEl) {
        alert('Modal jadwal tidak tersedia.');
        return;
    }

    const bsModal = new bootstrap.Modal(modalEl);

    let _jadwalItemsCache = [];
    const loadJadwals = async () => {
        listEl.innerHTML = '<div class="text-center text-muted py-3">Memuat jadwal...</div>';
        try {
            const res = await apiRequest('/jadwal-posyandus');
            const items = res?.data || [];
            _jadwalItemsCache = Array.isArray(items) ? items : [];
            if (!_jadwalItemsCache.length) {
                listEl.innerHTML = '<div class="text-center text-muted py-3">Belum ada jadwal.</div>';
                return;
            }
            listEl.innerHTML = _jadwalItemsCache
                .map((it) => `
                    <div class="form-check py-2 border-bottom">
                        <input class="form-check-input" type="radio" name="jadwal_select" id="jadwal_${it.id}" value="${escapeHtml(String(it.id))}">
                        <label class="form-check-label ms-2" for="jadwal_${it.id}">
                            <strong>${escapeHtml(it.tanggal_pelaksanaan)}</strong> ${escapeHtml(it.waktu_mulai)}-${escapeHtml(it.waktu_selesai)} — ${escapeHtml(it.lokasi)} (${escapeHtml(it.kategori_posyandu)})
                        </label>
                    </div>
                `)
                .join('');
        } catch (error) {
            listEl.innerHTML = '<div class="text-danger py-2">Gagal memuat jadwal.</div>';
        }
    };

    // create form handler
    if (createForm) {
        // Use onsubmit to avoid duplicate listeners when modal reopened
        createForm.onsubmit = async (e) => {
            e.preventDefault();
            if (alertEl) { alertEl.classList.add('d-none'); }
            const formData = new FormData(createForm);
            const payload = Object.fromEntries(formData.entries());
            try {
                const res = await apiRequest('/jadwal-posyandus', { method: 'POST', body: JSON.stringify(payload) });
                const newItem = res?.data || res;
                // reload list and select the new item, then reset form
                await loadJadwals();
                // try to select by id
                const radio = listEl.querySelector(`input[value='${escapeHtml(String(newItem.id))}']`);
                if (radio) radio.checked = true;
                try { createForm.reset(); } catch (err) {}
            } catch (error) {
                if (alertEl) {
                    alertEl.classList.remove('d-none');
                    alertEl.classList.add('alert-danger');
                    alertEl.textContent = error?.data?.message || 'Gagal membuat jadwal.';
                }
            }
        };
    }

    // confirm handler
    const onConfirm = async () => {
        const checked = listEl.querySelector('input[name="jadwal_select"]:checked');
        if (!checked) {
            if (alertEl) {
                alertEl.classList.remove('d-none');
                alertEl.classList.add('alert-warning');
                alertEl.textContent = 'Pilih jadwal terlebih dahulu.';
            }
            return;
        }
        let jadwal = null;
        try {
            const selId = checked.value;
            jadwal = (_jadwalItemsCache || []).find((it) => String(it.id) === String(selId)) || null;
        } catch (err) {
            jadwal = null;
        }
        bsModal.hide();
        if (_jadwalAssignCallback) {
            await _jadwalAssignCallback(jadwal);
        }
    };

    if (confirmBtn) {
        // assign directly to onclick to avoid duplicate listeners
        confirmBtn.onclick = onConfirm;
    }

    // load and show
    await loadJadwals();
    bsModal.show();
};

const initLogout = () => {
    document.addEventListener("click", async (event) => {
        const trigger = event.target.closest('[data-action="logout"]');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        try {
            await apiRequest("/auth/logout", { method: "POST" });
        } catch (error) {
            // Ignore logout error to ensure local state is cleared.
        }
        clearAuth();
        window.location.href = "/login";
    });
};

const initPublicProfile = async () => {
    const p = window.location.pathname || '';
    if (!(p === '/profile' || p === '/admin/profile' || p === '/kader/profile')) {
        return;
    }

    const token = getToken();
    if (!token) {
        window.location.href = "/login";
        return;
    }

    const alertBox = document.getElementById("profileAlert");
    const avatar = document.getElementById("profileAvatar");
    const nameEl = document.getElementById("profileName");
    const emailEl = document.getElementById("profileEmail");
    const roleEl = document.getElementById("profileRole");

    try {
        const data = await apiRequest("/auth/me");
        const user = data?.data || data?.user || data;
        setUserMeta(user);
        updateUserUI();

        const name = user?.name || user?.nama_lengkap || getUserName();
        const email = user?.email || getUserEmail();
        const role = user?.role || getUserRole() || "user";

        if (avatar) {
            avatar.textContent = getInitials(name);
        }
        if (nameEl) {
            nameEl.textContent = name;
        }
        if (emailEl) {
            emailEl.textContent = email;
        }
        if (roleEl) {
            roleEl.textContent = role;
        }
    } catch (error) {
        if (error?.status === 401) {
            clearAuth();
            window.location.href = "/login";
            return;
        }
        showAlert(
            alertBox,
            error?.data?.message || "Gagal memuat profil.",
            "danger",
        );
    }
};

const escapeHtml = (value) => {
    if (value === null || value === undefined) {
        return "";
    }
    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
};

const parsePathId = () => {
    const parts = window.location.pathname.split("/").filter(Boolean);
    if (parts.length === 0) {
        return null;
    }
    const last = parts[parts.length - 1];
    if (last === "edit") {
        return parts[parts.length - 2] || null;
    }
    return last || null;
};

const formatDate = (value) => {
    if (!value) {
        return "-";
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const calculateAge = (value) => {
    if (!value) {
        return "-";
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return "-";
    }
    const today = new Date();
    let age = today.getFullYear() - date.getFullYear();
    const month = today.getMonth() - date.getMonth();
    if (month < 0 || (month === 0 && today.getDate() < date.getDate())) {
        age -= 1;
    }
    return `${age} thn`;
};

const toInputDate = (value) => {
    if (!value) {
        return "";
    }
    if (value.includes("T")) {
        return value.split("T")[0];
    }
    return value;
};

const serializeForm = (form) => {
    const data = {};
    form.querySelectorAll("input, select, textarea").forEach((element) => {
        if (!element.name || element.disabled) {
            return;
        }
        if (element.type === "radio") {
            if (element.checked) {
                data[element.name] = element.value;
            }
            return;
        }
        if (element.type === "checkbox") {
            data[element.name] = element.checked ? "1" : "0";
            return;
        }
        if (element.value !== "") {
            data[element.name] = element.value;
        }
    });
    return data;
};

const initJadwalPosyanduIndex = () => {
    const tableBody = document.getElementById("jadwalPosyanduTableBody");
    if (!tableBody) {
        return;
    }

    const form = document.getElementById("jadwalPosyanduForm");
    const alertBox = document.getElementById("jadwalPosyanduAlert");
    const summary = document.getElementById("jadwalPosyanduSummary");
    const totalEl = document.getElementById("jadwalPosyanduTotal");
    const balitaEl = document.getElementById("jadwalPosyanduBalita");
    const ibuHamilEl = document.getElementById("jadwalPosyanduIbuHamil");
    const lansiaEl = document.getElementById("jadwalPosyanduLansia");
    const submitButton = document.getElementById("jadwalPosyanduSubmitBtn");
    const cancelEditButton = document.getElementById("jadwalPosyanduCancelEdit");
    const idInput = document.getElementById("jadwalPosyanduId");
    const endpoint = "/jadwal-posyandus";
    let currentItems = [];

    const categoryLabels = {
        balita: "Balita",
        ibu_hamil: "Ibu Hamil",
        lansia: "Lansia",
    };

    const locationLabels = {
        balai_rw_terdekat: "Balai RW terdekat",
        rumah_pak_rt_terdekat: "Rumah Pak RT terdekat",
        gedung_pkk_terdekat: "Gedung PKK terdekat",
    };

    const loadItems = async () => {
        try {
            // prefer API when user has token
            const token = getToken();
            if (token) {
                const response = await apiRequest(endpoint);
                const data = response?.data || response || [];
                return Array.isArray(data) ? data : [];
            }
        } catch (error) {
            if (error?.status !== 401) {
                throw error;
            }
            // fall back to localStorage when unauthenticated
        }

        try {
            const rawItems = JSON.parse(localStorage.getItem(APP_STORAGE.jadwalPosyandu) || "[]");
            return Array.isArray(rawItems) ? rawItems : [];
        } catch (error) {
            return [];
        }
    };

    const persistItem = async (payload, editingId = "") => {
        const token = getToken();
        if (!token) {
            // fallback to localStorage behavior
            try {
                const rawItems = JSON.parse(localStorage.getItem(APP_STORAGE.jadwalPosyandu) || "[]");
                const items = Array.isArray(rawItems) ? rawItems : [];
                const now = new Date().toISOString();
                const editing = editingId || payload.id || "";
                const nextItem = {
                    id: editing || `jadwal-${Date.now()}`,
                    tanggal_pelaksanaan: payload.tanggal_pelaksanaan,
                    waktu_mulai: payload.waktu_mulai,
                    waktu_selesai: payload.waktu_selesai,
                    kategori_posyandu: payload.kategori_posyandu,
                    lokasi: payload.lokasi,
                    created_at: editing ? (items.find((it) => it.id === editing)?.created_at || now) : now,
                    updated_at: now,
                };
                const nextItems = editing ? items.map((it) => (it.id === editing ? nextItem : it)) : [nextItem, ...items];
                localStorage.setItem(APP_STORAGE.jadwalPosyandu, JSON.stringify(nextItems));
                return { data: nextItem };
            } catch (err) {
                throw err;
            }
        }

        if (editingId) {
            return apiRequest(`${endpoint}/${editingId}`, {
                method: "PUT",
                body: JSON.stringify(payload),
            });
        }

        return apiRequest(endpoint, {
            method: "POST",
            body: JSON.stringify(payload),
        });
    };

    const getCategoryLabel = (value) => categoryLabels[value] || value || "-";
    const getLocationLabel = (value) => locationLabels[value] || value || "-";

    const resetEditor = () => {
        if (!form) {
            return;
        }
        form.reset();
        if (idInput) {
            idInput.value = "";
        }
        if (submitButton) {
            submitButton.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Jadwal';
        }
        if (cancelEditButton) {
            cancelEditButton.classList.add("d-none");
        }
    };

    const renderSummary = (items) => {
        const counts = items.reduce(
            (accumulator, item) => {
                const category = item.kategori_posyandu;
                if (category === "balita") accumulator.balita += 1;
                if (category === "ibu_hamil") accumulator.ibu_hamil += 1;
                if (category === "lansia") accumulator.lansia += 1;
                return accumulator;
            },
            { balita: 0, ibu_hamil: 0, lansia: 0 },
        );

        if (totalEl) {
            totalEl.textContent = String(items.length);
        }
        if (balitaEl) {
            balitaEl.textContent = String(counts.balita);
        }
        if (ibuHamilEl) {
            ibuHamilEl.textContent = String(counts.ibu_hamil);
        }
        if (lansiaEl) {
            lansiaEl.textContent = String(counts.lansia);
        }
        if (summary) {
            summary.textContent = `${items.length} jadwal tersimpan`;
        }
    };

    const renderRows = (items) => {
        currentItems = items;
        if (!items.length) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada jadwal posyandu.</td></tr>';
            return;
        }

        tableBody.innerHTML = items
            .map((item, index) => {
                const timeRange = `${item.waktu_mulai || "-"} - ${item.waktu_selesai || "-"}`;
                return `
                    <tr data-jadwal-id="${escapeHtml(item.id)}">
                        <td>${index + 1}</td>
                        <td>${escapeHtml(formatDate(item.tanggal_pelaksanaan))}</td>
                        <td>${escapeHtml(timeRange)}</td>
                        <td><span class="badge bg-primary">${escapeHtml(getCategoryLabel(item.kategori_posyandu))}</span></td>
                        <td>${escapeHtml(getLocationLabel(item.lokasi))}</td>
                        <td>${escapeHtml(formatDate(item.created_at))}</td>
                        <td class="text-center">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-action="edit-jadwal" data-id="${escapeHtml(item.id)}">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete-jadwal" data-id="${escapeHtml(item.id)}">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            })
            .join("");
    };

    const render = (items) => {
        renderSummary(items);
        renderRows(items);
    };

    const startEdit = (itemId) => {
        const item = currentItems.find((entry) => String(entry?.id) === String(itemId));
        if (!item || !form) {
            return;
        }

        form.querySelector('[name="tanggal_pelaksanaan"]').value = item.tanggal_pelaksanaan || "";
        form.querySelector('[name="waktu_mulai"]').value = item.waktu_mulai || "";
        form.querySelector('[name="waktu_selesai"]').value = item.waktu_selesai || "";
        form.querySelector('[name="kategori_posyandu"]').value = item.kategori_posyandu || "";
        form.querySelector('[name="lokasi"]').value = item.lokasi || "";
        if (idInput) {
            idInput.value = item.id || "";
        }
        if (submitButton) {
            submitButton.innerHTML = '<i class="bi bi-check-lg me-1"></i>Perbarui Jadwal';
        }
        if (cancelEditButton) {
            cancelEditButton.classList.remove("d-none");
        }
        form.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    if (cancelEditButton) {
        cancelEditButton.addEventListener("click", () => {
            resetEditor();
        });
    }

    if (form) {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            hideAlert(alertBox);
            clearValidationErrors(form);

            const formData = new FormData(form);
            const payload = {
                id: formData.get("id")?.toString().trim() || "",
                tanggal_pelaksanaan: formData.get("tanggal_pelaksanaan")?.toString().trim() || "",
                waktu_mulai: formData.get("waktu_mulai")?.toString().trim() || "",
                waktu_selesai: formData.get("waktu_selesai")?.toString().trim() || "",
                kategori_posyandu: formData.get("kategori_posyandu")?.toString().trim() || "",
                lokasi: formData.get("lokasi")?.toString().trim() || "",
            };
            const requiredFields = [
                "tanggal_pelaksanaan",
                "waktu_mulai",
                "waktu_selesai",
                "kategori_posyandu",
                "lokasi",
            ];

            const missingField = requiredFields.find((field) => !payload[field]);
            if (missingField) {
                showAlert(alertBox, "Lengkapi semua field wajib terlebih dahulu.", "danger");
                return;
            }

            if (payload.waktu_mulai >= payload.waktu_selesai) {
                showAlert(alertBox, "Waktu selesai harus lebih besar dari waktu mulai.", "danger");
                return;
            }

            const editingId = idInput?.value || payload.id || "";
            try {
                await persistItem(payload, editingId);
                resetEditor();
                const items = await loadItems();
                render(items);
                showAlert(alertBox, editingId ? "Jadwal berhasil diperbarui." : "Jadwal berhasil ditambahkan.", "success");
            } catch (error) {
                const status = error?.status;
                if (status === 422 && error?.data?.errors) {
                    applyValidationErrors(form, error.data.errors);
                    showAlert(alertBox, "Periksa kembali field yang diisi.", "danger");
                    return;
                }

                showAlert(alertBox, error?.message || "Gagal menyimpan jadwal.", "danger");
            }
        });

        form.addEventListener("reset", () => {
            window.setTimeout(() => {
                resetEditor();
            }, 0);
        });
    }

    tableBody.addEventListener("click", async (event) => {
        const button = event.target.closest("[data-action]");
        if (!button) {
            return;
        }

        const action = button.getAttribute("data-action");
        const itemId = button.getAttribute("data-id");
        if (!itemId) {
            return;
        }

        if (action === "edit-jadwal") {
            startEdit(itemId);
            return;
        }

        if (action === "delete-jadwal") {
            if (!window.confirm("Hapus jadwal posyandu ini?")) {
                return;
            }
            try {
                const token = getToken();
                if (!token) {
                    const raw = JSON.parse(localStorage.getItem(APP_STORAGE.jadwalPosyandu) || "[]");
                    const items = Array.isArray(raw) ? raw.filter((it) => String(it.id) !== String(itemId)) : [];
                    localStorage.setItem(APP_STORAGE.jadwalPosyandu, JSON.stringify(items));
                    render(items);
                    showAlert(alertBox, "Jadwal berhasil dihapus.", "success");
                    return;
                }

                await apiRequest(`${endpoint}/${itemId}`, { method: "DELETE" });
                const items = await loadItems();
                render(items);
                showAlert(alertBox, "Jadwal berhasil dihapus.", "success");
            } catch (error) {
                showAlert(alertBox, error?.message || "Gagal menghapus jadwal.", "danger");
            }
        }
    });

    loadItems()
        .then((items) => render(items))
        .catch((error) => {
            showAlert(alertBox, error?.message || "Gagal memuat jadwal.", "danger");
            render([]);
        });
};

// Sync local jadwal-posyandu entries (created while unauthenticated) to server when user logs in
window.syncLocalJadwalToServer = async function () {
    const token = getToken();
    if (!token) return;

    let rawItems = [];
    try {
        rawItems = JSON.parse(localStorage.getItem(APP_STORAGE.jadwalPosyandu) || "[]");
    } catch (err) {
        rawItems = [];
    }

    const unsynced = (rawItems || []).filter((it) => String(it.id).startsWith("jadwal-"));
    if (!unsynced.length) return;
    // Keep a mapping from localId -> serverItem to update assignments
    const idMap = {};
    for (const item of unsynced) {
        try {
            const payload = {
                tanggal_pelaksanaan: item.tanggal_pelaksanaan,
                waktu_mulai: item.waktu_mulai,
                waktu_selesai: item.waktu_selesai,
                kategori_posyandu: item.kategori_posyandu,
                lokasi: item.lokasi,
            };
            const res = await apiRequest('/jadwal-posyandus', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const serverItem = res?.data || res;
            idMap[String(item.id)] = serverItem;
            // remove the local unsynced item and prepend the server item
            rawItems = rawItems.filter((it) => String(it.id) !== String(item.id));
            rawItems.unshift(serverItem);
        } catch (err) {
            // ignore individual failures and continue
        }
    }

    // Update any local assignments that referenced the unsynced jadwal ids
    try {
        const assignments = getJadwalAssignments();
        let changed = false;
        const nextAssignments = assignments.map((a) => {
            const jad = a.jadwal || {};
            const mapped = idMap[String(jad.id)];
            if (mapped) {
                changed = true;
                return { ...a, jadwal: mapped };
            }
            return a;
        });
        if (changed) {
            saveJadwalAssignments(nextAssignments);
        }
    } catch (err) {
        // ignore
    }

    try {
        localStorage.setItem(APP_STORAGE.jadwalPosyandu, JSON.stringify(rawItems));
    } catch (err) {
        // ignore storage errors
    }

    // Try to push any local assignments to server now that jadwals are synced
    try {
        const assignments = getJadwalAssignments();
        for (const a of assignments) {
            try {
                const wargaId = a.warga_id;
                const jad = a.jadwal || {};
                if (!wargaId) continue;
                // only attempt if jadwal has server id
                if (!jad || String(jad.id).startsWith('jadwal-')) continue;
                await apiRequest(`/wargas/${wargaId}/assign-jadwal`, {
                    method: 'POST',
                    body: JSON.stringify({ jadwal_id: jad.id, notes: a.notes || null }),
                });
                // remove local assignment on success
                removeJadwalAssignment(wargaId);
            } catch (errAssign) {
                // ignore individual failures
            }
        }
    } catch (err) {
        // ignore
    }
};

const initWargaIndex = () => {
    const tableBody = document.getElementById("wargaTableBody");
    if (!tableBody) {
        return;
    }
    const summary = document.getElementById("wargaSummary");
    const pagination = document.getElementById("wargaPagination");
    const perPageSelect = document.getElementById("wargaPerPage");
    const filterForm = document.getElementById("wargaFilterForm");
    const resetButton = document.getElementById("wargaResetFilter");
    const alertBox = document.getElementById("wargaAlert");

    const params = new URLSearchParams(window.location.search);

    if (filterForm) {
        filterForm.querySelectorAll("input, select").forEach((input) => {
            if (params.has(input.name)) {
                input.value = params.get(input.name);
            }
        });
        filterForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(filterForm);
            const newParams = new URLSearchParams();
            formData.forEach((value, key) => {
                if (value) {
                    newParams.set(key, value);
                }
            });
            if (perPageSelect?.value) {
                newParams.set("per_page", perPageSelect.value);
            }
            window.location.search = newParams.toString();
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            window.location.search = "";
        });
    }

    if (perPageSelect) {
        if (params.has("per_page")) {
            perPageSelect.value = params.get("per_page");
        }
        perPageSelect.addEventListener("change", () => {
            params.set("per_page", perPageSelect.value);
            params.delete("page");
            window.location.search = params.toString();
        });
    }

    const renderPagination = (meta) => {
        if (!pagination || !meta) {
            return;
        }
        pagination.innerHTML = "";
        const current = meta.current_page || 1;
        const last = meta.last_page || 1;
        const createItem = (label, page, disabled = false, active = false) => {
            const li = document.createElement("li");
            li.className = `page-item${disabled ? " disabled" : ""}${active ? " active" : ""}`;
            if (disabled || active) {
                const span = document.createElement("span");
                span.className = "page-link";
                span.textContent = label;
                li.appendChild(span);
            } else {
                const link = document.createElement("a");
                const pageParams = new URLSearchParams(window.location.search);
                pageParams.set("page", page);
                link.className = "page-link";
                link.href = `?${pageParams.toString()}`;
                link.textContent = label;
                li.appendChild(link);
            }
            return li;
        };

        pagination.appendChild(createItem("Prev", current - 1, current === 1));
        pagination.appendChild(
            createItem(String(current), current, false, true),
        );
        if (current < last) {
            pagination.appendChild(
                createItem(String(current + 1), current + 1),
            );
        }
        pagination.appendChild(
            createItem("Next", current + 1, current >= last),
        );
    };

    const renderRows = (items = []) => {
        if (!items.length) {
            tableBody.innerHTML =
                '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data warga.</td></tr>';
            return;
        }
        tableBody.innerHTML = items
            .map((item, index) => {
                const kategori = item.kategori || "-";
                const kategoriClass =
                    kategori === "balita"
                        ? "badge-balita"
                        : kategori === "ibu_hamil"
                          ? "badge-ibu"
                          : kategori === "lansia"
                            ? "badge-lansia"
                            : "badge-balita";
                const jk = item.jenis_kelamin || "-";
                const jkIcon =
                    jk === "L"
                        ? '<i class="bi bi-gender-male text-primary"></i>'
                        : '<i class="bi bi-gender-female text-danger"></i>';
                return `
                <tr>
                    <td>${index + 1}</td>
                    <td><code>${escapeHtml(item.nik || "-")}</code></td>
                    <td><strong>${escapeHtml(item.nama_lengkap || "-")}</strong></td>
                    <td><span class="badge-category ${kategoriClass}">${escapeHtml(kategori)}</span></td>
                    <td class="text-center">${jkIcon} ${escapeHtml(jk)}</td>
                    <td>${escapeHtml(calculateAge(item.tanggal_lahir))}</td>
                    <td class="text-center action-btns">
                            <a href="/admin/warga/${item.id}" class="btn btn-outline-info btn-sm" title="Detail"><i class="bi bi-eye"></i></a>
                                    ${(() => {
                                        const role = getUserRole();
                                        const canEdit = role === 'admin' || role === 'kader';
                                        const canDelete = role === 'admin';
                                        return `${canEdit ? `<a href="/admin/warga/${item.id}/edit" class="btn btn-outline-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>` : ''}
                                        ${canDelete ? `<button type="button" class="btn btn-outline-danger btn-sm" data-action="delete-warga" data-id="${item.id}" title="Hapus"><i class="bi bi-trash"></i></button>` : ''}`;
                                    })()}
                    </td>
                </tr>
            `;
            })
            .join("");

        tableBody
            .querySelectorAll("[data-action='delete-warga']")
            .forEach((button) => {
                button.addEventListener("click", async () => {
                    const id = button.getAttribute("data-id");
                    if (!id) {
                        return;
                    }
                    if (
                        !window.confirm("Yakin ingin menghapus data warga ini?")
                    ) {
                        return;
                    }
                    try {
                        await apiRequest(`/wargas/${id}`, { method: "DELETE" });
                        hideAlert(alertBox);
                        await loadWarga();
                    } catch (error) {
                        showAlert(
                            alertBox,
                            error?.data?.message ||
                                "Gagal menghapus data warga.",
                            "danger",
                        );
                    }
                });
            });
    };

    const loadWarga = async () => {
        try {
            hideAlert(alertBox);
            const response = await apiRequest(`/wargas?${params.toString()}`);
            const items = response?.data || [];
            renderRows(items);
            if (summary) {
                summary.textContent = `Menampilkan ${items.length} dari ${response?.total || 0} data`;
            }
            renderPagination(response);
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data warga.",
                "danger",
            );
        }
    };

    loadWarga();

    // Export handler (CSV)
    const exportBtn = document.getElementById('wargaExportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            setButtonLoading(exportBtn, true, 'Menyiapkan...');
            try {
                // Fetch first page to learn pagination
                const first = await apiRequest('/wargas');
                const items = first?.data || [];
                const lastPage = first?.last_page || 1;
                // Fetch remaining pages if any
                for (let p = 2; p <= lastPage; p++) {
                    try {
                        const pageResp = await apiRequest(`/wargas?page=${p}`);
                        const pageItems = pageResp?.data || [];
                        items.push(...pageItems);
                    } catch (errPage) {
                        // ignore individual page errors but continue
                    }
                }
                if (!items.length) {
                    showAlert(alertBox, 'Tidak ada data untuk diekspor.');
                    return;
                }
                const headers = ['nik','nama_lengkap','tanggal_lahir','jenis_kelamin','kategori','no_hp','alamat','rt_rw','created_at'];
                const csvRows = [headers.join(',')];
                items.forEach((it) => {
                    const row = headers.map((h) => {
                        let v = it[h] ?? '';
                        if (typeof v === 'string') {
                            v = v.replace(/"/g, '""');
                        }
                        return `"${v}"`;
                    });
                    csvRows.push(row.join(','));
                });
                const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                const now = new Date();
                const fname = `wargas-${now.toISOString().slice(0,10)}.csv`;
                a.download = fname;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            } catch (err) {
                showAlert(alertBox, err?.data?.message || 'Gagal mengekspor data warga.', 'danger');
            } finally {
                setButtonLoading(exportBtn, false);
            }
        });
    }
};

const initWargaForm = () => {
    const form = document.getElementById("wargaForm");
    if (!form) {
        return;
    }
    const mode = form.dataset.mode || "create";
    const alertBox = document.getElementById("wargaFormAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const id = parsePathId();

    const fillForm = (data) => {
        Object.entries(data || {}).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (!input || value === null || value === undefined) {
                return;
            }
            if (input.type === "radio") {
                const radio = form.querySelector(
                    `[name="${key}"][value="${value}"]`,
                );
                if (radio) {
                    radio.checked = true;
                }
                return;
            }
            input.value = value;
        });
    };

    if (mode === "edit" && id) {
        apiRequest(`/wargas/${id}`)
            .then((response) => {
                const item = response?.data || response;
                fillForm(item);
            })
            .catch((error) => {
                showAlert(
                    alertBox,
                    error?.data?.message || "Gagal memuat data warga.",
                );
            });
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);
        const payload = serializeForm(form);
        setButtonLoading(submitButton, true, "Menyimpan...");

        try {
            if (mode === "edit" && id) {
                await apiRequest(`/wargas/${id}`, {
                    method: "PUT",
                    body: JSON.stringify(payload),
                });
            } else {
                await apiRequest("/wargas", {
                    method: "POST",
                    body: JSON.stringify(payload),
                });
            }
            window.location.href = "/admin/warga";
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            showAlert(
                alertBox,
                error?.data?.message || "Gagal menyimpan data warga.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    });
};

const initWargaShow = () => {
    const container = document.getElementById("wargaDetail");
    if (!container) {
        return;
    }
    const id = parsePathId();
    const editLink = document.getElementById("wargaEditLink");
    const deleteLink = document.getElementById("wargaDeleteLink");
    const avatar = document.getElementById("wargaAvatar");
    const breadcrumbName = document.getElementById("wargaBreadcrumbName");
    const kategoriBadge = document.getElementById("wargaKategoriBadge");
    const genderBadge = document.getElementById("wargaGenderBadge");
    const umur = document.getElementById("wargaUmur");

    if (editLink && id) {
        const base = getUserRole() === 'kader' ? '/kader' : '/admin';
        editLink.href = `${base}/warga/${id}/edit`;
    }
    if (deleteLink && id) {
        deleteLink.addEventListener("click", async (event) => {
            event.preventDefault();
            if (!window.confirm("Yakin ingin menghapus data warga ini?")) {
                return;
            }
            try {
                await apiRequest(`/wargas/${id}`, { method: "DELETE" });
                window.location.href = "/admin/warga";
            } catch (error) {
                window.alert(
                    error?.data?.message || "Gagal menghapus data warga.",
                );
            }
        });
    }

    apiRequest(`/wargas/${id}`)
        .then((response) => {
            console.debug('warga show response:', response);
            const rawItem = response?.data || response;
            let item = rawItem;
            // If backend accidentally returned an array or a wrapped structure,
            // pick the first element when appropriate.
            if (Array.isArray(item)) {
                item = item[0] || null;
            }
            if (!item) {
                return;
            }

            
            document
                .querySelectorAll(
                    '#wargaDetail [data-field], #wargaInfoDetail [data-field]',
                )
                .forEach((el) => {
                const field = el.getAttribute("data-field");
                const value = item[field];
                if (
                    field === "tanggal_lahir" ||
                    field === "created_at" ||
                    field === "updated_at"
                ) {
                    el.textContent = formatDate(value);
                } else {
                    el.textContent = value ?? "-";
                }
                });
            if (avatar) {
                avatar.textContent = getInitials(item.nama_lengkap || "User");
            }
            if (breadcrumbName) {
                breadcrumbName.textContent = item.nama_lengkap || "Detail";
            }
            // Show assigned jadwal from server relation if available, otherwise fall back to localStorage
            try {
                const jadwalInfoEl = document.getElementById('wargaJadwalInfo');
                let assigned = null;
                if (item.jadwal_assignments && item.jadwal_assignments.length) {
                    assigned = item.jadwal_assignments[0]?.jadwal_posyandu || null;
                } else if (item.jadwalAssignments && item.jadwalAssignments.length) {
                    assigned = item.jadwalAssignments[0]?.jadwalPosyandu || null;
                }
                if (!assigned) {
                    assigned = getAssignmentForWarga(id);
                }
                if (jadwalInfoEl) {
                    if (assigned) {
                        jadwalInfoEl.innerHTML = `${escapeHtml(assigned.tanggal_pelaksanaan)} ${escapeHtml(assigned.waktu_mulai)}-${escapeHtml(assigned.waktu_selesai)} — ${escapeHtml(assigned.lokasi)} (${escapeHtml(assigned.kategori_posyandu || assigned.kategori || '-')})`;
                    } else {
                        jadwalInfoEl.textContent = '-';
                    }
                }
            } catch (err) {
                // ignore
            }
            if (kategoriBadge) {
                const kategori = item.kategori || "-";
                kategoriBadge.textContent = kategori;
                kategoriBadge.classList.remove(
                    "badge-balita",
                    "badge-ibu",
                    "badge-lansia",
                );
                if (kategori === "balita") {
                    kategoriBadge.classList.add("badge-balita");
                } else if (kategori === "ibu_hamil") {
                    kategoriBadge.classList.add("badge-ibu");
                } else if (kategori === "lansia") {
                    kategoriBadge.classList.add("badge-lansia");
                }
            }
            if (genderBadge) {
                const jk = item.jenis_kelamin || "-";
                genderBadge.innerHTML =
                    jk === "L"
                        ? '<i class="bi bi-gender-male text-primary me-1"></i>Laki-laki'
                        : jk === "P"
                          ? '<i class="bi bi-gender-female text-danger me-1"></i>Perempuan'
                          : "-";
            }
            if (umur) {
                umur.textContent = calculateAge(item.tanggal_lahir);
            }

            // Render penimbangan history into the warga detail table
            const penimbanganBody = document.getElementById("wargaPenimbanganBody");
            if (penimbanganBody) {
                const penItems = item.penimbangans || [];
                if (!penItems.length) {
                    penimbanganBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada data penimbangan.</td></tr>';
                } else {
                    penimbanganBody.innerHTML = penItems
                        .map((p, idx) => {
                            const petugas = p.kader?.nama_lengkap || p.kader?.nama || p.kader_id || "-";
                            return `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${escapeHtml(formatDate(p.tanggal))}</td>
                                <td>${escapeHtml(formatMaybeUnit(p.berat_badan, "kg"))}</td>
                                <td>${escapeHtml(formatMaybeUnit(p.tinggi_badan, "cm"))}</td>
                                <td>${escapeHtml(p.status_gizi || "-")}</td>
                                <td>${escapeHtml(petugas)}</td>
                            </tr>
                        `;
                        })
                        .join("");
                }
            }

            // Render imunisasi history into the warga detail table
            const imunisasiBody = document.getElementById("wargaImunisasiBody");
            if (imunisasiBody) {
                const imItems = item.imunisasis || [];
                if (!imItems.length) {
                    imunisasiBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data imunisasi.</td></tr>';
                } else {
                    imunisasiBody.innerHTML = imItems
                        .map((im, idx) => {
                            const petugas = im.kader?.nama_lengkap || im.kader?.nama || im.kader_id || "-";
                            return `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${escapeHtml(formatDate(im.tanggal_pemberian || im.tanggal || im.tanggal))}</td>
                                <td>${escapeHtml(im.jenis_imunisasi || im.jenis || "-")}</td>
                                <td>${escapeHtml(petugas)}</td>
                                <td>${escapeHtml(im.keterangan || im.catatan || "-")}</td>
                            </tr>
                        `;
                        })
                        .join("");
                }
            }
        })
        .catch((error) => {
            // If unauthorized, `apiRequest` already cleared auth and redirected.
            if (error?.status === 401) {
                return;
            }
            const msg = error?.data?.message || error?.message || "Gagal memuat detail warga.";
            console.error("Warga show error:", error);
            window.alert(msg);
        });
};

const initKaderIndex = () => {
    const tableBody = document.getElementById("kaderTableBody");
    if (!tableBody) {
        return;
    }
    const summary = document.getElementById("kaderSummary");
    const alertBox = document.getElementById("kaderAlert");

    const renderRows = (items = []) => {
        if (!items.length) {
            tableBody.innerHTML =
                '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kader.</td></tr>';
            return;
        }
        tableBody.innerHTML = items
            .map((item, index) => {
                return `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${escapeHtml(item.nama_lengkap || "-")}</strong></td>
                    <td>${escapeHtml(item.user_id ?? "-")}</td>
                    <td>${escapeHtml(item.no_hp || "-")}</td>
                    <td>${escapeHtml(item.wilayah || "-")}</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td class="text-center">
                        <a href="/admin/kader/${item.id}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="/admin/kader/${item.id}/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-outline-danger btn-sm" type="button" data-action="delete-kader" data-id="${item.id}"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
            })
            .join("");

        tableBody
            .querySelectorAll("[data-action='delete-kader']")
            .forEach((button) => {
                button.addEventListener("click", async () => {
                    const id = button.getAttribute("data-id");
                    if (!id) {
                        return;
                    }
                    if (
                        !window.confirm("Yakin ingin menghapus data kader ini?")
                    ) {
                        return;
                    }
                    try {
                        await apiRequest(`/kaders/${id}`, { method: "DELETE" });
                        await loadKader();
                    } catch (error) {
                        showAlert(
                            alertBox,
                            error?.data?.message ||
                                "Gagal menghapus data kader.",
                            "danger",
                        );
                    }
                });
            });
    };

    const loadKader = async () => {
        try {
            hideAlert(alertBox);
            const response = await apiRequest("/kaders");
            const items = response?.data || [];
            renderRows(items);
            if (summary) {
                summary.textContent = `Total ${response?.total || items.length} kader`;
            }
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data kader.",
                "danger",
            );
        }
    };

    loadKader();
};

const initKaderForm = () => {
    const form = document.getElementById("kaderForm");
    if (!form) {
        return;
    }
    const mode = form.dataset.mode || "create";
    const alertBox = document.getElementById("kaderFormAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const id = parsePathId();

    const fillForm = (data) => {
        Object.entries(data || {}).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (!input || value === null || value === undefined) {
                return;
            }
            input.value = value;
        });
    };

    if (mode === "edit" && id) {
        apiRequest(`/kaders/${id}`)
            .then((response) => {
                const item = response?.data || response;
                fillForm(item);
            })
            .catch((error) => {
                showAlert(
                    alertBox,
                    error?.data?.message || "Gagal memuat data kader.",
                );
            });
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);
        const payload = serializeForm(form);
        setButtonLoading(submitButton, true, "Menyimpan...");

        try {
            if (mode === "edit" && id) {
                await apiRequest(`/kaders/${id}`, {
                    method: "PUT",
                    body: JSON.stringify(payload),
                });
            } else {
                await apiRequest("/kaders/account", {
                    method: "POST",
                    body: JSON.stringify(payload),
                });
            }
            window.location.href = "/admin/kader";
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            showAlert(
                alertBox,
                error?.data?.message || "Gagal menyimpan data kader.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    });
};

const initKaderShow = () => {
    const container = document.getElementById("kaderDetail");
    if (!container) {
        return;
    }
    const id = parsePathId();
    const editLink = document.getElementById("kaderEditLink");
    const deleteLink = document.getElementById("kaderDeleteLink");
    const avatar = document.getElementById("kaderAvatar");
    const breadcrumbName = document.getElementById("kaderBreadcrumbName");

    if (editLink && id) {
        editLink.href = `/admin/kader/${id}/edit`;
    }
    if (deleteLink && id) {
        deleteLink.addEventListener("click", async (event) => {
            event.preventDefault();
            if (!window.confirm("Yakin ingin menghapus data kader ini?")) {
                return;
            }
            try {
                await apiRequest(`/kaders/${id}`, { method: "DELETE" });
                window.location.href = "/admin/kader";
            } catch (error) {
                window.alert(
                    error?.data?.message || "Gagal menghapus data kader.",
                );
            }
        });
    }

    apiRequest(`/kaders/${id}`)
        .then((response) => {
            const item = response?.data || response;
            if (!item) {
                return;
            }
            document
                .querySelectorAll(
                    '#kaderDetail [data-field], #kaderInfoDetail [data-field]',
                )
                .forEach((el) => {
                const field = el.getAttribute("data-field");
                const value = item[field];
                if (field === "created_at" || field === "updated_at") {
                    el.textContent = formatDate(value);
                } else {
                    el.textContent = value ?? "-";
                }
                });
            if (avatar) {
                avatar.textContent = getInitials(item.nama_lengkap || "User");
            }
            if (breadcrumbName) {
                breadcrumbName.textContent = item.nama_lengkap || "Detail";
            }
        })
        .catch(() => {
            window.alert("Gagal memuat detail kader.");
        });
};

const buildSelectOptions = (items, labelGetter) => {
    return items
        .map((item) => {
            return `<option value="${item.id}">${escapeHtml(labelGetter(item))}</option>`;
        })
        .join("");
};

const initPenimbanganIndex = () => {
    const tableBody = document.getElementById("penimbanganTableBody");
    if (!tableBody) {
        return;
    }
    const totalBulanIniEl = document.getElementById("penimbanganTotalBulanIni");
    const statusBaikEl = document.getElementById("penimbanganStatusBaik");
    const statusKurangEl = document.getElementById("penimbanganStatusKurang");
    const statusBurukEl = document.getElementById("penimbanganStatusBuruk");
    const summary = document.getElementById("penimbanganSummary");
    const alertBox = document.getElementById("penimbanganAlert");
    const filterForm = document.getElementById("penimbanganFilterForm");
    const resetButton = document.getElementById("penimbanganResetFilter");
    const params = new URLSearchParams(window.location.search);

    if (filterForm) {
        filterForm.querySelectorAll("input, select").forEach((input) => {
            if (params.has(input.name)) {
                input.value = params.get(input.name);
            }
        });
        filterForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(filterForm);
            const newParams = new URLSearchParams();
            formData.forEach((value, key) => {
                if (value) {
                    newParams.set(key, value);
                }
            });
            window.location.search = newParams.toString();
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            window.location.search = "";
        });
    }

    const getRelationName = (value, fallback = "-") => {
        if (!value) {
            return fallback;
        }
        if (typeof value === "string") {
            return value;
        }
        return value.nama_lengkap || value.name || value.nama || fallback;
    };

    const getCategoryMeasurement = (item) => {
        const kategori = item.warga?.kategori || item.kategori || "";
        if (kategori === "balita") {
            return formatMaybeUnit(item.lingkar_kepala, "cm");
        }
        return "-";
    };

    

    

    const getCategoryNote = (item) => {
        const kategori = item.warga?.kategori || item.kategori || "";
        if (kategori === "balita") {
            return item.catatan || "-";
        }
        return item.catatan || "-";
    };

    const getYearMonth = (dateValue) => {
        if (!dateValue) {
            return null;
        }
        const raw = String(dateValue).slice(0, 10);
        const parts = raw.split("-");
        if (parts.length !== 3) {
            return null;
        }
        const year = Number(parts[0]);
        const month = Number(parts[1]);
        if (!Number.isInteger(year) || !Number.isInteger(month)) {
            return null;
        }
        return `${year}-${String(month).padStart(2, "0")}`;
    };

    const renderStats = (items = []) => {
        const now = new Date();
        const currentYearMonth = `${now.getFullYear()}-${String(
            now.getMonth() + 1,
        ).padStart(2, "0")}`;

        const stats = items.reduce(
            (acc, item) => {
                const itemYearMonth = getYearMonth(item.tanggal);
                if (itemYearMonth === currentYearMonth) {
                    acc.totalBulanIni += 1;
                }

                const status = String(item.status_gizi || "")
                    .trim()
                    .toLowerCase();
                if (status === "baik") {
                    acc.baik += 1;
                } else if (status === "kurang") {
                    acc.kurang += 1;
                } else if (status === "buruk") {
                    acc.buruk += 1;
                }
                return acc;
            },
            { totalBulanIni: 0, baik: 0, kurang: 0, buruk: 0 },
        );

        if (totalBulanIniEl) {
            totalBulanIniEl.textContent = String(stats.totalBulanIni);
        }
        if (statusBaikEl) {
            statusBaikEl.textContent = String(stats.baik);
        }
        if (statusKurangEl) {
            statusKurangEl.textContent = String(stats.kurang);
        }
        if (statusBurukEl) {
            statusBurukEl.textContent = String(stats.buruk);
        }
    };

    const renderRows = (items = []) => {
        if (!items.length) {
            tableBody.innerHTML =
                '<tr><td colspan="16" class="text-center text-muted py-4">Belum ada data penimbangan.</td></tr>';
            return;
        }
        tableBody.innerHTML = items
            .map((item, index) => {
                const wargaValue = item.warga || item.warga_data || item.warga_nama || item.warga_name || null;
                const kaderValue = item.kader || item.kader_data || item.kader_nama || item.kader_name || item.petugas || null;
                const kategori = item.warga?.kategori || item.kategori || "-";
                const kategoriClass =
                    kategori === "balita"
                        ? "badge-balita"
                        : kategori === "ibu_hamil"
                          ? "badge-ibu"
                          : kategori === "lansia"
                            ? "badge-lansia"
                            : "badge-balita";
                const status = formatDisplayValue(item.status_gizi);
                const statusClass =
                    status === "baik"
                        ? "bg-success"
                        : status === "kurang"
                          ? "bg-warning text-dark"
                          : status === "buruk"
                            ? "bg-danger"
                            : "bg-secondary";
                const beratBadan = item.berat_badan ?? item.berat ?? item.berat_value;
                const tinggiBadan = item.tinggi_badan ?? item.tinggi ?? item.tinggi_value;
                const lingkarKepala = item.lingkar_kepala ?? item.kepala;
                const tekanan = item.tekanan_darah ?? item.tekanan ?? "-";
                const lengan = item.lingkar_lengan_atas ?? item.lila;
                const perut = item.lingkar_perut ?? item.perut;
                const kol = item.kolesterol;
                const urat = item.asam_urat;
                const note = item.catatan ?? item.keterangan ?? "-";

                return `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(formatDate(item.tanggal))}</td>
                    <td><strong>${escapeHtml(getRelationName(wargaValue, item.warga_nama || item.warga_id || "-"))}</strong></td>
                    <td><span class="badge-category ${kategoriClass}">${escapeHtml(kategori)}</span></td>
                    <td><strong>${escapeHtml(formatMaybeUnit(beratBadan, "kg"))}</strong></td>
                    <td>${escapeHtml(formatMaybeUnit(tinggiBadan, "cm"))}</td>
                    <td>${escapeHtml(formatMaybeUnit(lingkarKepala, "cm"))}</td>
                    <td>${escapeHtml(formatDisplayValue(tekanan))}</td>
                    <td>${escapeHtml(formatDisplayValue(lengan))}</td>
                    <td>${escapeHtml(formatDisplayValue(perut))}</td>
                    <td>${escapeHtml(formatDisplayValue(kol))}</td>
                    <td>${escapeHtml(formatDisplayValue(urat))}</td>
                    <td>${escapeHtml(formatDisplayValue(note))}</td>
                    <td><span class="badge ${statusClass}">${escapeHtml(status)}</span></td>
                    <td>${escapeHtml(getRelationName(kaderValue, item.kader_nama || item.kader_id || "-"))}</td>
                    <td class="text-center">
                                ${(() => {
                                    const base = getUserRole() === 'kader' ? '/kader' : '/admin';
                                    return `
                                <a href="${base}/penimbangan/${item.id}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                                <a href="${base}/penimbangan/${item.id}/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                `;
                                })()}
                        <button class="btn btn-outline-danger btn-sm" type="button" data-action="delete-penimbangan" data-id="${item.id}"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
            })
            .join("");

        tableBody
            .querySelectorAll("[data-action='delete-penimbangan']")
            .forEach((button) => {
                button.addEventListener("click", async () => {
                    const id = button.getAttribute("data-id");
                    if (!id) {
                        return;
                    }
                    if (
                        !window.confirm(
                            "Yakin ingin menghapus data penimbangan ini?",
                        )
                    ) {
                        return;
                    }
                    try {
                        await apiRequest(`/penimbangans/${id}`, {
                            method: "DELETE",
                        });
                        await loadPenimbangan();
                    } catch (error) {
                        showAlert(
                            alertBox,
                            error?.data?.message ||
                                "Gagal menghapus data penimbangan.",
                            "danger",
                        );
                    }
                });
            });
    };

    const loadPenimbangan = async () => {
        try {
            hideAlert(alertBox);
            const response = await apiRequest(
                `/penimbangans?${params.toString()}`,
            );
            const items = response?.data || [];
            renderRows(items);
            renderStats(items);
            if (summary) {
                summary.textContent = `Menampilkan ${items.length} dari ${response?.total || 0} data`;
            }
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data penimbangan.",
                "danger",
            );
        }
    };

    loadPenimbangan();
};

const initPenimbanganForm = () => {
    const form = document.getElementById("penimbanganForm");
    if (!form) {
        return;
    }
    const mode = form.dataset.mode || "create";
    const alertBox = document.getElementById("penimbanganFormAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const wargaSelect = document.getElementById("penimbanganWargaSelect");
    const kaderSelect = document.getElementById("penimbanganKaderSelect");
    const preview = document.getElementById("penimbanganWargaPreview");
    const statusGiziInput = form.querySelector('[name="status_gizi"]');
    const id = parsePathId();
    let wargaList = [];

    const toNumber = (value) => {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : null;
    };

    const parseBloodPressure = (value) => {
        if (!value) {
            return null;
        }
        const match = String(value).trim().match(/^(\d{2,3})\s*[\/,\-]\s*(\d{2,3})$/);
        if (!match) {
            return null;
        }
        return {
            systolic: Number(match[1]),
            diastolic: Number(match[2]),
        };
    };

    const calculateBmi = (beratBadan, tinggiBadan) => {
        if (!beratBadan || !tinggiBadan || tinggiBadan <= 0) {
            return null;
        }
        const tinggiMeter = tinggiBadan / 100;
        if (tinggiMeter <= 0) {
            return null;
        }
        return beratBadan / (tinggiMeter * tinggiMeter);
    };

    const calculateAgeInMonths = (warga, tanggal) => {
        if (!warga?.tanggal_lahir || !tanggal) {
            return null;
        }
        const birthDate = new Date(warga.tanggal_lahir);
        const checkDate = new Date(tanggal);
        if (Number.isNaN(birthDate.getTime()) || Number.isNaN(checkDate.getTime()) || checkDate < birthDate) {
            return null;
        }
        return (checkDate.getFullYear() - birthDate.getFullYear()) * 12 + (checkDate.getMonth() - birthDate.getMonth());
    };

    const determineStatusGizi = (warga, values) => {
        if (!warga) {
            return "";
        }

        const bmi = calculateBmi(values.berat_badan, values.tinggi_badan);
        if (bmi === null) {
            return "";
        }

        const kategori = (warga.kategori || "").toLowerCase();
        const lingkarKepala = values.lingkar_kepala;
        const lila = values.lingkar_lengan_atas;
        const lingkarPerut = values.lingkar_perut;
        const kolesterol = values.kolesterol;
        const asamUrat = values.asam_urat;
        const tekananDarah = parseBloodPressure(values.tekanan_darah);
        const jenisKelamin = (warga.jenis_kelamin || "").toUpperCase();
        const usiaBulan = calculateAgeInMonths(warga, form.querySelector('[name="tanggal"]')?.value);

        if (kategori === "balita") {
            let rentangKepala = null;
            if (lingkarKepala !== null) {
                if (usiaBulan !== null && usiaBulan <= 6) {
                    rentangKepala = [34, 43];
                } else if (usiaBulan !== null && usiaBulan <= 12) {
                    rentangKepala = [40, 46.5];
                } else if (usiaBulan !== null && usiaBulan <= 24) {
                    rentangKepala = [43.5, 49.5];
                } else {
                    rentangKepala = [45, 52.5];
                }
            }
            const kepalaBuruk = rentangKepala && (lingkarKepala < rentangKepala[0] - 1.5 || lingkarKepala > rentangKepala[1] + 1.5);
            const kepalaKurang = rentangKepala && !kepalaBuruk && (lingkarKepala < rentangKepala[0] || lingkarKepala > rentangKepala[1]);

            if (bmi < 13.5 || bmi > 18.5 || kepalaBuruk) {
                return "buruk";
            }
            if (bmi < 15.5 || bmi > 17.5 || kepalaKurang) {
                return "kurang";
            }
            return "baik";
        }

        if (kategori === "ibu_hamil") {
            if (bmi < 18.5 || (lila !== null && lila < 23) || (tekananDarah && (tekananDarah.systolic >= 140 || tekananDarah.diastolic >= 90))) {
                return "buruk";
            }
            if (bmi >= 25 || (lila !== null && lila < 23.5) || (tekananDarah && (tekananDarah.systolic >= 130 || tekananDarah.diastolic >= 85))) {
                return "kurang";
            }
            return "baik";
        }

        if (kategori === "lansia") {
            const batasLingkarPerut = jenisKelamin === "P" ? 80 : 90;
            const batasAsamUrat = jenisKelamin === "P" ? 6 : 7;

            if (bmi < 18.5 || bmi >= 30) {
                return "buruk";
            }
            if (
                bmi < 22 ||
                bmi > 27 ||
                (lingkarPerut !== null && lingkarPerut > batasLingkarPerut + 10) ||
                (kolesterol !== null && kolesterol >= 240) ||
                (asamUrat !== null && asamUrat > batasAsamUrat + 1) ||
                (tekananDarah && (tekananDarah.systolic >= 140 || tekananDarah.diastolic >= 90))
            ) {
                return "buruk";
            }
            if (
                bmi < 22 ||
                bmi > 25.5 ||
                (lingkarPerut !== null && lingkarPerut > batasLingkarPerut) ||
                (kolesterol !== null && kolesterol >= 200) ||
                (asamUrat !== null && asamUrat > batasAsamUrat) ||
                (tekananDarah && (tekananDarah.systolic >= 130 || tekananDarah.diastolic >= 85))
            ) {
                return "kurang";
            }
            return "baik";
        }

        if (bmi < 18.5 || bmi >= 30) {
            return "buruk";
        }
        if (bmi >= 25) {
            return "kurang";
        }
        return "baik";
    };

    const syncStatusGizi = () => {
        if (!statusGiziInput || !wargaSelect) {
            return;
        }
        const selected = wargaList.find((warga) => String(warga.id) === String(wargaSelect.value));
        const values = {
            berat_badan: toNumber(form.querySelector('[name="berat_badan"]')?.value),
            tinggi_badan: toNumber(form.querySelector('[name="tinggi_badan"]')?.value),
            lingkar_kepala: toNumber(form.querySelector('[name="lingkar_kepala"]')?.value),
            tekanan_darah: form.querySelector('[name="tekanan_darah"]')?.value || "",
            lingkar_lengan_atas: toNumber(form.querySelector('[name="lingkar_lengan_atas"]')?.value),
            lingkar_perut: toNumber(form.querySelector('[name="lingkar_perut"]')?.value),
            kolesterol: toNumber(form.querySelector('[name="kolesterol"]')?.value),
            asam_urat: toNumber(form.querySelector('[name="asam_urat"]')?.value),
        };
        statusGiziInput.value = determineStatusGizi(selected, values);
    };

    const updatePreview = () => {
        if (!preview || !wargaSelect) {
            return;
        }
        const selected = wargaList.find(
            (warga) => String(warga.id) === String(wargaSelect.value),
        );
        if (!selected) {
            preview.innerHTML =
                '<div class="text-muted small">Pilih warga untuk melihat ringkasan.</div>';
            return;
        }
        preview.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div class="profile-avatar" style="width:48px;height:48px;font-size:1rem;">${getInitials(
                    selected.nama_lengkap || "-",
                )}</div>
                <div>
                    <div class="fw-bold">${escapeHtml(selected.nama_lengkap || "-")}</div>
                    <div class="small text-muted">NIK: ${escapeHtml(selected.nik || "-")} &middot; ${escapeHtml(
                        selected.kategori || "-",
                    )}</div>
                </div>
            </div>
        `;
        const lingkarGroup = document.getElementById("penimbanganLingkarGroup");
        const tekananGroup = document.getElementById("penimbanganTekananGroup");
        const lenganGroup = document.getElementById("penimbanganLenganGroup");
        const perutGroup = document.getElementById("penimbanganPerutGroup");
        const kolesterolGroup = document.getElementById("penimbanganKolesterolGroup");
        const asamUratGroup = document.getElementById("penimbanganAsamUratGroup");
        const kategori = (selected.kategori || "").toLowerCase();
        if (lingkarGroup) {
            lingkarGroup.style.display = kategori === "balita" ? "" : "none";
        }
        if (tekananGroup) {
            tekananGroup.style.display = kategori === "ibu_hamil" || kategori === "lansia" ? "" : "none";
        }
        if (lenganGroup) {
            lenganGroup.style.display = kategori === "ibu_hamil" || kategori === "lansia" ? "" : "none";
        }
        if (perutGroup) {
            perutGroup.style.display = kategori === "lansia" ? "" : "none";
        }
        if (kolesterolGroup) {
            kolesterolGroup.style.display = kategori === "lansia" ? "" : "none";
        }
        if (asamUratGroup) {
            asamUratGroup.style.display = kategori === "lansia" ? "" : "none";
        }
        syncStatusGizi();
    };

    const loadOptions = async () => {
        try {
            const [wargaResponse, kaderResponse] = await Promise.all([
                apiRequest("/wargas?per_page=1000"),
                apiRequest("/kaders?per_page=1000"),
            ]);
            wargaList = wargaResponse?.data || [];
            if (wargaSelect) {
                wargaSelect.innerHTML =
                    '<option value="">Pilih warga...</option>' +
                    buildSelectOptions(wargaList, (warga) => {
                        return `${warga.nik || ""} - ${warga.nama_lengkap || ""}`;
                    });
                wargaSelect.addEventListener("change", updatePreview);
            }
            const kaderList = kaderResponse?.data || [];
            if (kaderSelect) {
                kaderSelect.innerHTML =
                    '<option value="">Pilih kader...</option>' +
                    buildSelectOptions(kaderList, (kader) => {
                        return `${kader.nama_lengkap || ""} (${kader.wilayah || "-"})`;
                    });
            }
            ["berat_badan", "tinggi_badan", "lingkar_kepala", "tekanan_darah", "lingkar_lengan_atas", "lingkar_perut", "kolesterol", "asam_urat"].forEach((fieldName) => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    input.addEventListener("input", syncStatusGizi);
                    input.addEventListener("change", syncStatusGizi);
                }
            });
            updatePreview();
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data warga/kader.",
            );
        }
    };

    const fillForm = (data) => {
        Object.entries(data || {}).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (!input || value === null || value === undefined) {
                return;
            }
            if (input.type === "date") {
                input.value = toInputDate(value);
                return;
            }
            input.value = value;
        });
        syncStatusGizi();
    };

    const loadExisting = async () => {
        if (mode !== "edit" || !id) {
            return;
        }
        try {
            const response = await apiRequest(`/penimbangans/${id}`);
            const item = response?.data || response;
            fillForm(item);
            if (wargaSelect) {
                wargaSelect.value = item?.warga_id || "";
            }
            if (kaderSelect) {
                kaderSelect.value = item?.kader_id || "";
            }
            updatePreview();
            syncStatusGizi();
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data penimbangan.",
            );
        }
    };

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);
        syncStatusGizi();
        const payload = serializeForm(form);
        setButtonLoading(submitButton, true, "Menyimpan...");
        try {
            if (mode === "edit" && id) {
                await apiRequest(`/penimbangans/${id}`, {
                    method: "PUT",
                    body: JSON.stringify(payload),
                });
            } else {
                await apiRequest("/penimbangans", {
                    method: "POST",
                    body: JSON.stringify(payload),
                });
            }
            const base = getUserRole() === 'kader' ? '/kader' : '/admin';
            window.location.href = `${base}/penimbangan`;
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            showAlert(
                alertBox,
                error?.data?.message || "Gagal menyimpan data penimbangan.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    });

    loadOptions().then(loadExisting);
};

const initPenimbanganShow = () => {
    const container = document.getElementById("penimbanganDetail");
    if (!container) {
        return;
    }
    const id = parsePathId();
    const editLink = document.getElementById("penimbanganEditLink");
    const deleteLink = document.getElementById("penimbanganDeleteLink");
    const breadcrumb = document.getElementById("penimbanganBreadcrumb");

    if (editLink && id) {
        const base = getUserRole() === 'kader' ? '/kader' : '/admin';
        editLink.href = `${base}/penimbangan/${id}/edit`;
    }
    if (deleteLink && id) {
        deleteLink.addEventListener("click", async (event) => {
            event.preventDefault();
            if (
                !window.confirm("Yakin ingin menghapus data penimbangan ini?")
            ) {
                return;
            }
            try {
                await apiRequest(`/penimbangans/${id}`, { method: "DELETE" });
            const base = getUserRole() === 'kader' ? '/kader' : '/admin';
            window.location.href = `${base}/penimbangan`;
            } catch (error) {
                window.alert(
                    error?.data?.message || "Gagal menghapus data penimbangan.",
                );
            }
        });
    }

    apiRequest(`/penimbangans/${id}`)
        .then((response) => {
            const item = response?.data || response;
            if (!item) {
                return;
            }
            if (breadcrumb) {
                breadcrumb.textContent = `Penimbangan ${formatDate(item.tanggal)}`;
            }
            container.querySelectorAll("[data-field]").forEach((el) => {
                const field = el.getAttribute("data-field");
                let value = item[field];

                // Normalize common fields for the detail view. The Blade templates
                // already include unit labels (e.g. "kg", "cm"), so avoid adding
                // units here — only provide raw numbers or fallback strings.
                if (field === "tanggal") {
                    value = formatDate(item.tanggal);
                }
                if (field === "warga_nama") {
                    value =
                        item.warga?.nama_lengkap ||
                        item.warga?.nama ||
                        item.warga_id ||
                        "-";
                }
                if (field === "kader_nama") {
                    value =
                        item.kader?.nama_lengkap ||
                        item.kader?.nama ||
                        item.kader_id ||
                        "-";
                }
                if (field === "berat_badan") {
                    const raw = item.berat_badan;
                    let bb = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') bb = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) bb = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = bb !== null && Number.isFinite(bb) ? bb.toFixed(2) : "-";
                }
                if (field === "tinggi_badan") {
                    const raw = item.tinggi_badan;
                    let tb = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') tb = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) tb = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = tb !== null && Number.isFinite(tb) ? tb.toFixed(2) : "-";
                }

                const kategori = item.warga?.kategori || item.kategori || "";
                if (field === "lingkar_kepala") {
                    const raw = item.lingkar_kepala;
                    let lk = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') lk = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) lk = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = (kategori === "balita" && lk !== null && Number.isFinite(lk)) ? lk : "-";
                }
                if (field === "tekanan_darah") {
                    value = (kategori === "ibu_hamil" || kategori === "lansia") ? (item.tekanan_darah || "-") : "-";
                }
                if (field === "lingkar_lengan_atas") {
                    const raw = item.lingkar_lengan_atas;
                    let lla = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') lla = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) lla = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = (kategori === "ibu_hamil" || kategori === "lansia") ? (lla !== null && Number.isFinite(lla) ? lla : "-") : "-";
                }
                if (field === "lingkar_perut") {
                    const raw = item.lingkar_perut;
                    let lp = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') lp = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) lp = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = (kategori === "lansia") ? (lp !== null && Number.isFinite(lp) ? lp : "-") : "-";
                }
                if (field === "kolesterol") {
                    const raw = item.kolesterol;
                    let kol = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') kol = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) kol = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = (kategori === "lansia") ? (kol !== null && Number.isFinite(kol) ? kol : "-") : "-";
                }
                if (field === "asam_urat") {
                    const raw = item.asam_urat;
                    let urat = null;
                    if (raw !== null && raw !== undefined) {
                        if (typeof raw === 'number') urat = raw;
                        else {
                            const m = String(raw).match(/-?\d+[\d\.,]*/);
                            if (m) urat = parseFloat(m[0].replace(/,/g, '.'));
                        }
                    }
                    value = (kategori === "lansia") ? (urat !== null && Number.isFinite(urat) ? urat : "-") : "-";
                }
                if (field === "status_gizi") {
                    value = item.status_gizi || "-";
                }
                if (field === "catatan") {
                    value = item.catatan || "-";
                }

                el.textContent = value ?? "-";
            });
        })
        .catch((error) => {
            const msg = error?.data?.message || error?.message || "Gagal memuat detail penimbangan.";
            window.alert(msg);
            console.error("Penimbangan show error:", error);
        });
};

const initImunisasiIndex = () => {
    const tableBody = document.getElementById("imunisasiTableBody");
    if (!tableBody) {
        return;
    }
    const summary = document.getElementById("imunisasiSummary");
    const alertBox = document.getElementById("imunisasiAlert");
    const filterForm = document.getElementById("imunisasiFilterForm");
    const resetButton = document.getElementById("imunisasiResetFilter");
    const params = new URLSearchParams(window.location.search);

    if (filterForm) {
        filterForm.querySelectorAll("input, select").forEach((input) => {
            if (params.has(input.name)) {
                input.value = params.get(input.name);
            }
        });
        filterForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(filterForm);
            const newParams = new URLSearchParams();
            formData.forEach((value, key) => {
                if (value) {
                    newParams.set(key, value);
                }
            });
            window.location.search = newParams.toString();
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            window.location.search = "";
        });
    }

    const getRelationName = (value, fallback = "-") => {
        if (!value) {
            return fallback;
        }
        if (typeof value === "string") {
            return value;
        }
        return value.nama_lengkap || value.nama || value.name || fallback;
    };

    const renderRows = (items = []) => {
        if (!items.length) {
            tableBody.innerHTML =
                '<tr><td colspan="8" class="text-center text-muted py-4">Belum ada data imunisasi.</td></tr>';
            return;
        }
        tableBody.innerHTML = items
            .map((item, index) => {
                const kategori =
                    item.kategori_warga ||
                    item.warga?.kategori ||
                    item.kategori ||
                    "-";
                const kategoriClass =
                    kategori === "balita"
                        ? "badge-balita"
                        : kategori === "ibu_hamil"
                          ? "badge-ibu"
                          : kategori === "lansia"
                            ? "badge-lansia"
                            : "badge-balita";
                return `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(formatDate(item.tanggal_pemberian))}</td>
                    <td><strong>${escapeHtml(getRelationName(item.warga, item.warga_nama || item.warga_id || "-"))}</strong></td>
                    <td><span class="badge-category ${kategoriClass}">${escapeHtml(kategori)}</span></td>
                    <td><span class="badge bg-info text-dark">${escapeHtml(item.jenis_imunisasi || "-")}</span></td>
                    <td>${escapeHtml(getRelationName(item.kader, item.kader_nama || item.kader_id || "-"))}</td>
                    <td>${escapeHtml(item.keterangan || "-")}</td>
                    <td class="text-center">
                        ${(() => {
                            const base = getUserRole() === 'kader' ? '/kader' : '/admin';
                            return `
                        <a href="${base}/imunisasi/${item.id}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="${base}/imunisasi/${item.id}/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        `;
                        })()}
                        <button class="btn btn-outline-danger btn-sm" type="button" data-action="delete-imunisasi" data-id="${item.id}"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
            })
            .join("");

        tableBody
            .querySelectorAll("[data-action='delete-imunisasi']")
            .forEach((button) => {
                button.addEventListener("click", async () => {
                    const id = button.getAttribute("data-id");
                    if (!id) {
                        return;
                    }
                    if (
                        !window.confirm(
                            "Yakin ingin menghapus data imunisasi ini?",
                        )
                    ) {
                        return;
                    }
                    try {
                        await apiRequest(`/imunisasis/${id}`, {
                            method: "DELETE",
                        });
                        await loadImunisasi();
                    } catch (error) {
                        showAlert(
                            alertBox,
                            error?.data?.message ||
                                "Gagal menghapus data imunisasi.",
                            "danger",
                        );
                    }
                });
            });
    };

    const loadImunisasi = async () => {
        try {
            hideAlert(alertBox);
            const response = await apiRequest(
                `/imunisasis?${params.toString()}`,
            );
            const items = response?.data || [];
            renderRows(items);
            if (summary) {
                summary.textContent = `Menampilkan ${items.length} dari ${response?.total || 0} data`;
            }
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data imunisasi.",
                "danger",
            );
        }
    };

    loadImunisasi();
};

const initImunisasiForm = () => {
    const form = document.getElementById("imunisasiForm");
    if (!form) {
        return;
    }
    const mode = form.dataset.mode || "create";
    const alertBox = document.getElementById("imunisasiFormAlert");
    const submitButton = form.querySelector("button[type=" + '"submit"' + "]");
    const wargaSelect = document.getElementById("imunisasiWargaSelect");
    const kaderSelect = document.getElementById("imunisasiKaderSelect");
    const preview = document.getElementById("imunisasiWargaPreview");
    const id = parsePathId();
    let wargaList = [];
    let kaderList = [];

    const syncVaksinSelection = () => {
        document.querySelectorAll(".vaksin-item").forEach((item) => {
            const input = item.querySelector("input[type='radio']");
            if (input?.checked) {
                item.classList.add("selected");
            } else {
                item.classList.remove("selected");
            }
        });
    };

    const updatePreview = () => {
        if (!preview || !wargaSelect) {
            return;
        }
        const selected = wargaList.find(
            (warga) => String(warga.id) === String(wargaSelect.value),
        );
        if (!selected) {
            preview.innerHTML =
                '<div class="text-muted small">Pilih warga untuk melihat ringkasan.</div>';
            return;
        }
        preview.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div class="profile-avatar" style="width:48px;height:48px;font-size:1rem;">${getInitials(
                    selected.nama_lengkap || "-",
                )}</div>
                <div>
                    <div class="fw-bold">${escapeHtml(selected.nama_lengkap || "-")}</div>
                    <div class="small text-muted">NIK: ${escapeHtml(selected.nik || "-")} &middot; ${escapeHtml(
                        selected.kategori || "-",
                    )}</div>
                    <div class="small text-muted">Orang Tua/Wali: ${escapeHtml(
                        selected.nama_orang_tua || "-",
                    )}</div>
                </div>
            </div>
        `;
    };

    const loadOptions = async () => {
        try {
            const [wargaResponse, kaderResponse] = await Promise.all([
                apiRequest("/wargas?per_page=1000"),
                apiRequest("/kaders?per_page=1000"),
            ]);
            wargaList = wargaResponse?.data || [];
            if (wargaSelect) {
                wargaSelect.innerHTML =
                    '<option value="">Pilih warga...</option>' +
                    buildSelectOptions(wargaList, (warga) => {
                        return `${warga.nik || ""} - ${warga.nama_lengkap || ""}`;
                    });
                wargaSelect.addEventListener("change", updatePreview);
            }
            kaderList = kaderResponse?.data || [];
            if (kaderSelect) {
                kaderSelect.innerHTML =
                    '<option value="">Pilih kader...</option>' +
                    buildSelectOptions(kaderList, (kader) => {
                        return `${kader.nama_lengkap || ""} (${kader.wilayah || "-"})`;
                    });
            }
            updatePreview();
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data warga/kader.",
            );
        }
    };

    const fillForm = (data) => {
        Object.entries(data || {}).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (!input || value === null || value === undefined) {
                return;
            }
            if (input.type === "date") {
                input.value = toInputDate(value);
                return;
            }
            if (input.type === "radio") {
                const radio = form.querySelector(
                    `[name="${key}"][value="${value}"]`,
                );
                if (radio) {
                    radio.checked = true;
                }
                return;
            }
            input.value = value;
        });
        syncVaksinSelection();
    };

    const loadExisting = async () => {
        if (mode !== "edit" || !id) {
            return;
        }
        try {
            const response = await apiRequest(`/imunisasis/${id}`);
            const payload = response?.data || response;
            const item = payload?.data || payload;
            fillForm(item);
            if (wargaSelect) {
                const wargaName =
                    item?.warga?.nama_lengkap || item?.warga?.nama || item?.warga;
                const matchedWarga = wargaList.find(
                    (warga) =>
                        String(warga.id) === String(item?.warga_id || "") ||
                        (wargaName && warga.nama_lengkap === wargaName),
                );
                wargaSelect.value = matchedWarga?.id || item?.warga_id || "";
            }
            if (kaderSelect) {
                const kaderName =
                    item?.kader?.nama_lengkap || item?.kader?.nama || item?.kader;
                const matchedKader = kaderList.find(
                    (kader) =>
                        String(kader.id) === String(item?.kader_id || "") ||
                        (kaderName && kader.nama_lengkap === kaderName),
                );
                kaderSelect.value = matchedKader?.id || item?.kader_id || "";
            }
            updatePreview();
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data imunisasi.",
            );
        }
    };

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        hideAlert(alertBox);
        clearValidationErrors(form);
        const payload = serializeForm(form);
        setButtonLoading(submitButton, true, "Menyimpan...");
        try {
            if (mode === "edit" && id) {
                await apiRequest(`/imunisasis/${id}`, {
                    method: "PUT",
                    body: JSON.stringify(payload),
                });
            } else {
                await apiRequest("/imunisasis", {
                    method: "POST",
                    body: JSON.stringify(payload),
                });
            }
            const base = getUserRole() === 'kader' ? '/kader' : '/admin';
            window.location.href = `${base}/imunisasi`;
        } catch (error) {
            if (error?.status === 422) {
                applyValidationErrors(form, error?.data?.errors);
            }
            showAlert(
                alertBox,
                error?.data?.message || "Gagal menyimpan data imunisasi.",
                "danger",
            );
        } finally {
            setButtonLoading(submitButton, false);
        }
    });

    loadOptions().then(loadExisting);
};

const initImunisasiShow = () => {
    const container = document.getElementById("imunisasiDetail");
    if (!container) {
        return;
    }
    const id = parsePathId();
    const editLink = document.getElementById("imunisasiEditLink");
    const deleteLink = document.getElementById("imunisasiDeleteLink");
    const breadcrumb = document.getElementById("imunisasiBreadcrumb");

    if (editLink && id) {
        const base = getUserRole() === 'kader' ? '/kader' : '/admin';
        editLink.href = `${base}/imunisasi/${id}/edit`;
    }
    if (deleteLink && id) {
        deleteLink.addEventListener("click", async (event) => {
            event.preventDefault();
            if (!window.confirm("Yakin ingin menghapus data imunisasi ini?")) {
                return;
            }
            try {
                await apiRequest(`/imunisasis/${id}`, { method: "DELETE" });
                const base = getUserRole() === 'kader' ? '/kader' : '/admin';
                window.location.href = `${base}/imunisasi`;
            } catch (error) {
                window.alert(
                    error?.data?.message || "Gagal menghapus data imunisasi.",
                );
            }
        });
    }

    const getRelationName = (value, fallback = "-") => {
        if (!value) {
            return fallback;
        }
        if (typeof value === "string") {
            return value;
        }
        return value.nama_lengkap || value.nama || value.name || fallback;
    };

    apiRequest(`/imunisasis/${id}`)
        .then((response) => {
            const payload = response?.data || response;
            const item = payload?.data || payload;
            if (!item) {
                return;
            }
            if (breadcrumb) {
                breadcrumb.textContent = `Imunisasi ${formatDate(item.tanggal_pemberian)}`;
            }
            container.querySelectorAll("[data-field]").forEach((el) => {
                const field = el.getAttribute("data-field");
                let value = item[field];
                if (
                    field === "tanggal_pemberian" ||
                    field === "tanggal_berikutnya"
                ) {
                    value = formatDate(value);
                }
                if (field === "warga_nama") {
                    value = getRelationName(
                        item.warga,
                        item.warga_nama || item.warga_id || "-",
                    );
                }
                if (field === "kader_nama") {
                    value = getRelationName(
                        item.kader,
                        item.kader_nama || item.kader_id || "-",
                    );
                }
                el.textContent = value ?? "-";
            });
        })
        .catch(() => {
            window.alert("Gagal memuat detail imunisasi.");
        });
};

const initAdminDashboard = () => {
    const totalWargaEl = document.getElementById("adminTotalWarga");
    if (!totalWargaEl) {
        return;
    }

    const totalKaderEl = document.getElementById("adminTotalKader");
    const totalPenimbanganEl = document.getElementById("adminTotalPenimbangan");
    const totalImunisasiEl = document.getElementById("adminTotalImunisasi");
    const activityBody = document.getElementById("adminRecentActivityTableBody");
    const alertBox = document.getElementById("adminDashboardAlert");
    const dateEl = document.getElementById("adminDashboardDate");

    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    }

    const getRelationName = (value, fallback = "-") => {
        if (!value) {
            return fallback;
        }
        if (typeof value === "string") {
            return value;
        }
        return value.nama_lengkap || value.nama || value.name || fallback;
    };

    const toDate = (value) => {
        if (!value) {
            return null;
        }
        const date = new Date(value);
        return Number.isNaN(date.getTime()) ? null : date;
    };

    const renderActivities = (activities = []) => {
        if (!activityBody) {
            return;
        }
        if (!activities.length) {
            activityBody.innerHTML =
                '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas terbaru.</td></tr>';
            return;
        }

        const typeBadge = (type) => {
            if (type === "Penimbangan") {
                return '<span class="badge bg-warning text-dark">Penimbangan</span>';
            }
            if (type === "Imunisasi") {
                return '<span class="badge bg-info text-dark">Imunisasi</span>';
            }
            if (type === "Warga Baru") {
                return '<span class="badge bg-primary">Warga Baru</span>';
            }
            return '<span class="badge bg-success">Kader Baru</span>';
        };

        const statusBadge = (status) => {
            const key = String(status || "").toLowerCase();
            if (key === "disetujui" || key === "selesai") {
                return '<span class="badge bg-success">Selesai</span>';
            }
            if (key === "pending") {
                return '<span class="badge bg-warning text-dark">Pending</span>';
            }
            if (key === "ditolak") {
                return '<span class="badge bg-danger">Ditolak</span>';
            }
            return `<span class="badge bg-secondary">${escapeHtml(status || "-")}</span>`;
        };

        activityBody.innerHTML = activities
            .map((activity, index) => {
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(formatDate(activity.date))}</td>
                        <td>${typeBadge(activity.type)}</td>
                        <td><strong>${escapeHtml(activity.name || "-")}</strong></td>
                        <td>${escapeHtml(activity.officer || "-")}</td>
                        <td>${statusBadge(activity.status)}</td>
                    </tr>
                `;
            })
            .join("");
    };

    const loadDashboard = async () => {
        try {
            hideAlert(alertBox);
            const [wargaRes, kaderRes, penimbanganRes, imunisasiRes] =
                await Promise.all([
                    apiRequest("/wargas?status_verifikasi=all"),
                    apiRequest("/kaders"),
                    apiRequest("/penimbangans"),
                    apiRequest("/imunisasis"),
                ]);

            if (totalWargaEl) {
                totalWargaEl.textContent = String(wargaRes?.total ?? 0);
            }
            if (totalKaderEl) {
                totalKaderEl.textContent = String(kaderRes?.total ?? 0);
            }
            if (totalPenimbanganEl) {
                totalPenimbanganEl.textContent = String(
                    penimbanganRes?.total ?? penimbanganRes?.data?.length ?? 0,
                );
            }
            if (totalImunisasiEl) {
                totalImunisasiEl.textContent = String(
                    imunisasiRes?.total ?? imunisasiRes?.data?.length ?? 0,
                );
            }

            const wargaItems = wargaRes?.data || [];
            const kaderItems = kaderRes?.data || [];
            const penimbanganItems = penimbanganRes?.data || [];
            const imunisasiItems = imunisasiRes?.data || [];

            const getItemName = (item) => {
                if (!item) return "-";
                // prefer nested relation
                const warga = item.warga || item.warga_data || null;
                if (typeof warga === "string") {
                    return warga;
                }
                if (warga) {
                    return warga.nama_lengkap || warga.name || warga.nama || warga.nik || String(warga.id || "-");
                }
                // fallback to top-level fields that may exist in formatted responses
                return item.warga || item.warga_nama || item.warga_name || item.nama_lengkap || item.name || item.nama || "-";
            };

            const getOfficerName = (item) => {
                if (!item) return "-";
                const kader = item.kader || item.kader_data || null;
                if (typeof kader === "string") {
                    return kader;
                }
                if (kader) {
                    return kader.nama_lengkap || kader.name || kader.nama || String(kader.id || "-");
                }
                return item.kader || item.kader_nama || item.kader_name || item.petugas || item.petugas_nama || item.kader_id || "-";
            };

            const activities = [
                ...penimbanganItems.map((item) => ({
                    date: item.tanggal,
                    sortDate: toDate(item.tanggal),
                    type: "Penimbangan",
                    name: getItemName(item),
                    officer: getOfficerName(item),
                    status: "Selesai",
                })),
                ...imunisasiItems.map((item) => ({
                    date: item.tanggal_pemberian,
                    sortDate: toDate(item.tanggal_pemberian),
                    type: "Imunisasi",
                    name: getItemName(item),
                    officer: getOfficerName(item),
                    status: "Selesai",
                })),
                ...wargaItems.map((item) => ({
                    date: item.created_at,
                    sortDate: toDate(item.created_at),
                    type: "Warga Baru",
                    name: item.nama_lengkap || "-",
                    officer: "Admin",
                    status: item.status_verifikasi || "pending",
                })),
                ...kaderItems.map((item) => ({
                    date: item.created_at,
                    sortDate: toDate(item.created_at),
                    type: "Kader Baru",
                    name: item.nama_lengkap || "-",
                    officer: "Admin",
                    status: "Selesai",
                })),
            ]
                .filter((item) => item.sortDate)
                .sort((a, b) => b.sortDate - a.sortDate)
                .slice(0, 8);

            renderActivities(activities);
        } catch (error) {
            showAlert(
                alertBox,
                error?.data?.message || "Gagal memuat data dashboard admin.",
                "danger",
            );
            renderActivities([]);
        }
    };

    // Initial load
    loadDashboard();

    // Manual sync button
    const syncBtn = document.getElementById("adminDashboardSync");
    if (syncBtn) {
        syncBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const original = syncBtn.innerHTML;
            syncBtn.disabled = true;
            syncBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sinkron...';
            loadDashboard()
                .finally(() => {
                    syncBtn.disabled = false;
                    syncBtn.innerHTML = original;
                })
                .catch(() => {});
        });
    }

    // Auto-refresh every 60 seconds
    const dashboardAutoRefreshMs = 60000;
    const dashboardIntervalId = setInterval(loadDashboard, dashboardAutoRefreshMs);
    window._adminDashboardIntervalId = dashboardIntervalId;
};

const initPublicLandingSummary = () => {
    const wrapper = document.getElementById("landingSummary");
    if (!wrapper) {
        return;
    }

    const totalWargaEl = document.getElementById("landingTotalWarga");
    const totalKaderEl = document.getElementById("landingTotalKader");
    const totalPenimbanganEl = document.getElementById("landingTotalPenimbangan");
    const totalImunisasiEl = document.getElementById("landingTotalImunisasi");
    const updatedAtEl = document.getElementById("landingSummaryUpdatedAt");

    const formatCount = (value) => {
        const num = Number(value);
        if (!Number.isFinite(num)) {
            return "-";
        }
        return num.toLocaleString("id-ID");
    };

    const pickTotal = (response) => response?.total ?? response?.data?.length ?? 0;

    const requestWithFallback = async (requests = []) => {
        let lastError = null;
        for (const request of requests) {
            try {
                const response = await apiRequest(
                    request.path,
                    request.options || {},
                    request.meta || {},
                );
                return { response, ok: true, error: null };
            } catch (error) {
                lastError = error;
            }
        }
        return { response: null, ok: false, error: lastError };
    };

    const loadSummary = async () => {
        const [wargaResult, kaderResult, penimbanganResult, imunisasiResult] =
            await Promise.all([
                requestWithFallback([
                    { path: "/public/wargas", meta: { auth: false, apiKey: true } },
                    {
                        path: "/wargas?status_verifikasi=all",
                        options: {},
                        meta: { auth: false },
                    },
                ]),
                requestWithFallback([{ path: "/kaders", meta: { auth: false } }]),
                requestWithFallback([
                    { path: "/public/penimbangans", meta: { auth: false, apiKey: true } },
                    {
                        path: "/penimbangans",
                        options: {},
                        meta: { auth: false },
                    },
                ]),
                requestWithFallback([{ path: "/imunisasis", meta: { auth: false } }]),
            ]);

        const wargaRes = wargaResult.response;
        const kaderRes = kaderResult.response;
        const penimbanganRes = penimbanganResult.response;
        const imunisasiRes = imunisasiResult.response;

        if (totalWargaEl && wargaRes) {
            totalWargaEl.textContent = formatCount(pickTotal(wargaRes));
        }
        if (totalKaderEl && kaderRes) {
            totalKaderEl.textContent = formatCount(pickTotal(kaderRes));
        }
        if (totalPenimbanganEl && penimbanganRes) {
            totalPenimbanganEl.textContent = formatCount(pickTotal(penimbanganRes));
        }
        if (totalImunisasiEl && imunisasiRes) {
            totalImunisasiEl.textContent = formatCount(pickTotal(imunisasiRes));
        }

        if (updatedAtEl) {
            updatedAtEl.textContent = `Diperbarui ${new Date().toLocaleString("id-ID")}`;
        }
    };

    loadSummary();
};

const initKaderDashboard = () => {
    const totalWargaEl = document.getElementById('kaderTotalWarga');
    if (!totalWargaEl) return;

    const totalPenimbanganEl = document.getElementById('kaderPenimbanganBulanIni');
    const totalImunisasiEl = document.getElementById('kaderImunisasiBulanIni');
    const scheduleBody = document.querySelector('#kaderTodayScheduleBody');
    const alertBox = document.getElementById('kaderDashboardAlert');
    const dateEl = document.getElementById('kaderDashboardDate');

    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    const load = async () => {
        try {
            hideAlert(alertBox);
            const [wargaRes, penimbanganRes, imunisasiRes] = await Promise.all([
                apiRequest('/wargas?status_verifikasi=all'),
                apiRequest('/penimbangans'),
                apiRequest('/imunisasis'),
            ]);

            if (totalWargaEl) totalWargaEl.textContent = String(wargaRes?.total ?? wargaRes?.data?.length ?? 0);
            if (totalPenimbanganEl) totalPenimbanganEl.textContent = String(penimbanganRes?.total ?? penimbanganRes?.data?.length ?? 0);
            if (totalImunisasiEl) totalImunisasiEl.textContent = String(imunisasiRes?.total ?? imunisasiRes?.data?.length ?? 0);

            const scheduleItems = (penimbanganRes?.data || []).slice(0, 8);
            if (scheduleBody) {
                if (!scheduleItems.length) {
                    scheduleBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas hari ini.</td></tr>';
                } else {
                    scheduleBody.innerHTML = scheduleItems
                        .map((it, idx) => {
                                const name = typeof it.warga === 'string'
                                    ? it.warga
                                    : it.warga?.nama_lengkap || it.warga_nama || '-';
                            const kategori = it.warga?.kategori || it.kategori || '-';
                            const tanggal = it.tanggal ? formatDate(it.tanggal) : '-';
                            const status = it.status_gizi || '-';
                            return `
                                <tr>
                                    <td>${idx + 1}</td>
                                    <td><strong>${escapeHtml(name)}</strong></td>
                                    <td>${escapeHtml(kategori)}</td>
                                    <td>${escapeHtml(tanggal)}</td>
                                    <td>${escapeHtml(status)}</td>
                                </tr>
                            `;
                        })
                        .join('');
                }
            }
        } catch (error) {
            showAlert(alertBox, error?.data?.message || 'Gagal memuat data dashboard kader.', 'danger');
        }
    };

    load();
    const btn = document.getElementById('adminDashboardSync');
    if (btn) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            load();
        });
    }
};

const initUiInteractions = () => {
    const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebarBackdrop");
    const toggles = document.querySelectorAll('[data-toggle="sidebar"]');

    const closeSidebar = () => {
        if (sidebar) {
            sidebar.classList.remove("open");
        }
        if (backdrop) {
            backdrop.classList.remove("show");
        }
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            if (!sidebar || !backdrop) {
                return;
            }
            sidebar.classList.toggle("open");
            backdrop.classList.toggle("show");
        });
    });

    if (backdrop) {
        backdrop.addEventListener("click", closeSidebar);
    }

    const passwordToggles = document.querySelectorAll(".toggle-password");
    passwordToggles.forEach((button) => {
        button.addEventListener("click", () => {
            const input = button
                .closest(".input-group")
                ?.querySelector("input");
            const icon = button.querySelector("i");
            if (!input) {
                return;
            }
            const isHidden = input.type === "password";
            input.type = isHidden ? "text" : "password";
            if (icon) {
                icon.classList.toggle("bi-eye", !isHidden);
                icon.classList.toggle("bi-eye-slash", isHidden);
            }
        });
    });

    const kategoriCards = document.querySelectorAll(".kategori-card");
    kategoriCards.forEach((card) => {
        card.addEventListener("click", () => {
            kategoriCards.forEach((item) => item.classList.remove("selected"));
            card.classList.add("selected");
            const input = card.querySelector('input[type="radio"]');
            if (input) {
                input.checked = true;
            }
        });
    });

    const vaksinItems = document.querySelectorAll(".vaksin-item");
    vaksinItems.forEach((item) => {
        item.addEventListener("click", () => {
            vaksinItems.forEach((el) => el.classList.remove("selected"));
            item.classList.add("selected");
            const input = item.querySelector('input[type="radio"]');
            if (input) {
                input.checked = true;
            }
        });
    });
};

document.addEventListener("DOMContentLoaded", () => {
    initUiInteractions();
    initAuthGuard();
    initStaffNotifications();
    initLogin();
    initRegister();
    initPublicWargaRegistration();
    initPublicWargaStatus();
    initPublicLandingSummary();
    initLogout();
    updateUserUI();
    initPublicProfile();
    initWargaIndex();
    initWargaForm();
    initWargaShow();
    initKaderIndex();
    initKaderForm();
    initKaderShow();
    initJadwalPosyanduIndex();
    initVerifikasiIndex();
    initKaderDashboard();
    initAdminDashboard();
    initPenimbanganIndex();
    initPenimbanganForm();
    initPenimbanganShow();
    initImunisasiIndex();
    initImunisasiForm();
    initImunisasiShow();
});
