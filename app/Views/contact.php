<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">Contact Us</h1>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-envelope text-primary me-2"></i>
                                Get in Touch
                            </h5>
                            <p class="card-text">
                                Have questions about our project or need assistance? 
                                Feel free to reach out to us through any of the following channels.
                            </p>
                            
                            <div class="mt-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                                    <div>
                                        <strong>Email</strong><br>
                                        <small class="text-muted">contact@ite311-project.com</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-phone text-primary me-3"></i>
                                    <div>
                                        <strong>Phone</strong><br>
                                        <small class="text-muted">+1 (555) 123-4567</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-geo-alt text-primary me-3"></i>
                                    <div>
                                        <strong>Address</strong><br>
                                        <small class="text-muted">123 University Ave, City, State 12345</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-chat-dots text-primary me-2"></i>
                                Send Message
                            </h5>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= base_url() ?>" class="btn btn-primary me-3">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
                <a href="<?= base_url('about') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-info-circle me-2"></i>About Us
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
