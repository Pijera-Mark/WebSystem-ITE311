<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">About Our Project</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Project Overview
                    </h5>
                    <p class="card-text">
                        This is a comprehensive web system developed as part of ITE311 - Web System Development course. 
                        The project demonstrates the implementation of modern web development practices using CodeIgniter 4 framework.
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tools text-primary me-2"></i>
                        Technologies Used
                    </h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success me-2"></i> CodeIgniter 4 Framework</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> PHP 8.1+</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Bootstrap 5</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> HTML5 & CSS3</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> JavaScript</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> MySQL Database</li>
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-diagram-3 text-primary me-2"></i>
                        MVC Architecture
                    </h5>
                    <p class="card-text">
                        The application follows the Model-View-Controller (MVC) architectural pattern, ensuring 
                        clean separation of concerns and maintainable code structure.
                    </p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url() ?>" class="btn btn-primary me-3">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
                <a href="<?= base_url('contact') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
