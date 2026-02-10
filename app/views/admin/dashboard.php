<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <div class="row g-4">
        
        <!-- Manage Users -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-primary">👥</h1>
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text text-muted">View users, assign plans, and manage access.</p>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-primary">Go to Users</a>
                </div>
            </div>
        </div>

        <!-- Manage Plans -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-success">💳</h1>
                    <h5 class="card-title">Manage Plans & Prices</h5>
                    <p class="card-text text-muted">Edit plan prices for users and faculty.</p>
                    <a href="<?= BASE_URL ?>/admin/plans" class="btn btn-success">Manage Plans</a>
                </div>
            </div>
        </div>

        <!-- Reports (Placeholder) -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-warning">💰</h1>
                    <h5 class="card-title">Manage Payments</h5>
                    <p class="card-text text-muted">Verify transactions and check payer UPI IDs.</p>
                    <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-warning">Go to Payments</a>
                </div>
            </div>
        </div>

        <!-- Manage Events -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-danger">📢</h1>
                    <h5 class="card-title">Manage Events</h5>
                    <p class="card-text text-muted">Create and manage gym events and announcements.</p>
                    <a href="<?= BASE_URL ?>/admin/events" class="btn btn-danger">Go to Events</a>
                </div>
            </div>
        </div>

        <!-- Manage Faculty (Placeholder) -->
       <div class="col-md-4">
    <div class="card shadow h-100">
        <div class="card-body text-center">
            <h1 class="display-4 text-secondary">👨‍🏫</h1>
            <h5 class="card-title">Manage Faculty</h5>
            <p class="card-text text-muted">View and manage faculty members.</p>
            <a href="<?= BASE_URL ?>/admin/faculty" class="btn btn-secondary">
                Go to Faculty
            </a>
        </div>
    </div>
</div>



        <!-- Reports (Placeholder) -->
        <div class="col-md-4">
            <div class="card shadow h-100 border-secondary" style="opacity: 0.6;">
                <div class="card-body text-center">
                    <h1 class="display-4 text-secondary">📊</h1>
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text text-muted">View attendance and growth reports.</p>
                    <button class="btn btn-secondary" disabled>Coming Soon</button>
                </div>
            </div>
        </div>

        <!-- Email Automation Settings -->
        <div class="col-md-4">
            <div class="card shadow h-100 border-primary">
                <div class="card-body text-center">
                    <h1 class="display-4 text-primary">📧</h1>
                    <h5 class="card-title">Email Automation</h5>
                    <p class="card-text text-muted">Notify users before their plan expires.</p>
                    
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#reminderConfigModal">
                        Configure Reminders
                    </button>
                </div>
            </div>
        </div>

        <!-- QR Attendance -->
        <div class="col-md-4">
            <div class="card shadow h-100 border-info">
                <div class="card-body text-center">
                    <h1 class="display-4 text-info">QR</h1>
                    <h5 class="card-title">Attendance QR Code</h5>
                    <p class="card-text text-muted">Generate and print QR code for gym wall.</p>
                    <a href="<?= BASE_URL ?>/admin/qr_attendance" class="btn btn-info text-white">View QR Code</a>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5>Total Users</h5>
                <h2><?= $stats['total_users'] ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5>Total Faculty</h5>
                <h2><?= $stats['total_faculty'] ?></h2>
            </div>
        </div>
    </div>  

    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5>Today Attendance</h5>
                <h2><?= $stats['today_attendance'] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Email Reminder Configuration Modal -->
<div class="modal fade" id="reminderConfigModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configure Plan Expiration Emails Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reminderForm" action="<?= BASE_URL ?>/admin/saveSettings" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reminder Schedule</label>
                        <p class="text-muted mb-0">Emails will be sent automatically <strong>3 days, 2 days, and 1 day</strong> before plan expiration.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Send Emails At (Times)</label>
                        <div id="timesContainer">
                            <?php 
                                // Ensure $cron_times is an array
                                $timesList = (isset($cron_times) && is_array($cron_times) && count($cron_times) > 0) ? $cron_times : ['15:00'];
                                $tIndex = 0;
                                foreach ($timesList as $time): 
                            ?>
                            <div class="input-group mb-2 time-input-group">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="time" name="reminder_times[]" class="form-control" value="<?= htmlspecialchars($time) ?>" required>
                                <?php if ($tIndex > 0): ?>
                                <button type="button" class="btn btn-outline-danger remove-time-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                            <?php $tIndex++; endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="addTimeBtn">
                            <i class="fas fa-plus"></i> Add Another Timing
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch notifications
    function fetchNotifications() {
        fetch('<?= BASE_URL ?>/admin/getNotifications')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update Badge
                    const badge = document.querySelector('#notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }

                    // Update List (if you have a container for it)
                    const listContainer = document.querySelector('#notification-list');
                    if (listContainer && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(n => {
                            html += `<li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/payments">
                                <small class="fw-bold">${n.user_name}</small><br>
                                <small class="text-muted">Paid ₹${n.amount}</small>
                            </a></li>`;
                        });
                        listContainer.innerHTML = html;
                    }
                }
            });
    }
    // Poll every 30 seconds
    setInterval(fetchNotifications, 30000);
    // Initial call
    fetchNotifications();

    // Dynamic Time Inputs
    const container = document.getElementById('timesContainer');
    const addBtn = document.getElementById('addTimeBtn');
    const maxTimes = 3;

    function updateAddButton() {
        const count = container.querySelectorAll('.time-input-group').length;
        addBtn.style.display = (count >= maxTimes) ? 'none' : 'inline-block';
    }
    updateAddButton();

    addBtn.addEventListener('click', function() {
        if (container.querySelectorAll('.time-input-group').length >= maxTimes) return;

        const div = document.createElement('div');
        div.className = 'input-group mb-2 time-input-group';
        div.innerHTML = `
            <span class="input-group-text"><i class="fas fa-clock"></i></span>
            <input type="time" name="reminder_times[]" class="form-control" required>
            <button type="button" class="btn btn-outline-danger remove-time-btn">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
        updateAddButton();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-time-btn')) {
            e.target.closest('.time-input-group').remove();
            updateAddButton();
        }
    });

    // Form Validation
    const reminderForm = document.getElementById('reminderForm');
    if (reminderForm) {
        reminderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const inputs = this.querySelectorAll('input[name="reminder_times[]"]');
            const times = [];
            let hasError = false;

            for (let input of inputs) {
                const val = input.value;
                if (!val) {
                    toastr.error('All time fields are required.', 'Validation Error');
                    input.focus();
                    hasError = true;
                    break;
                }
                if (times.includes(val)) {
                    toastr.error('Duplicate time detected: ' + val, 'Validation Error');
                    input.focus();
                    hasError = true;
                    break;
                }
                times.push(val);
            }

            if (!hasError) {
                this.submit();
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>