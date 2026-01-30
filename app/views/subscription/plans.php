<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12 px-4">
        <div class="text-center mb-4">
            <h2 class="text-warning">Choose a Plan</h2>
            <p class="text-white">Select one of our plans and scan the UPI QR to pay. After payment, submit the transaction id.</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>



        <div class="position-relative">
            <!-- Navigation Arrows -->
            <button class="btn btn-outline-warning position-absolute translate-middle-y d-none d-md-flex align-items-center justify-content-center border-0 border-radius-circle shadow-lg slider-arrow arrow-prev" style="top: 50%; left: -20px; z-index: 10; width: 45px; height: 45px; background: rgba(0,0,0,0.6); -webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </button>
            <button class="btn btn-outline-warning position-absolute translate-middle-y d-none d-md-flex align-items-center justify-content-center border-0 border-radius-circle shadow-lg slider-arrow arrow-next" style="top: 50%; right: -20px; z-index: 10; width: 45px; height: 45px; background: rgba(0,0,0,0.6); -webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>

            <div class="d-flex justify-content-center gap-3 pb-3" id="plansContainer" style="overflow-x: auto; flex-wrap: nowrap; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; padding: 20px 0;">
            <?php foreach ($plans as $p): ?>
                <?php $key = $p['plan_key']; ?>
                <?php $displayPrice = (isset($_SESSION['role']) && $_SESSION['role'] === 'faculty') ? $p['price_faculty'] : $p['price_user']; ?>
                <div class="plan-card" style="width: 300px; perspective: 1200px;">
                    <div class="card shadow-lg border-0 bg-dark text-light position-relative">

                        <div class="card-inner">

                            <div class="card-front card-body p-4 text-center">
                                <h4 class="text-warning mb-2"><?= htmlspecialchars($p['name']) ?></h4>
                                <div class="mb-3 display-6">₹<?= number_format($displayPrice) ?></div>
                                <p class="text-white">Access workouts, diet plans and gym attendance</p>
                                <button class="btn btn-success subscribe-btn" data-plan="<?= htmlspecialchars($key) ?>">Subscribe</button>
                            </div>

                            <div class="card-back card-body p-3 text-center">
                                <div class="qr-container mb-4" style="display:none;">
                                    <a href="#" class="open-upi-link d-block mb-2"><img src="" alt="QR" class="qr-img mb-3 img-fluid" style="max-width:180px;"></a>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-sm paid-confirm-btn">I have made the payment</button>
                                    </div>
                                </div>

                                <div class="payment-details">
                                    <div class="mb-2 d-flex justify-content-center align-items-center gap-2">
                                        <small>UPI ID: <strong class="upi-id text-warning"><?= UPI_ID ?></strong></small>
                                        <button type="button" class="btn btn-sm btn-outline-light copy-upi-btn" title="Copy UPI ID" style="padding: 2px 6px; line-height: 1;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                              <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6ZM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1H2Z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mb-2">
                                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">
                                            <a href="#" class="btn btn-primary btn-sm open-upi" style="display:none;">Open UPI app</a>
                                            <a href="#" class="btn btn-warning btn-sm open-phonepe" style="display:none;">Pay via PhonePe</a>
                                            <a href="#" class="btn btn-info btn-sm open-gpay" style="display:none;">Pay via GPay</a>
                                        </div>
                                    </div>

                                    <form method="post" action="<?= BASE_URL ?>/payment/verify" class="verify-form">
                                        <input type="hidden" name="payment_id" value="">
                                        <div class="mb-2">
                                            <input class="form-control form-control-sm" name="payer_upi" placeholder="Your UPI ID (Optional)">
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-control form-control-sm" name="utr" placeholder="Enter transaction id (UTR/TID)" required>
                                        </div>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-primary btn-sm">I Paid</button>
                                            <button type="button" class="btn btn-outline-light btn-sm cancel-btn">Cancel</button>
                                        </div>
                                    </form>

                                    <div class="mt-2 mb-4 text-white small">Click outside to go back</div>
                                </div>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-outline-info btn-sm toggle-qr" style="display:none;">Show QR Code</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>

    </div>
</div>

