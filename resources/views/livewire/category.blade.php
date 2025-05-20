<div>
    <div class="col-md-8 mb-2">
        <div class="card">
            <div class="card-body">
                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($updateCategory)
                    @include('livewire.update')
                @else
                    @include('livewire.create')
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        <button wire:click="edit({{ $category->id }})" class="btn btn-primary btn-sm">Edit</button>
                                        <button type="button" wire:click="destroy({{$category->id}})"  wire:confirm="Are you sure you want to delete this post?" class="btn btn-danger btn-sm">Delete</button>




                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Categories Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $categories->links('livewire::bootstrap') }} <!-- Livewire pagination links -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
