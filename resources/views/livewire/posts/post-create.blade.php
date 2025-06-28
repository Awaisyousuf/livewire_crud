<div class="container">
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>Title</label>
            <input wire:model="title" class="form-control" />
        </div>

        <div class="mb-3">
            <label>Slug</label>
            <input wire:model="slug" class="form-control" />
        </div>

        <div class="mb-3">
            <label>Excerpt</label>
            <textarea wire:model="excerpt" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <x-trix input-id="content" wire:model.defer="content" />
            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Tags</label>
            <input wire:model="tags" class="form-control" />
        </div>

        <div class="mb-3">
            <label>Published At</label>
            <input type="datetime-local" wire:model="published_at" class="form-control" />
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" wire:model="status" id="status">
            <label class="form-check-label" for="status">Published</label>
        </div>

        <button class="btn btn-success">Create</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>