<script>
    (function(){
        function hideOthers(card){
            document.querySelectorAll('#plansContainer .plan-card').forEach(c=>{ if(c!==card) c.classList.add('d-none'); });
        }
        function showAll(){
            document.querySelectorAll('#plansContainer .plan-card').forEach(c=>{ 
                c.classList.remove('d-none'); 
                c.classList.remove('flipped'); 
                const back = c.querySelector('.card-back'); if(back) back.style.display='none';
                const openLink = c.querySelector('.open-upi-link'); if(openLink) { openLink.href='#'; openLink.style.display='none'; }
                const openBtn = c.querySelector('.open-upi'); if(openBtn) { openBtn.href='#'; openBtn.style.display='none'; }
                const phonepeBtn = c.querySelector('.open-phonepe'); if(phonepeBtn) { phonepeBtn.href='#'; phonepeBtn.style.display='none'; }
                const gpayBtn = c.querySelector('.open-gpay'); if(gpayBtn) { gpayBtn.href='#'; gpayBtn.style.display='none'; }
                const toggleQrBtn = c.querySelector('.toggle-qr'); 
                if(toggleQrBtn) {
                    toggleQrBtn.style.display='none';
                    toggleQrBtn.textContent = 'Show QR Code';
                }
                const qrCont = c.querySelector('.qr-container'); if(qrCont) qrCont.style.display='none';
                const details = c.querySelector('.payment-details'); if(details) details.style.display='block';
            });
            // Show arrows again after reset
            document.querySelectorAll('.slider-arrow').forEach(a => a.style.display = 'flex');
        }

        document.querySelectorAll('.subscribe-btn').forEach(btn=>{
            btn.addEventListener('click', async function(e){
                const card = e.target.closest('.plan-card');
                const planKey = e.target.dataset.plan;

                // call backend to create a pending payment and get QR
                const res = await fetch('<?= BASE_URL ?>/payment/create', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({plan: planKey})
                });

                const data = await res.json();
                if (data.status === 'success') {
                    const back = card.querySelector('.card-back');
                    back.style.display = 'block';

                    const qrImg = card.querySelector('.qr-img');
                    // use server-side QR endpoint URL
                    const qrUrl = data.qr_url || data.qr || '';
                    qrImg.src = qrUrl;
                    card.querySelector('input[name=payment_id]').value = data.payment_id;

                    // set open link
                    const openLink = card.querySelector('.open-upi-link');
                    const openBtn = card.querySelector('.open-upi');
                    const phonepeBtn = card.querySelector('.open-phonepe');
                    const gpayBtn = card.querySelector('.open-gpay');

                    if (openLink) {
                        openLink.href = data.upi_link;
                        openLink.style.display = 'block';
                        openLink.querySelector('img').src = qrUrl;
                    }

                    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                    if (openBtn) {
                        openBtn.href = data.upi_link;
                        if (isMobile) {
                            openBtn.style.display = 'inline-block';
                        } else {
                            openBtn.style.display = 'none';
                        }

                        if (!openBtn.dataset.listenerAdded) {
                            openBtn.addEventListener('click', function(e) {
                                const start = Date.now();
                                setTimeout(() => {
                                    if (Date.now() - start < 2500) {
                                        alert("If your UPI app didn't open, please try the specific PhonePe/GPay buttons or scan the QR code.");
                                    }
                                }, 2000);
                            });
                            openBtn.dataset.listenerAdded = 'true';
                        }
                    }
                    
                    if (phonepeBtn) {
                        phonepeBtn.href = data.phonepe_link;
                        if (isMobile) phonepeBtn.style.display = 'inline-block';
                        
                        if (!phonepeBtn.dataset.listenerAdded) {
                            phonepeBtn.addEventListener('click', function(e) {
                                const start = Date.now();
                                setTimeout(() => {
                                    if (Date.now() - start < 2500) {
                                        alert("If PhonePe didn't open, please use the standard 'Open UPI app' button or scan the QR code.");
                                    }
                                }, 2000);
                            });
                            phonepeBtn.dataset.listenerAdded = 'true';
                        }
                    }

                    if (gpayBtn) {
                        gpayBtn.href = data.gpay_link;
                        if (isMobile) gpayBtn.style.display = 'inline-block';
                    }

                    const toggleQrBtn = card.querySelector('.toggle-qr');
                    const paidConfirmBtn = card.querySelector('.paid-confirm-btn');
                    
                    const toggleView = (showQr) => {
                        const qrCont = card.querySelector('.qr-container');
                        const details = card.querySelector('.payment-details');
                        if (showQr) {
                            qrCont.style.display = 'block';
                            details.style.display = 'none';
                            if(toggleQrBtn) toggleQrBtn.textContent = 'Hide QR Code';
                        } else {
                            qrCont.style.display = 'none';
                            details.style.display = 'block';
                            if(toggleQrBtn) toggleQrBtn.textContent = 'Show QR Code';
                        }
                    };

                    if (toggleQrBtn) {
                        toggleQrBtn.style.display = 'inline-block';
                        if (!toggleQrBtn.dataset.listenerAdded) {
                            toggleQrBtn.addEventListener('click', function() {
                                const isHidden = card.querySelector('.qr-container').style.display === 'none';
                                toggleView(isHidden);
                            });
                            toggleQrBtn.dataset.listenerAdded = 'true';
                        }
                    }

                    if (paidConfirmBtn && !paidConfirmBtn.dataset.listenerAdded) {
                        paidConfirmBtn.addEventListener('click', function() {
                            toggleView(false); // Hide QR, show form
                        });
                        paidConfirmBtn.dataset.listenerAdded = 'true';
                    }

                    // Update UPI ID text
                    const upiSpan = card.querySelector('.upi-id');
                    if (upiSpan) {
                        upiSpan.textContent = data.upi_id;
                    }

                    // Setup Copy functionality
                    const copyBtn = card.querySelector('.copy-upi-btn');
                    if (copyBtn && !copyBtn.dataset.listenerAdded) {
                        copyBtn.addEventListener('click', function() {
                            const upiText = card.querySelector('.upi-id').textContent;
                            navigator.clipboard.writeText(upiText).then(() => {
                                const originalHTML = copyBtn.innerHTML;
                                copyBtn.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                      <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.42-6.446a.24.24 0 0 1 .012-.012z"/>
                                    </svg>`;
                                copyBtn.classList.replace('btn-outline-light', 'btn-success');
                                setTimeout(() => {
                                    copyBtn.innerHTML = originalHTML;
                                    copyBtn.classList.replace('btn-success', 'btn-outline-light');
                                }, 2000);
                            });
                        });
                        copyBtn.dataset.listenerAdded = 'true';
                    }

                    // Hide arrows when a plan is selected
                    document.querySelectorAll('.slider-arrow').forEach(a => a.style.display = 'none');
                    
                    // flip and hide others
                    card.classList.add('flipped');
                    hideOthers(card);
                } else {
                    if (data.message === 'Not authenticated') {
                        // redirect to login so user can authenticate first
                        window.location = '<?= BASE_URL ?>/auth/login';
                        return;
                    }
                    alert(data.message || 'Unable to create payment');
                }
            });
        });

        // Slider Navigation Logic
        const container = document.getElementById('plansContainer');
        const prevBtn = document.querySelector('.arrow-prev');
        const nextBtn = document.querySelector('.arrow-next');

        if (container && prevBtn && nextBtn) {
            const scrollAmount = 320; // card width + gap
            
            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            
            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });

            // Auto-hide arrows based on scroll position
            container.addEventListener('scroll', () => {
                prevBtn.style.opacity = container.scrollLeft <= 5 ? '0.3' : '1';
                nextBtn.style.opacity = (container.scrollLeft + container.clientWidth >= container.scrollWidth - 5) ? '0.3' : '1';
            });
        }

        // Auto-open plan if requested via ?plan=1m
        const preselect = '<?= $preselect ?? '' ?>';
        if (preselect) {
            const btn = document.querySelector(`.subscribe-btn[data-plan="${preselect}"]`);
            if (btn) {
                setTimeout(()=> btn.click(), 150);
                // clean the url
                history.replaceState(null, '', '<?= BASE_URL ?>/payment/index');
            }
        }

        // Cancel button
        document.querySelectorAll('.cancel-btn').forEach(b=>{
            b.addEventListener('click', function(e){
                showAll();
            });
        });

        // click outside to reset
        document.addEventListener('click', function(e){
            if (!e.target.closest('.plan-card')) {
                showAll();
            }
        });
    })();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>