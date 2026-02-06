<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12 px-4">
        <div class="text-center mb-4">
            <h2 class="text-warning">Choose a Plan</h2>
            <p class="text-muted">Select one of our plans and scan the UPI QR to pay. After payment, submit the transaction id.</p>
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
                                <?php if (!empty($hasActiveVerifiedSubscription)): ?>
                                    <button class="btn btn-secondary" disabled>Active Subscription</button>
                                <?php else: ?>
                                    <div class="btn btn-success subscribe-btn" data-plan="<?= htmlspecialchars($key) ?>" style="cursor:pointer;">Subscribe</div>
                                <?php endif; ?>
                            </div>

                            <div class="card-back card-body p-3 text-center" style="display: none;">
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
                                        <input type="hidden" name="plan_id" value="">
                                        <input type="hidden" name="plan_key" value="">
                                        <div class="mb-2">
                                            <input class="form-control form-control-sm" name="payer_upi" placeholder="UPI ID used for payment">
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-control form-control-sm" name="account_holder_name" placeholder="Account Holder Name">
                                        </div>
                                        <div class="mb-2">
                                            <input type="date" class="form-control form-control-sm" name="transaction_date" required value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-control form-control-sm" name="utr" placeholder="Enter UTR" required>
                                            <span class="utr-error text-danger small" style="display:none; margin-top:4px;"></span>
                                        </div>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-primary btn-sm submit-utr-btn">I Paid</button>
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
                const subBtn = c.querySelector('.subscribe-btn'); if(subBtn) subBtn.style.display='';
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

        // Debug: Listen for all form submissions to catch rogue ones
        window.addEventListener('submit', function(e) {
            console.log('Form submission caught from:', e.target);
            // If it's not the verify-form, it shouldn't be submitting!
            if (!e.target.classList.contains('verify-form')) {
                console.warn('Blocking unexpected form submission:', e.target);
                e.preventDefault(); 
            }
        }, true);

        document.querySelectorAll('.subscribe-btn').forEach(btn=>{
            btn.addEventListener('click', async function(e){
                e.preventDefault();
                e.stopPropagation();
                
                // Hide subscribe button
                btn.style.display = 'none';

                const card = e.target.closest('.plan-card');
                const planKey = e.target.dataset.plan;

                // Flip immediately and hide others
                card.classList.add('flipped');
                hideOthers(card);
                
                // Hide arrows when a plan is selected
                document.querySelectorAll('.slider-arrow').forEach(a => a.style.display = 'none');

                // Show a loading message in the back details if needed
                const back = card.querySelector('.card-back');
                back.style.display = 'block';
                const qrCont = card.querySelector('.qr-container');
                const details = card.querySelector('.payment-details');
                
                // Temporarily hide details until results come in
                details.style.display = 'none';
                qrCont.style.display = 'block';
                const qrImg = card.querySelector('.qr-img');
                const originalQrSrc = qrImg.src;
                qrImg.style.opacity = '0.5';
                
                // call backend to get QR code WITHOUT creating payment record
                const res = await fetch('<?= BASE_URL ?>/payment/get_qr', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({plan: planKey})
                });

                const data = await res.json();
                qrImg.style.opacity = '1';

                if (data.status === 'success') {
                    // use QR URL from response
                    const qrUrl = data.qr_url || '';
                    qrImg.src = qrUrl;
                    // Store plan info for later use (when "I Paid" is clicked)
                    if (data.plan_id) {
                        card.querySelector('input[name=plan_id]').value = data.plan_id;
                    }
                    if (data.plan_key) {
                        card.querySelector('input[name=plan_key]').value = data.plan_key;
                    }
                    // IMPORTANT: DO NOT set payment_id here - it will be created when "I Paid" is clicked
                    // Clear any existing payment_id to be safe
                    card.querySelector('input[name=payment_id]').value = '';

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

                    // Keep QR view visible so user can scan it
                    toggleView(true);
                } else {
                    if (data.message === 'Not authenticated') {
                        // redirect to login so user can authenticate first
                        window.location = '<?= BASE_URL ?>/auth/login';
                        return;
                    }
                    alert(data.message || 'Unable to create payment');
                    // Flip back on error
                    showAll();
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

        // Intercept verify form submit and use requestSubmit with an allow flag
        document.querySelectorAll('.verify-form').forEach(form => {
            // submit handler: allow submission only if flag set
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // ALWAYS block native submit
                runValidationAndSubmit();
            });


            const runValidationAndSubmit = async function() {
                const utrInput = form.querySelector('input[name=utr]');
                const payerUpiInput = form.querySelector('input[name=payer_upi]');
                const payerUpi = (payerUpiInput && payerUpiInput.value || '').trim();
                const accountHolderInput = form.querySelector('input[name=account_holder_name]');
                const accountHolder = (accountHolderInput && accountHolderInput.value || '').trim();
                const paymentId = form.querySelector('input[name=payment_id]').value;
                const planKey = form.querySelector('input[name=plan_key]').value;
                const utr = (utrInput && utrInput.value || '').trim();
                const errSpan = form.querySelector('.utr-error');

                function setUtrError(msg) {
                    if (errSpan) {
                        if (msg) {
                            errSpan.style.display = 'block';
                            errSpan.textContent = msg;
                        } else {
                            errSpan.style.display = 'none';
                            errSpan.textContent = '';
                        }
                    }
                }

                if (!payerUpi) {
                    setUtrError('Please enter your UPI ID.');
                    return;
                }

                if (!/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/.test(payerUpi)) {
                    setUtrError('Invalid UPI ID format (e.g. user@bank).');
                    return;
                }

                if (!accountHolder) {
                    setUtrError('Please enter Account Holder Name.');
                    return;
                }

                if (!/^[a-zA-Z\s]+$/.test(accountHolder)) {
                    setUtrError('Account Holder Name must contain only alphabets.');
                    return;
                }

                if (!utr) {
                    setUtrError('Please enter UTR.');
                    return;
                }

                if (!/^\d+$/.test(utr)) {
                    setUtrError('UTR must contain only numbers.');
                    return;
                }

                // Validate Date (Client-side)
                const dateInput = form.querySelector('input[name=transaction_date]');
                if (dateInput && dateInput.value) {
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const day = String(today.getDate()).padStart(2, '0');
                    const todayStr = `${year}-${month}-${day}`;

                    if (dateInput.value > todayStr) {
                        setUtrError('Transaction date cannot be in the future.');
                        return;
                    }
                }

                // clear previous error while checking
                setUtrError('');

                // check uniqueness server-side (payment_id may be empty - will be created in verify)
                const fd = new FormData();
                fd.append('utr', utr);
                fd.append('payment_id', paymentId || '');

                fetch('<?= BASE_URL ?>/payment/check_utr', {
                    method: 'POST',
                    body: fd
                }).then(r => r.json()).then(data => {
                    if (data.status === 'success') {
                        if (data.available) {
                            // Submit form - verify() will create payment record with UTR
                            form.submit();
                        } else {
                            setUtrError('This transaction id has already been used. Please verify your transaction id.');
                        }
                    } else {
                        setUtrError(data.message || 'Unable to validate transaction id. Try again.');
                    }
                }).catch(err => {
                    console.error(err);
                    setUtrError('Unable to validate transaction id. Try again later.');
                });
            };

            // Click handler for the explicit button
            const btn = form.querySelector('.submit-utr-btn');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // start validation which will call requestSubmit when OK
                    runValidationAndSubmit();
                });
            }

            // Clear inline error when user types
            form.querySelectorAll('input[name=utr]').forEach(inp => {
                inp.addEventListener('input', function() {
                    const err = form.querySelector('.utr-error');
                    if (err) { err.style.display = 'none'; err.textContent = ''; }
                });
            });
        });

        // click outside to reset
        document.addEventListener('click', function(e){
            if (!e.target.closest('.plan-card')) {
                showAll();
            }
        });

        // Initialize state to ensure back of cards are hidden
        showAll();
    })();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>