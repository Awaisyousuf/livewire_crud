<!-- resources/views/livewire/blog-manager.blade.php -->
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Blog Management</h2>
            <div>
                <button wire:click="showList" 
                        class="btn btn-sm {{ $currentScreen === 'list' ? 'btn-light' : 'btn-outline-light' }}">
                    <i class="fas fa-list me-1"></i> Blog List
                </button>
                <button wire:click="showCreate"
                        class="btn btn-sm {{ $currentScreen === 'create' ? 'btn-light' : 'btn-outline-light' }}">
                    <i class="fas fa-plus me-1"></i> Create New
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($currentScreen === 'list')
                <!-- Blog List Screen -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Featured Image</th>
                                <th>Published Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $blog->title }}</div>
                                        <div class="text-muted small">{{ Str::limit($blog->excerpt, 60) }}</div>
                                    </td>
                                    <td>
                                        @if($blog->featured_image)
                                            <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                                                 alt="Featured Image" 
                                                 class="img-thumbnail" 
                                                 style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $blog->published_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:change="toggleStatus({{ $blog->id }})"
                                                   id="status-{{ $blog->id }}"
                                                   {{ $blog->status ? 'checked' : '' }}>
                                            <label class="form-check-label small" 
                                                   for="status-{{ $blog->id }}">
                                                {{ $blog->status ? 'Published' : 'Draft' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button wire:click="showEdit({{ $blog->id }})" 
                                                class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $blog->id }})" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} of {{ $blogs->total() }} entries</div>
                    <div>{{ $blogs->links() }}</div>
                </div>
                
            @else
                <!-- Create/Edit Form Screen -->
                <form wire:submit.prevent="{{ $currentScreen === 'create' ? 'create' : 'update' }}">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" wire:model="title" class="form-control form-control-lg">
                                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" wire:model="slug" class="form-control">
                                @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <div wire:ignore>
                                    <textarea id="editor" wire:model="content" class="form-control"></textarea>
                                </div>
                                @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Featured Image</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        @if($featured_image)
                                            <img src="{{ $featured_image->temporaryUrl() }}" 
                                                 class="img-fluid rounded mb-3">
                                        @elseif($existing_image)
                                            <img src="{{ asset('storage/' . $existing_image) }}" 
                                                 class="img-fluid rounded mb-3">
                                        @endif
                                        
                                        <input type="file" wire:model="featured_image" class="form-control">
                                        @error('featured_image') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Publish Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:model="status"
                                                   id="statusSwitch">
                                            <label class="form-check-label" 
                                                   for="statusSwitch">
                                                {{ $status ? 'Published' : 'Draft' }}
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Publish Date/Time</label>
                                        <input type="datetime-local" wire:model="published_at" class="form-control">
                                        @error('published_at') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            {{ $currentScreen === 'create' ? 'Create Blog' : 'Update Blog' }}
                                        </button>
                                        <button type="button" wire:click="showList" 
                                                class="btn btn-outline-secondary mt-2">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Additional Info</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Excerpt</label>
                                        <textarea wire:model="excerpt" rows="3" class="form-control"></textarea>
                                        <div class="form-text">A short description of your blog</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tags</label>
                                        <input type="text" wire:model="tags" class="form-control">
                                        <div class="form-text">Separate tags with commas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@push('styles')
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    document.addEventListener('livewire:load', function () {
        initializeEditor();
    });
    
    Livewire.hook('message.processed', (message, component) => {
        initializeEditor();
    });
    
    function initializeEditor() {
        if ($('#editor').length > 0 && !$('#editor').hasClass('summernote-initialized')) {
            $('#editor').summernote({
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onChange: function(contents) {
                        @this.set('content', contents);
                    },
                    onBlur: function() {
                        @this.set('content', $('#editor').summernote('code'));
                    }
                }
            });
            
            // Set existing content
            if (@this.content) {
                $('#editor').summernote('code', @this.content);
            }
            
            $('#editor').addClass('summernote-initialized');
        }
    }
</script>
@endpush