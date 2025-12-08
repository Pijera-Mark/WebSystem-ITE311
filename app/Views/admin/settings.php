<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2><?= $pageTitle ?></h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>System Settings</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/settings') ?>">
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" class="form-control" name="site_name" value="<?= $settings['site_name'] ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Site Description</label>
                            <textarea class="form-control" name="site_description" rows="3"><?= $settings['site_description'] ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="maintenance_mode" <?= $settings['maintenance_mode'] ? 'checked' : '' ?>>
                                <label class="form-check-label">Maintenance Mode</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_registration" <?= $settings['allow_registration'] ? 'checked' : '' ?>>
                                <label class="form-check-label">Allow User Registration</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_notifications" <?= $settings['email_notifications'] ? 'checked' : '' ?>>
                                <label class="form-check-label">Email Notifications</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>System Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>CodeIgniter Version:</strong> 4.6.3</p>
                    <p><strong>PHP Version:</strong> <?= PHP_VERSION ?></p>
                    <p><strong>Database:</strong> MySQL</p>
                    <p><strong>Environment:</strong> Development</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
