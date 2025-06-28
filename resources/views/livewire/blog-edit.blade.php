<div>
    @if ($blogId)
        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label>Title</label>
                <input class="form-control" wire:model="title">
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

  
</div>