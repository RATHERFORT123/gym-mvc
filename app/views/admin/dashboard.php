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

        <!-- Manage Faculty (Placeholder) -->
        <div class="col-md-4">
            <div class="card shadow h-100 border-secondary" style="opacity: 0.6;">
                <div class="card-body text-center">
                    <h1 class="display-4 text-secondary">👨‍🏫</h1>
                    <h5 class="card-title">Manage Faculty</h5>
                    <p class="card-text text-muted">Faculty specific management.</p>
                    <button class="btn btn-secondary" disabled>Coming Soon</button>
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
        
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
