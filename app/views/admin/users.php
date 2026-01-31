<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Users</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/admin/users" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white border-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg></span>
                        <input type="text" name="search" class="form-control border-secondary" placeholder="Search by name or email..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white border-secondary">Limit</span>
                        <select name="limit" class="form-select border-secondary" onchange="this.form.submit()">
                            <?php foreach([10, 25, 50, 75, 100] as $l): ?>
                                <option value="<?= $l ?>" <?= ($limit == $l) ? 'selected' : '' ?>><?= $l ?> per page</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php if (!empty($search)): ?>
                    <div class="col-md-2">
                        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary w-100">Clear Search</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Fitness Goal</th>
                            <th>BMI Stats</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($user['name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
                                    <td>
                                        <?php if($user['fitness_goal']): ?>
                                            <span class="badge bg-info text-dark"><?= $user['fitness_goal'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user['weight_kg'] && $user['height_cm']): ?>
                                            <small>
                                                H: <?= $user['height_cm'] ?>cm<br>
                                                W: <?= $user['weight_kg'] ?>kg
                                            </small>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>/admin/assignPlan/<?= $user['id'] ?>" class="btn btn-primary" title="Assign Plan">
                                                📋 Plan
                                            </a>
                                            
                                            <?php if ($user['is_active']): ?>
                                                <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=0" class="btn btn-warning" onclick="return confirm('Deactivate this user?')">
                                                    🚫 Block
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=1" class="btn btn-success">
                                                    ✅ Activate
                                                </a>
                                            <?php endif; ?>
    
                                            <a href="<?= BASE_URL ?>/admin/deleteUser/<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <?php 
                $baseUrl = BASE_URL . "/admin/users?limit=" . $limit . "&search=" . urlencode($search);
                ?>
                <nav aria-label="User navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?>" tabindex="-1">Previous</a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $baseUrl ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const filterForm = searchInput ? searchInput.closest('form') : null;
    let debounceTimer;

    if (searchInput && filterForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 600); // 600ms debounce
        });

        // Set cursor to end of input on focus (useful for auto-reloads)
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
