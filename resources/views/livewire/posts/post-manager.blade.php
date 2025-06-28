<div class="container">
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create Post</a>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->status ? 'Published' : 'Draft' }}</td>
                    <td>{{ $post->published_at }}</td>
                    <td>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button wire:click="delete({{ $post->id }})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}
</div>