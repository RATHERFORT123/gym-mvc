<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --footer-bg: rgba(255, 255, 255, 0.8);
        --text-muted: #636e72;
    }

    .custom-footer {
        background: var(--footer-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-top: 3px solid var(--bhagua);
        padding: 60px 0 30px 0;
        color: #2d3436;
        position: relative;
        overflow: hidden;
    }

    /* Decorative Bhagua Glow */
    .custom-footer::after {
        content: '';
        position: absolute;
        top: -150px;
        right: -150px;
        width: 300px;
        height: 300px;
        background: rgba(255, 153, 51, 0.05);
        border-radius: 50%;
        filter: blur(80px);
        z-index: 0;
    }

    .footer-brand {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        letter-spacing: 1px;
        color: #1e272e;
        margin-bottom: 1.5rem;
    }

    .footer-brand span {
        color: var(--bhagua);
    }

    .footer-description {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 400px;
        margin: 0 auto 2rem auto;
        line-height: 1.6;
    }

    /* Social Link Styling */
    .social-wrapper {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 2rem;
    }

    .social-link {
        color: #1e272e;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 8px 16px;
        border-radius: 50px;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: var(--bhagua);
        color: #ffffff !important;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 153, 51, 0.3);
    }

    .footer-bottom {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding-top: 25px;
        margin-top: 20px;
    }

    .copyright-text {
        color: #b2bec3;
        font-size: 0.85rem;
    }
</style>

</main>

<footer class="custom-footer mt-auto">
    <div class="container text-center position-relative" style="z-index: 1;">
        
        <h4 class="footer-brand">SGSIT <span>GYM</span></h4>
        
        <p class="footer-description">
            Empowering students with strength and fitness. 
            Providing a world-class training environment within the SGSITS campus.
        </p>

        <div class="social-wrapper">
            <a href="#" class="social-link">Instagram</a>
            <a href="#" class="social-link">Facebook</a>
            <a href="#" class="social-link">Twitter</a>
        </div>

        <div class="footer-bottom">
            <small class="copyright-text">
                &copy; <?= date('Y') ?> <strong>SGSIT Indore</strong>. All rights reserved.
            </small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>