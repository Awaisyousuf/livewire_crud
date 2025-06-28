<?php
namespace App\Livewire\Posts;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class PostManager extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        session()->flash('message', 'Post deleted.');
    }

    public function render()
    {
        return view('livewire.posts.post-manager', [
            'posts' => Blog::latest()->paginate(10),
        ]);
    }
}
