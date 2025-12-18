<?= $this->extend('working_template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-upload"></i> Upload Material
                        <small class="text-muted">- <?= esc($course['title']) ?></small>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/admin/course/' . $course_id . '/upload') ?>" 
                          method="post" 
                          enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="material_file" class="form-label fw-bold">
                                <i class="bi bi-file-earmark"></i> Select File
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="material_file" 
                                   name="material_file" 
                                   required
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar">
                            <div class="form-text">
                                Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR
                                Maximum file size: 10MB
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Courses
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Existing Materials -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-folder"></i> Existing Materials
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $materialModel = new \App\Models\MaterialModel();
                    $materials = $materialModel->getMaterialsByCourse($course_id);
                    ?>
                    
                    <?php if (empty($materials)): ?>
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">No materials uploaded yet for this course.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($materials as $material): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="bi bi-file-earmark"></i> 
                                            <?= esc($material['file_name']) ?>
                                        </h6>
                                        <small class="text-muted">
                                            Uploaded on <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="<?= base_url('/materials/delete/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this material?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
