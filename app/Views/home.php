<<<<<<< HEAD
<div class="container mt-5">
    <h1 class="text-center">Welcome to CodeIgniter</h1>
    <p class="text-center">This is your Home page content.</p>
</div>
=======
<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to ITE311</h1>
                <p class="lead mb-4">Web System Development with CodeIgniter 4 and Bootstrap 5</p>
                <a href="#features" class="btn btn-light btn-lg">Learn More</a>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-code-slash" style="font-size: 8rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Key Features</h2>
                <p class="lead">Modern web development with powerful tools</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-lightning-charge text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Fast & Lightweight</h5>
                        <p class="card-text">CodeIgniter 4 provides excellent performance with minimal overhead, perfect for rapid web development.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-phone text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Responsive Design</h5>
                        <p class="card-text">Bootstrap 5 ensures your application looks great on all devices, from mobile to desktop.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check text-warning mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Secure & Reliable</h5>
                        <p class="card-text">Built-in security features and best practices to keep your application safe and secure.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">About This Project</h2>
                <p class="lead">This is a laboratory exercise for ITE311 - Web System Development course.</p>
                <p>The project demonstrates the integration of CodeIgniter 4 framework with Bootstrap 5 for creating modern, responsive web applications.</p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-primary">CodeIgniter 4</h4>
                            <p class="small">PHP Framework</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-primary">Bootstrap 5</h4>
                            <p class="small">CSS Framework</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-gear-fill text-primary" style="font-size: 6rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-6 fw-bold mb-4">Ready to Get Started?</h2>
                <p class="lead mb-4">Explore the power of modern web development with CodeIgniter 4 and Bootstrap 5.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="https://codeigniter.com/user_guide/" class="btn btn-primary btn-lg me-md-2" target="_blank">
                        <i class="bi bi-book"></i> CodeIgniter Docs
                    </a>
                    <a href="https://getbootstrap.com/docs/" class="btn btn-outline-primary btn-lg" target="_blank">
                        <i class="bi bi-bootstrap"></i> Bootstrap Docs
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
>>>>>>> 23e0973a899deb3ad5683eced5a25bd250639e2d
