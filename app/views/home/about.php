<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">

    <!-- HERO SECTION -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-warning display-6">
            Gymnasium Legend <span class="text-white">86</span>
        </h1>
        <p class="text-secondary mt-2">
            A Legacy of Strength, Discipline & Wellness Since 2011
        </p>
        <div class="mx-auto mt-3" style="width:80px;height:3px;background:#ffc107;"></div>
    </div>

    <!-- INTRO CARD -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <p class="text-white fs-5 mb-0">
                        <strong class="text-warning">Gymnasium Legend 86</strong> was established by the
                        <strong>1986 batch alumni</strong> of the institute on the occasion of their
                        <strong>Silver Jubilee in 2011</strong>.
                        It stands as a tribute to fitness, discipline, and lifelong wellness within the campus community.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 shadow-sm text-center">
                <div class="card-body">
                    <div class="fs-1 text-warning mb-2">🏋️</div>
                    <h5 class="text-white">Modern Equipment</h5>
                    <p class="text-secondary small">
                        Advanced machines for aerobics and heavy weight training including treadmills,
                        cross-trainers, multifunction units and lat pulldown stations.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 shadow-sm text-center">
                <div class="card-body">
                    <div class="fs-1 text-warning mb-2">🎓</div>
                    <h5 class="text-white">Exclusive Access</h5>
                    <p class="text-secondary small">
                        Available only to institute
                        <strong>students, faculty members, and alumni</strong>
                        to maintain a focused and disciplined environment.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 shadow-sm text-center">
                <div class="card-body">
                    <div class="fs-1 text-warning mb-2">👨‍🏫</div>
                    <h5 class="text-white">Professional Supervision</h5>
                    <p class="text-secondary small">
                        Managed by a full-time trainer and attendant under the guidance
                        of the Professor-in-Charge of the Gymnasium.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- TIMINGS SECTION -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card bg-dark border-warning shadow-lg">
                <div class="card-body text-center p-4">
                    <h4 class="text-warning mb-3">Gym Timings</h4>
                    <p class="text-white mb-1">
                        <strong>Monday to Saturday</strong>
                    </p>
                    <p class="text-secondary mb-1">
                        Morning: <strong class="text-white">6:00 AM – 9:00 AM</strong>
                    </p>
                    <p class="text-secondary mb-0">
                        Evening: <strong class="text-white">5:00 PM – 9:00 PM</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CLOSING SECTION -->
    <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
            <p class="text-warning fs-5">
                Gymnasium Legend 86 is more than a fitness center — it is a symbol of
                <strong class="text-warning">alumni contribution</strong>,
                <strong class="text-warning">institutional pride</strong>,
                and a lasting commitment to a
                <strong class="text-warning">healthy and active lifestyle</strong>.
            </p>

            <a href="<?= BASE_URL ?>/auth/register" class="btn btn-warning btn-lg mt-3 px-4">
                Join the Legacy
            </a>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
