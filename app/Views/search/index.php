<?= $this->extend('working_template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Search Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title text-center mb-4">
                        <i class="bi bi-search"></i> Search Courses and Materials
                    </h1>
                    
                    <!-- Search Form -->
                    <form method="GET" action="<?= base_url('search') ?>" id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           name="q" 
                                           id="searchInput"
                                           value="<?= esc($query) ?>"
                                           placeholder="Search for courses, materials..."
                                           autocomplete="off">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                                
                                <!-- Suggestions Dropdown -->
                                <div id="searchSuggestions" class="dropdown-menu w-100" style="display: none;">
                                    <!-- Suggestions will be loaded here -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg" name="type" id="searchType">
                                    <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All Content</option>
                                    <option value="courses" <?= $type === 'courses' ? 'selected' : '' ?>>Courses Only</option>
                                    <?php if (session()->get('isLoggedIn')): ?>
                                        <option value="materials" <?= $type === 'materials' ? 'selected' : '' ?>>Materials Only</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Quick Filters -->
                    <div class="mt-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="programming">
                                <i class="bi bi-code-slash"></i> Programming
                            </button>
                            <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="web">
                                <i class="bi bi-globe"></i> Web
                            </button>
                            <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="database">
                                <i class="bi bi-database"></i> Database
                            </button>
                            <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="design">
                                <i class="bi bi-palette"></i> Design
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Search Results -->
            <?php if (!empty($query)): ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> Search Results
                        </h5>
                        <span class="badge bg-primary">
                            <?= $totalResults ?> result<?= $totalResults !== 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if ($totalResults === 0): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
                                <h4 class="mt-3 text-muted">No results found</h4>
                                <p class="text-muted">
                                    Try searching with different keywords or check your spelling.
                                </p>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Suggestions: Try more general terms, check for typos, or browse all courses.
                                    </small>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Results Container -->
                            <div id="searchResults">
                                <?php if (isset($results['courses']) && !empty($results['courses'])): ?>
                                    <div class="mb-4">
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-book"></i> Courses (<?= count($results['courses']) ?>)
                                        </h6>
                                        <div class="row course-results">
                                            <?php foreach ($results['courses'] as $course): ?>
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card h-100 search-item" data-type="course">
                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <i class="bi bi-book text-primary"></i>
                                                                <?= esc($course['title']) ?>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                <?= esc(substr($course['description'], 0, 100)) ?>...
                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <?= date('M d, Y', strtotime($course['created_at'])) ?>
                                                                </small>
                                                                <a href="<?= base_url($course['url']) ?>" class="btn btn-sm btn-outline-primary">
                                                                    View Course
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($results['materials']) && !empty($results['materials'])): ?>
                                    <div class="mb-4">
                                        <h6 class="text-success mb-3">
                                            <i class="bi bi-file-earmark"></i> Materials (<?= count($results['materials']) ?>)
                                        </h6>
                                        <div class="list-group material-results">
                                            <?php foreach ($results['materials'] as $material): ?>
                                                <div class="list-group-item search-item" data-type="material">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                <i class="bi bi-file-earmark text-success"></i>
                                                                <?= esc($material['title']) ?>
                                                            </h6>
                                                            <p class="mb-1 text-muted small">
                                                                <?= esc($material['description']) ?>
                                                            </p>
                                                            <small class="text-muted">
                                                                <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                                            </small>
                                                        </div>
                                                        <a href="<?= base_url($material['url']) ?>" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Search Tips -->
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-lightbulb" style="font-size: 2rem; color: #ffc107;"></i>
                        <h4 class="mt-3">Start Searching</h4>
                        <p class="text-muted">
                            Enter keywords above to search through courses and materials.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6><i class="bi bi-book"></i> Search Courses</h6>
                                <small class="text-muted">Find courses by title or description</small>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="bi bi-file-earmark"></i> Search Materials</h6>
                                <small class="text-muted">Find files from your enrolled courses</small>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="bi bi-funnel"></i> Filter Results</h6>
                                <small class="text-muted">Narrow down by content type</small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.search-item {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.search-item:hover {
    border-left-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.quick-filter:hover {
    background-color: #007bff;
    color: white;
}

#searchSuggestions {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-top: none;
}
</style>

<!-- jQuery for Search Functionality -->
<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#searchInput');
    const searchSuggestions = $('#searchSuggestions');
    const searchForm = $('#searchForm');
    
    // Search suggestions with debouncing
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchSuggestions.hide().empty();
            return;
        }
        
        searchTimeout = setTimeout(function() {
            fetchSuggestions(query);
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.input-group').length) {
            searchSuggestions.hide();
        }
    });
    
    // Quick filter buttons
    $('.quick-filter').on('click', function() {
        const filter = $(this).data('filter');
        searchInput.val(filter);
        searchForm.submit();
    });
    
    // Client-side filtering for existing results
    $('#searchType').on('change', function() {
        filterResults($(this).val());
    });
    
    function fetchSuggestions(query) {
        $.ajax({
            url: '/search/suggestions',
            method: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.suggestions.length > 0) {
                    displaySuggestions(response.suggestions);
                } else {
                    searchSuggestions.hide().empty();
                }
            },
            error: function() {
                searchSuggestions.hide().empty();
            }
        });
    }
    
    function displaySuggestions(suggestions) {
        searchSuggestions.empty();
        
        suggestions.forEach(function(suggestion) {
            const icon = suggestion.type === 'course' ? 
                '<i class="bi bi-book text-primary"></i>' : 
                '<i class="bi bi-file-earmark text-success"></i>';
            
            const item = `
                <a href="${suggestion.url}" class="dropdown-item suggestion-item">
                    <div class="d-flex align-items-center">
                        <div class="me-2">${icon}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${suggestion.title}</div>
                            <small class="text-muted">${suggestion.description}</small>
                        </div>
                    </div>
                </a>
            `;
            searchSuggestions.append(item);
        });
        
        searchSuggestions.show();
    }
    
    function filterResults(type) {
        const courseResults = $('.course-results .search-item');
        const materialResults = $('.material-results .search-item');
        
        if (type === 'courses') {
            courseResults.show();
            materialResults.hide();
            $('.material-results').parent().hide();
            $('.course-results').parent().show();
        } else if (type === 'materials') {
            courseResults.hide();
            materialResults.show();
            $('.course-results').parent().hide();
            $('.material-results').parent().show();
        } else {
            courseResults.show();
            materialResults.show();
            $('.course-results').parent().show();
            $('.material-results').parent().show();
        }
    }
    
    // Highlight search terms in results
    function highlightSearchTerms() {
        const query = searchInput.val().trim();
        if (query.length < 2) return;
        
        const regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        
        $('.search-item h6, .search-item .card-text, .search-item .list-group-item').each(function() {
            const $this = $(this);
            const text = $this.text();
            if (regex.test(text)) {
                $this.html(text.replace(regex, '<mark>$1</mark>'));
            }
        });
    }
    
    // Apply highlighting on page load if there's a query
    <?php if (!empty($query)): ?>
        highlightSearchTerms();
    <?php endif; ?>
    
    // Live search for existing results (client-side)
    searchInput.on('input', function() {
        const query = $(this).val().trim().toLowerCase();
        
        if (query.length < 2) {
            $('.search-item').show();
            return;
        }
        
        $('.search-item').each(function() {
            const $item = $(this);
            const text = $item.text().toLowerCase();
            
            if (text.includes(query)) {
                $item.show();
            } else {
                $item.hide();
            }
        });
        
        // Update result count
        const visibleCount = $('.search-item:visible').length;
        $('.badge.bg-primary').text(visibleCount + ' result' + (visibleCount !== 1 ? 's' : ''));
    });
});
</script>
<?= $this->endSection() ?>
