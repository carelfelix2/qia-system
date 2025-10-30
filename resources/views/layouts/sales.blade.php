<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sales Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite('resources/css/tabler.css')
</head>
<body>
    <div class="page">
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href=".">
                        <img src="{{ asset('images/logo.png') }}" width="110" height="32" alt="Logo" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item me-2">
                        <button id="dark-mode-toggle" class="nav-link d-flex lh-1 text-reset p-0" aria-label="Toggle dark mode">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
                  </svg>
                        </button>
                    </div>
                    <div class="nav-item dropdown me-2">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open notifications" id="notification-toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
                            </svg>
                            <span class="badge bg-red badge-blink position-absolute top-0 start-100 translate-middle" id="notification-badge" style="font-size: 0.6rem; display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="notification-dropdown">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <button class="btn btn-sm btn-outline-primary" id="mark-all-read-btn" style="display: none;">Mark All Read</button>
                            </div>
                            <div id="notification-list">
                                <div class="dropdown-item text-center text-muted">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Loading notifications...
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item text-center">View All</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url({{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://preview.tabler.io/static/avatars/000m.jpg' }})"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="mt-1 small text-muted">Sales</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Profile</a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Settings</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('sales.dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('sales.dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">Home</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('sales.input-penawaran.create') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('sales.input-penawaran.create') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                    </span>
                                    <span class="nav-link-title">Input Penawaran</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('sales.daftar-penawaran') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('sales.daftar-penawaran') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="4" y1="10" x2="20" y2="10" /><line x1="10" y1="4" x2="10" y2="20" /></svg>
                                    </span>
                                    <span class="nav-link-title">Daftar Penawaran</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('sales.daftar-po') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('sales.daftar-po') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                    </span>
                                    <span class="nav-link-title">Daftar PO</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="/" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                  <li class="list-inline-item"><a href="/" class="link-secondary">License</a></li>
                  <li class="list-inline-item">
                    <a href="https://github.com/carelfelix2" target="_blank" class="link-secondary" rel="noopener">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                       Made with
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-pink icon-inline icon-4">
                        <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright © 2025
                    <a href="." class="link-secondary">PT. Quantum Inti Akurasi</a>. All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const htmlElement = document.documentElement;

            // Function to toggle dark mode
            function toggleDarkMode() {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                if (currentTheme === 'dark') {
                    htmlElement.removeAttribute('data-bs-theme');
                    localStorage.setItem('theme', 'light');
                } else {
                    htmlElement.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                htmlElement.setAttribute('data-bs-theme', 'dark');
            }

            // Add click event to toggle button
            darkModeToggle.addEventListener('click', toggleDarkMode);

            // Notification functionality
            let notifications = [];
            let unreadCount = 0;

            function loadNotifications() {
                fetch('/notifications', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    notifications = data.notifications;
                    unreadCount = data.unread_count;
                    updateNotificationUI();
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notification-list').innerHTML = '<div class="dropdown-item text-center text-muted">Failed to load notifications</div>';
                });
            }

            function updateNotificationUI() {
                const badge = document.getElementById('notification-badge');
                const list = document.getElementById('notification-list');
                const markAllBtn = document.getElementById('mark-all-read-btn');

                // Update badge
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }

                // Update list
                if (notifications.length === 0) {
                    list.innerHTML = '<div class="dropdown-item text-center text-muted">No notifications</div>';
                    markAllBtn.style.display = 'none';
                } else {
                    let html = '';
                    notifications.forEach(notification => {
                        const iconClass = notification.read_at ? 'text-muted' : 'text-blue';
                        const itemClass = notification.read_at ? '' : 'fw-bold';
                        const actionUrl = notification.action_url || '#';
                        html += `
                            <a href="${actionUrl}" class="dropdown-item notification-item ${itemClass}" data-id="${notification.id}">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm ${iconClass}" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M9 12l2 2l4 -4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-muted small">${notification.time_ago}</div>
                                        <div>${notification.message}</div>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    list.innerHTML = html;
                    markAllBtn.style.display = unreadCount > 0 ? 'block' : 'none';

                    // Add click handlers for individual notifications
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.addEventListener('click', function(e) {
                            const id = this.getAttribute('data-id');
                            const href = this.getAttribute('href');
                            if (href && href !== '#') {
                                // If there's an action URL, mark as read and allow navigation
                                markAsRead(id);
                                // Navigation will happen naturally
                            } else {
                                // No action URL, just mark as read
                                e.preventDefault();
                                markAsRead(id);
                            }
                        });
                    });
                }
            }

            function markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications(); // Reload notifications
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
            }

            function markAllAsRead() {
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications(); // Reload notifications
                    }
                })
                .catch(error => console.error('Error marking all notifications as read:', error));
            }

            // Load notifications on page load
            loadNotifications();

            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            // Mark all as read button
            document.getElementById('mark-all-read-btn').addEventListener('click', function(e) {
                e.preventDefault();
                markAllAsRead();
            });
        });
    </script>
</body>
</html>
