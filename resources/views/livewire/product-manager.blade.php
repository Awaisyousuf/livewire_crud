<!-- resources/views/livewire/product-manager.blade.php -->
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Product Management</h2>
            <div>
                <button wire:click="showList" 
                        class="btn btn-sm {{ $currentScreen === 'list' ? 'btn-light' : 'btn-outline-light' }}">
                    <i class="fas fa-list me-1"></i> Product List
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
                <!-- Product List Screen -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <div class="text-muted small">{{ Str::limit($product->description, 50) }}</div>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($product->discounted_price)
                                            <span class="text-danger"><del>${{ number_format($product->price, 2) }}</del></span>
                                            <span class="fw-bold">${{ number_format($product->discounted_price, 2) }}</span>
                                        @else
                                            <span>${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:change="toggleStock({{ $product->id }})"
                                                   id="stock-{{ $product->id }}"
                                                   {{ $product->in_stock === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label small" 
                                                   for="stock-{{ $product->id }}">
                                                {{ $product->in_stock === 'yes' ? 'In Stock' : 'Out of Stock' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:change="toggleStatus({{ $product->id }})"
                                                   id="status-{{ $product->id }}"
                                                   {{ $product->status ? 'checked' : '' }}>
                                            <label class="form-check-label small" 
                                                   for="status-{{ $product->id }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button wire:click="showEdit({{ $product->id }})" 
                                                class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $product->id }})" 
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
                    <div>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries</div>
                    <div>{{ $products->links() }}</div>
                </div>
                
            @else
                <!-- Create/Edit Form Screen -->
                <form wire:submit.prevent="{{ $currentScreen === 'create' ? 'create' : 'update' }}">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="name" class="form-control form-control-lg">
                                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="slug" class="form-control">
                                        @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Category <span class="text-danger">*</span></label>
                                            <select wire:model="category_id" class="form-select">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->cat_id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <select wire:model="brand_id" class="form-select">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Tags</label>
                                        <input type="text" wire:model="tags" class="form-control">
                                        <div class="form-text">Separate tags with commas</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <div wire:ignore>
                                            <textarea id="description-editor" wire:model="description" class="form-control"></textarea>
                                        </div>
                                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Specifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <input type="text" wire:model="spec_key" 
                                                   class="form-control" placeholder="Specification Key">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" wire:model="spec_value" 
                                                   class="form-control" placeholder="Specification Value">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" wire:click="addSpec" 
                                                    class="btn btn-primary w-100">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Key</th>
                                                    <th>Value</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($specs as $key => $value)
                                                    <tr>
                                                        <td>{{ $key }}</td>
                                                        <td>{{ $value }}</td>
                                                        <td>
                                                            <button type="button" 
                                                                    wire:click="removeSpec('{{ $key }}')"
                                                                    class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No specifications added
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Pricing</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" wire:model="price" class="form-control">
                                        @error('price') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Discounted Price ($)</label>
                                        <input type="number" step="0.01" wire:model="discounted_price" class="form-control">
                                        @error('discounted_price') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Inventory & Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Stock Status <span class="text-danger">*</span></label>
                                        <select wire:model="in_stock" class="form-select">
                                            <option value="yes">In Stock</option>
                                            <option value="no">Out of Stock</option>
                                        </select>
                                        @error('in_stock') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Product Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   wire:model="status"
                                                   id="statusSwitch">
                                            <label class="form-check-label" 
                                                   for="statusSwitch">
                                                {{ $status ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ $currentScreen === 'create' ? 'Create Product' : 'Update Product' }}
                                </button>
                                <button type="button" wire:click="showList" 
                                        class="btn btn-outline-secondary">
                                    Cancel
                                </button>
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
        initDescriptionEditor();
    });
    
    Livewire.hook('message.processed', (message, component) => {
        initDescriptionEditor();
    });
    
    function initDescriptionEditor() {
        const editorId = '#description-editor';
        if ($(editorId).length > 0 && !$(editorId).hasClass('summernote-initialized')) {
            $(editorId).summernote({
                height: 300,
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
                        @this.set('description', contents);
                    },
                    onBlur: function() {
                        @this.set('description', $(editorId).summernote('code'));
                    }
                }
            });
            
            // Set existing content
            if (@this.description) {
                $(editorId).summernote('code', @this.description);
            }
            
            $(editorId).addClass('summernote-initialized');
        }
    }
</script>
@endpush