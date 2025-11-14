<?php
$role       = $userRole ?? session('role')      ?? 'guest';
$username   = $username ?? session('username')  ?? 'Guest';
$isLoggedIn = session('isLoggedIn') ?? false;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark
    <?php if ($role === 'admin'): ?>bg-danger
    <?php elseif ($role === 'teacher'): ?>bg-warning
    <?php else: ?>bg-primary
    <?php endif; ?>">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-clipboard-list"></i> LMS
     

        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown me-2" id="notification-container">
                        <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="fas fa-bell fs-5"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                                </span>
                            <?php else: ?>
                                <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notifDropdown" style="min-width: 320px; max-width: 360px;">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <h6 class="mb-0">
                                    <span id="notif-title">Notifications</span>
                                    <span id="notif-loading" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </h6>
                                <div>
                                    <a href="#" class="text-decoration-none small me-2" id="mark-all-read" title="Mark all as read">
                                        <i class="fas fa-check-double"></i>
                                    </a>
                                    <a href="#" class="text-decoration-none small" id="notif-refresh" title="Refresh notifications">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="notif-list" class="p-2" style="max-height: 400px; overflow-y: auto;">
                                <?php if (!empty($notifications)): ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item alert alert-<?= $notification['is_read'] ? 'light' : 'info' ?> alert-dismissible fade show mb-2 p-2" role="alert" data-id="<?= $notification['id'] ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="me-2">
                                                    <i class="fas <?= $notification['is_read'] ? 'fa-envelope-open' : 'fa-envelope' ?> fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 small"><?= esc($notification['message']) ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted"><?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?></small>
                                                        <?php if (!$notification['is_read']): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-sm mark-as-read" data-id="<?= $notification['id'] ?>">
                                                                Mark as read
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center p-3">
                                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No notifications yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <div class="text-center p-2">
                                <a href="#" class="text-decoration-none small view-all-notifications">View all notifications</a>
                            </div>
                        </div>
                    </li>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-users"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> Courses</a></li>
                    <?php elseif ($role === 'teacher'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> My Classes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-tasks"></i> Assignments</a></li>
                    <?php else: ?> 
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> My Courses</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Grades</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- jQuery and Bootstrap JS for dropdowns -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function($) {
        'use strict';

        // CSRF token for AJAX requests
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        /**
         * Render notifications in the dropdown
         * @param {Array} items - Array of notification objects
         */
        function renderNotifications(items) {
            const $list = $('#notif-list');
            $list.empty();
            
            if (!items || items.length === 0) {
                $list.append(`
                    <div class="text-center p-3">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">No notifications yet</p>
                    </div>
                `);
                return;
            }

            items.forEach(notification => {
                const isRead = Number(notification.is_read) === 1;
                const alertClass = isRead ? 'light' : 'info';
                const timeAgo = timeSince(new Date(notification.created_at));
                
                const notificationElement = `
                    <div class="notification-item alert alert-${alertClass} alert-dismissible fade show mb-2 p-2" 
                         role="alert" 
                         data-id="${notification.id}">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fas ${isRead ? 'fa-envelope-open' : 'fa-envelope'} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">${escapeHtml(notification.message)}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" title="${formatDate(notification.created_at)}">
                                        ${timeAgo}
                                    </small>
                                    ${!isRead ? `
                                        <button type="button" class="btn btn-sm btn-outline-primary mark-as-read" 
                                                data-id="${notification.id}">
                                            Mark as read
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $list.append(notificationElement);
            });
        }

        /**
         * Update the notification badge
         * @param {number} count - Number of unread notifications
         */
        function updateBadge(count) {
            const $badge = $('#notif-badge');
            if (count > 0) {
                $badge.text(count > 9 ? '9+' : count).show();
            } else {
                $badge.hide();
            }
        }

        /**
         * Fetch notifications from the server
         */
        function fetchNotifications() {
            if (isLoading) return; // Prevent multiple simultaneous requests
            
            isLoading = true;
            
            $.ajax({
                url: '<?= base_url('notifications') ?>',
                method: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response && response.status === 'ok') {
                        updateBadge(response.unread || 0);
                        renderNotifications(response.notifications || []);
                        updatePageTitle(response.unread);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching notifications:', error);
                    showToast('Error loading notifications', 'danger');
                },
                complete: function() {
                    isLoading = false;
                    // Hide loading state
                    $('#notif-loading').hide();
                    $('#notif-title').show();
                }
            });
        }

        /**
         * Mark a notification as read
         * @param {number} id - Notification ID
         * @param {boolean} markAll - Whether to mark all as read
         */
        function markAsRead(id = null, markAll = false) {
            const url = markAll 
                ? '<?= base_url('notifications/mark_all_read') ?>'
                : `<?= base_url('notifications/mark_read') ?>/${id}`;
                
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.status === 'ok') {
                        // Refresh notifications
                        fetchNotifications();
                        
                        // Show feedback
                        if (markAll) {
                            showToast('All notifications marked as read', 'success');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error marking notification as read:', error);
                    showToast('Error updating notification', 'danger');
                }
            });
        }

        /**
         * Update page title with unread count
         * @param {number} unreadCount - Number of unread notifications
         */
        function updatePageTitle(unreadCount) {
            if (unreadCount > 0) {
                document.title = document.title.replace(/^\(\d+\)\s*|^\(\d+\+?\)\s*/, '') + ' (' + (unreadCount > 9 ? '9+' : unreadCount) + ')';
            } else {
                document.title = document.title.replace(/^\(\d+\)\s*|^\(\d+\+?\)\s*/, '');
            }
        }

        /**
         * Show a toast notification
         * @param {string} message - Message to display
         * @param {string} type - Bootstrap alert type (success, danger, etc.)
         */
        function showToast(message, type = 'info') {
            // Create toast container if it doesn't exist
            let $toastContainer = $('#toast-container');
            if ($toastContainer.length === 0) {
                $toastContainer = $('<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>').appendTo('body');
            }

            const toastId = 'toast-' + Date.now();
            const $toast = $(`
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `);

            $toastContainer.append($toast);
            const toast = new bootstrap.Toast($toast[0]);
            toast.show();

            // Remove toast after it's hidden
            $toast.on('hidden.bs.toast', function() {
                $toast.remove();
            });
        }

        /**
         * Format date to relative time (e.g., "2 minutes ago")
         * @param {string} dateString - ISO date string
         * @returns {string} Formatted relative time
         */
        function timeSince(dateString) {
            const date = new Date(dateString);
            const seconds = Math.floor((new Date() - date) / 1000);
            
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) return interval + ' year' + (interval === 1 ? '' : 's') + ' ago';
            
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return interval + ' month' + (interval === 1 ? '' : 's') + ' ago';
            
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return interval + ' day' + (interval === 1 ? '' : 's') + ' ago';
            
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago';
            
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago';
            
            return 'just now';
        }

        /**
         * Format date to readable string
         * @param {string} dateString - ISO date string
         * @returns {string} Formatted date string
         */
        function formatDate(dateString) {
            const options = { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        /**
         * Escape HTML to prevent XSS
         * @param {string} str - String to escape
         * @returns {string} Escaped string
         */
        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Tracks if dropdown is open
        let isDropdownOpen = false;
        let isLoading = false;

        // Event Handlers
        $(document)
            // Handle dropdown show event
            .on('show.bs.dropdown', '#notification-container', function() {
                isDropdownOpen = true;
                // Show loading state
                $('#notif-loading').show();
                $('#notif-title').hide();
                
                // Fetch notifications with a small delay to allow dropdown to animate
                setTimeout(() => {
                    if (isDropdownOpen) {
                        fetchNotifications();
                    }
                }, 100);
            })
            
            // Handle dropdown hide event
            .on('hide.bs.dropdown', '#notification-container', function() {
                isDropdownOpen = false;
            })
            
            // Refresh notifications button
            .on('click', '#notif-refresh', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Show loading state
                $('#notif-loading').show();
                $('#notif-title').hide();
                
                fetchNotifications();
            })
            
            // Mark single notification as read
            .on('click', '.mark-as-read', function() {
                const $btn = $(this);
                const notificationId = $btn.data('id');
                markAsRead(notificationId);
            })
            
            // Mark all notifications as read
            .on('click', '#mark-all-read', function(e) {
                e.preventDefault();
                markAsRead(null, true);
            })
            
            // View all notifications (placeholder)
            .on('click', '.view-all-notifications', function(e) {
                e.preventDefault();
                // You can implement a dedicated notifications page here
                showToast('View all notifications clicked', 'info');
            });

        // Initialize
        $(function() {
            <?php if ($isLoggedIn): ?>
            // Initial fetch
            fetchNotifications();
            
            // Poll for new notifications every 60 seconds
            setInterval(fetchNotifications, 60000);
            
            // Show tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Initialize notification dropdown
            const notificationDropdown = new bootstrap.Dropdown(document.getElementById('notifDropdown'), {
                autoClose: 'outside'
            });
            
            // Prevent dropdown from closing when clicking inside
            const dropdownMenu = document.querySelector('.dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    // Only prevent default for certain elements
                    if (e.target.closest('.mark-as-read, #mark-all-read, #notif-refresh')) {
                        e.stopPropagation();
                    }
                });
            }
            
            // Make notification icon toggle the dropdown
            const notifDropdown = document.getElementById('notifDropdown');
            if (notifDropdown) {
                notifDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dropdown = bootstrap.Dropdown.getInstance(this);
                    if (dropdown) {
                        // Toggle dropdown
                        if (this.getAttribute('aria-expanded') === 'true') {
                            dropdown.hide();
                        } else {
                            dropdown.show();
                        }
                    }
                });
            }
            <?php endif; ?>
        });
    })(jQuery);
</script>