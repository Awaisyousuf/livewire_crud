@once
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    @endpush
@endonce

<input id="{{ $attributes['input-id'] }}" type="hidden" {{ $attributes }} />
<trix-editor input="{{ $attributes['input-id'] }}"></trix-editor>