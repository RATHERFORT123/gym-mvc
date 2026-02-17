<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Events</h2>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createEventModal">+ Create New Event</button>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No events found. Create your first event!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($event['title']) ?></strong>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?><?= strlen($event['description'] ?? '') > 100 ? '...' : '' ?></small>
                                    </td>
                                    <td>
                                        <?php if ($event['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= date('M d, Y', strtotime($event['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary btn-sm edit-event-btn" 
                                                    data-id="<?= $event['id'] ?>"
                                                    data-title="<?= htmlspecialchars($event['title']) ?>"
                                                    data-description="<?= htmlspecialchars($event['description'] ?? '') ?>"
                                                    data-status="<?= $event['status'] ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editEventModal">
                                                ✏️ Edit
                                            </button>
                                            
                                            <?php if ($event['status'] === 'active'): ?>
                                                <button class="btn btn-warning btn-sm confirm-action-btn" 
                                                        data-action="deactivate"
                                                        data-url="<?= BASE_URL ?>/admin/toggleEventStatus/<?= $event['id'] ?>?status=inactive"
                                                        data-title="Deactivate Event"
                                                        data-message="Are you sure you want to deactivate '<?= htmlspecialchars($event['title']) ?>'?"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#confirmActionModal">
                                                    🚫 Deactivate
                                                </button>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>/admin/toggleEventStatus/<?= $event['id'] ?>?status=active" 
                                                   class="btn btn-success btn-sm">
                                                    ✅ Activate
                                                </a>
                                            <?php endif; ?>
                                            
                                            <button class="btn btn-danger btn-sm confirm-action-btn" 
                                                    data-action="delete"
                                                    data-url="<?= BASE_URL ?>/admin/deleteEvent/<?= $event['id'] ?>"
                                                    data-title="Delete Event"
                                                    data-message="Are you sure you want to permanently delete '<?= htmlspecialchars($event['title']) ?>'? This action cannot be undone."
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmActionModal">
                                                🗑️ Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="<?= BASE_URL ?>/admin/createEvent">
            <div class="modal-header">
                <h5 class="modal-title">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="create_title" class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_title" name="title" required maxlength="255" placeholder="Enter event title">
                </div>

                <div class="mb-3">
                    <label for="create_description" class="form-label">Description</label>
                    <textarea class="form-control" id="create_description" name="description" rows="4" placeholder="Enter event description or announcement details"></textarea>
                </div>

                <div class="mb-3">
                    <label for="create_status" class="form-label">Status</label>
                    <select class="form-select" id="create_status" name="status">
                        <option value="inactive">Inactive (Draft)</option>
                        <option value="active">Active (Visible to Users)</option>
                    </select>
                    <small class="text-muted">Active events will be displayed to users and faculty members.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Create Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" id="editEventForm">
            <div class="modal-header">
                <h5 class="modal-title">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_title" name="title" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="edit_status" class="form-label">Status</label>
                    <select class="form-select" id="edit_status" name="status">
                        <option value="inactive">Inactive (Draft)</option>
                        <option value="active">Active (Visible to Users)</option>
                    </select>
                    <small class="text-muted">Active events will be displayed to users and faculty members.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalTitle">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button clicks
    const editButtons = document.querySelectorAll('.edit-event-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            const description = this.dataset.description;
            const status = this.dataset.status;
            
            // Update form action
            document.getElementById('editEventForm').action = '<?= BASE_URL ?>/admin/editEvent/' + id;
            
            // Populate form fields
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_status').value = status;
        });
    });
});

// Handle confirmation modal
document.addEventListener('DOMContentLoaded', function() {
    const confirmButtons = document.querySelectorAll('.confirm-action-btn');
    const confirmModal = document.getElementById('confirmActionModal');
    
    confirmButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const title = this.dataset.title;
            const message = this.dataset.message;
            const url = this.dataset.url;
            const action = this.dataset.action;
            
            // Update modal content
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;
            
            // Update confirm button style based on action
            const confirmBtn = document.getElementById('confirmActionBtn');
            confirmBtn.className = 'btn '; // Reset classes
            
            if (action === 'delete') {
                confirmBtn.classList.add('btn-danger');
                confirmBtn.innerHTML = '🗑️ Delete';
            } else if (action === 'deactivate') {
                confirmBtn.classList.add('btn-warning');
                confirmBtn.innerHTML = '🚫 Deactivate';
            } else {
                confirmBtn.classList.add('btn-primary');
                confirmBtn.innerHTML = 'Confirm';
            }
            
            // Set the action URL
            confirmBtn.onclick = function() {
                window.location.href = url;
            };
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
