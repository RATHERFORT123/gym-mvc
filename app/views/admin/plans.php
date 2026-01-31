<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card mb-4 bg-dark text-white">
            <div class="card-body">
                <h4 class="text-primary">Global Payment Settings</h4>
                <form method="post" action="<?= BASE_URL ?>/admin/saveSettings" class="row align-items-end">
                    <div class="col-md-6 text-start">
                        <label class="form-label">Master UPI ID (Used for all plans)</label>
                        <input class="form-control" name="global_upi" value="<?= htmlspecialchars($global_upi ?? UPI_ID) ?>" required placeholder="e.g. your-upi@bank">
                    </div>
                    <div class="col-md-3 text-start">
                        <button class="btn btn-primary w-100">Update UPI ID</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-warning mb-0">Manage Plans & Prices</h3>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#planModal" onclick="prepareAdd()">+ Add New Plan</button>
                </div>
                <p class="text-muted">Set separate prices for regular users and faculty.</p>

                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Plan Key</th>
                            <th>Name</th>
                            <th>Price (User)</th>
                            <th>Price (Faculty)</th>
                            <th>Unique UPI</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['plan_key']) ?></td>
                                <td><?= htmlspecialchars($p['name']) ?></td>
                                <td>₹<?= number_format($p['price_user']) ?></td>
                                <td>₹<?= number_format($p['price_faculty']) ?></td>
                                <td><?= htmlspecialchars($p['upi_id'] ?? 'Default') ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" onclick='prepareEdit(<?= json_encode($p) ?>)'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                              <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.171 7.146-2.203-2.203.111-.049 2.203 2.203-.111.049z"/>
                                            </svg>
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/deletePlanMaster/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this plan? This will remove all associated user subscriptions!')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Plan Modal -->
<div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalTitle">Add New Plan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/admin/saveMasterPlan" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="planId">
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" name="name" id="planName" class="form-control" required placeholder="e.g. Monthly Silver">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan Key (Unique)</label>
                        <input type="text" name="plan_key" id="planKey" class="form-control" required placeholder="e.g. 1m_silver">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (User)</label>
                            <input type="number" name="price_user" id="planPriceUser" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (Faculty)</label>
                            <input type="number" name="price_faculty" id="planPriceFaculty" class="form-control" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Custom UPI ID (Optional)</label>
                        <input type="text" name="upi_id" id="planUpi" class="form-control" placeholder="upi@bank (blank for default)">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareAdd() {
    document.getElementById('modalTitle').textContent = 'Add New Plan';
    document.getElementById('planId').value = '';
    document.getElementById('planName').value = '';
    document.getElementById('planKey').value = '';
    document.getElementById('planPriceUser').value = '';
    document.getElementById('planPriceFaculty').value = '';
    document.getElementById('planUpi').value = '';
}

function prepareEdit(plan) {
    document.getElementById('modalTitle').textContent = 'Edit Plan: ' + plan.name;
    document.getElementById('planId').value = plan.id;
    document.getElementById('planName').value = plan.name;
    document.getElementById('planKey').value = plan.plan_key;
    document.getElementById('planPriceUser').value = plan.price_user;
    document.getElementById('planPriceFaculty').value = plan.price_faculty;
    document.getElementById('planUpi').value = plan.upi_id || '';
    
    // Open modal programmatically
    var myModal = new bootstrap.Modal(document.getElementById('planModal'));
    myModal.show();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>