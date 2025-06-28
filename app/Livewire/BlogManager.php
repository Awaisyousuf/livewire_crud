<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogManager extends Component
{
    use WithPagination;
       // ← ✅ this is required!

    public $title, $slug, $content, $excerpt, $tags, $featured_image, $status = true, $published_at;
    public $blogId = null;
    public $showForm = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required',
        'excerpt' => 'nullable|string|max:500',
        'tags' => 'nullable|string|max:255',
        'status' => 'boolean',
        'published_at' => 'required|date',
    ];

    public function render()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('livewire.blog-manager', compact('blogs'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function updateContent(string $content)
{
    $this->content = $content;
}

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $this->fill($blog->toArray());
        $this->blogId = $id;
        $this->showForm = true;

        // Dispatch content to Trix editor
        $this->dispatch('refresh-trix',  $blog->content);


    }

    public function save()
    {
        $this->validate();

        Blog::updateOrCreate(
            ['id' => $this->blogId],
            [
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'tags' => $this->tags,
                'status' => $this->status,
                'published_at' => $this->published_at,
            ]
        );

        session()->flash('message', $this->blogId ? 'Blog updated.' : 'Blog created.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Blog::destroy($id);
    }

    public function resetForm()
    {
        $this->reset(['title', 'slug', 'content', 'excerpt', 'tags', 'featured_image', 'status', 'published_at', 'blogId', 'showForm']);
    }
}
