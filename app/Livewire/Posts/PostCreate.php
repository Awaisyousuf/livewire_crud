<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;

class PostCreate extends Component
{
    public $title, $slug, $content, $excerpt, $tags, $status = true, $published_at;

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'content' => 'required',
            'published_at' => 'required|date',
        ]);

        Post::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'tags' => $this->tags,
            'status' => $this->status,
            'published_at' => $this->published_at,
        ]);

        session()->flash('message', 'Post created.');
        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.posts.post-create');
    }
}
