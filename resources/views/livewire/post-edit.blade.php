<div>
    @if ($blogId)
        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label>Title</label>
                <input class="form-control" wire:model="title">
            </div>

            <div class="mb-3" wire:ignore>
                <input id="content_edit" type="hidden" wire:model="content">
                <trix-editor input="content_edit" class="trix-content"></trix-editor>
                @error('content') <p class="text-danger text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-3">
                <label>Excerpt</label>
                <textarea class="form-control" wire:model="excerpt"></textarea>
            </div>

            <div class="mb-3">
                <label>Tags</label>
                <input class="form-control" wire:model="tags">
            </div>

            <div class="mb-3">
                <label>Published At</label>
                <input type="datetime-local" class="form-control" wire:model="published_at">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" wire:model="status" id="status">
                <label class="form-check-label" for="status">Published</label>
            </div>

            <button class="btn btn-warning">Update</button>
        </form>
    @endif

    @push('scripts')
        <script>
            window.addEventListener('trix-set-content', event => {
                const hiddenInput = document.getElementById('content_edit');
                const trixEditor = document.querySelector("trix-editor[input='content_edit']");
                if (hiddenInput && trixEditor) {
                    setTimeout(() => {
                        hiddenInput.value = event.detail;
                        trixEditor.editor.loadHTML(event.detail);
                    }, 100);
                }
            });

            document.addEventListener('trix-blur', function () {
                Livewire.dispatch('updateContent', {
                    content: document.getElementById('content_edit').value
                });
            });
        </script>
    @endpush
</div>