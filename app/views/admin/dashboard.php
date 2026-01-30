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
