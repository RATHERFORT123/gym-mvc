<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            Attendance Calendar –
            <?= htmlspecialchars($user['name']) ?>
        </h3>
        <div>

            <a href="<?= BASE_URL ?>/admin/exportAttendance/<?= $user['id'] ?>"
            class="btn btn-success ">
            ⬇️ Export EXCEL
        </a>
        <a href="<?= BASE_URL ?>/admin/attendance/<?= $user['id'] ?>"
        class="btn btn-secondary">
        Back
    </a>
</div>
       
    </div>

    <!-- Month Selector -->
    <form method="get" class="mb-4">
        <input type="month" name="month" value="<?= $month ?>" class="form-control w-25">
        <button class="btn btn-primary mt-2">View</button>
    </form>

    <?php
        $year  = date('Y', strtotime($month));
        $mon   = date('m', strtotime($month));
        $days  = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
    ?>

    <div class="row g-2">
        <?php for ($d = 1; $d <= $days; $d++): ?>
            <?php
                $date = sprintf('%s-%02d', $month, $d);
                $present = in_array($date, $dates);
            ?>
            <div class="col-2 col-md-1 text-center">
                <div class="p-2 rounded
                    <?= $present ? 'bg-success text-white' : 'bg-light border' ?>">
                    <strong><?= $d ?></strong><br>
                    <small><?= $present ? 'Present' : 'Absent' ?></small>
                </div>
            </div>
        <?php endfor; ?>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
