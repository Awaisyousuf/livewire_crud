<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Title</th>
            <th>Tags</th>
            <th>Status</th>
            <th>Published At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blogs as $blog)
            <tr>
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->tags }}</td>
                <td>{{ $blog->status ? 'Published' : 'Draft' }}</td>
                <td>{{ $blog->published_at }}</td>
                <td>
                    <button class="btn btn-sm btn-primary" wire:click="edit({{ $blog->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $blog->id }})">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $blogs->links() }}
<button class="btn btn-primary mb-3" wire:click="create">+ Create Blog</button>
@if ($showForm)
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input wire:model.defer="title" class="form-control">
        </div>
        <div class="mb-6" wire:ignore>
            <input id="content" type="hidden" placeholder="content.." value="{{ $this->content }}" wire:model="content">
            <trix-editor input="content" class="trix-content"></trix-editor>
            @error('content')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror

            @script
            <script>
                let trixEditor = document.getElementById("content")
                addEventListener("trix-blur", function (event) {
                    @this.set('content', trixEditor.getAttribute('value'))
                })
            </script>
            @endscript
        </div>

        <div class="mb-3">
            <label class="form-label">Excerpt</label>
            <textarea wire:model.defer="excerpt" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tags</label>
            <input wire:model.defer="tags" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Published At</label>
            <input type="datetime-local" wire:model.defer="published_at" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" wire:model.defer="status" class="form-check-input" id="status">
            <label for="status" class="form-check-label">Published</label>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" wire:click="resetForm">Cancel</button>
    </form>