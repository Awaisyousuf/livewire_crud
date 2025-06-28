<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Blog;
use Livewire\Attributes\On;

class PostEdit extends Component
{
    public $blogId;
    public $title, $content, $excerpt, $tags, $published_at, $status = true;

    #[On('editBlog')]
    public function loadBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $this->fill($blog->toArray());
        $this->blogId = $blog->id;

        $this->dispatch('trix-set-content', content: $this->content);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        Blog::findOrFail($this->blogId)->update([
            'title' => $this->title,
            'slug' => \Str::slug($this->title),
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'tags' => $this->tags,
            'published_at' => $this->published_at,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Blog updated!');
        $this->dispatch('blogUpdated');
    }

    public function render()
    {
        return view('livewire.posts.post-edit');
    }
}
