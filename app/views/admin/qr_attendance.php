<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h4 class="mb-0">Gym Attendance QR Code</h4>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted mb-4">
                        Display this QR code in the gym. Users can scan it with their phones to mark their attendance automatically.
                    </p>

                    <div class="qr-container bg-white p-4 rounded-4 shadow-sm d-inline-block border mb-4">
                        <?php 
                            // Detect absolute URL
                            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                            $host = $_SERVER['HTTP_HOST'];
                            $qrUrl = $protocol . "://" . $host . BASE_URL . "/attendance/qrMark";
                            
                            // Use a reliable QR API
                            $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrUrl);
                        ?>
                        <img id="qrImage" src="<?= $qrImageUrl ?>" alt="Attendance QR Code" class="img-fluid" style="min-width: 250px; border: 1px solid #eee;">
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button onclick="window.print()" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-print me-2"></i> Print QR Code
                        </button>
                        <button id="downloadBtn" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-download me-2"></i> Download QR Code
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted d-block mb-1">Scannable Link:</small>
                        <code class="bg-light p-2 rounded d-block text-break"><?= $qrUrl ?></code>
                        <div class="alert alert-info mt-3 py-2 small border-0 shadow-sm text-start">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Make sure your gym computer is accessible via this IP/Host from your phone's network.
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-link mt-4 text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #qrImage, #qrImage * {
        visibility: visible;
    }
    #qrImage {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 80% !important;
        max-width: 500px !important;
    }
}
</style>

<script>
document.getElementById('downloadBtn').addEventListener('click', function() {
    const img = document.getElementById('qrImage');
    
    fetch(img.src)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'gym-attendance-qr.png';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();
        })
        .catch(() => {
            alert('Failed to download image. Please right-click the QR code and select "Save image as".');
        });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
