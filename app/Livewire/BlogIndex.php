<?php
// app/Livewire/Posts/BlogIndex.php

namespace App\Livewire;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class BlogIndex extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Blog::findOrFail($id)->delete();
        session()->flash('success', 'Blog deleted successfully.');
    }

    public function render()
    {
        return view('livewire.blog-index', [
            'blogs' => Blog::latest()->paginate(10)
        ]);
    }
}
