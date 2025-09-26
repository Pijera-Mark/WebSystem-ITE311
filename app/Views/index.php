<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">Welcome to ITE311 Web System</h1>
        <p class="lead mb-4">A modern web application built with CodeIgniter 4 and Bootstrap 5</p>
        <a href="<?= base_url('about') ?>" class="btn btn-light btn-lg me-3">Learn More</a>
        <a href="<?= base_url('contact') ?>" class="btn btn-outline-light btn-lg">Contact Us</a>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-code-slash display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Modern Framework</h5>
                    <p class="card-text">Built with CodeIgniter 4, a powerful PHP framework for rapid web development.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-bootstrap display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Responsive Design</h5>
                    <p class="card-text">Beautiful, mobile-first design using Bootstrap 5 for optimal user experience.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-gear display-4 text-primary mb-3"></i>
                    <h5 class="card-title">MVC Architecture</h5>
                    <p class="card-text">Clean separation of concerns with Model-View-Controller pattern.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
