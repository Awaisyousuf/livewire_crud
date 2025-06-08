<?php

// app/Livewire/BlogManager.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap'; // Use Bootstrap pagination

    public $currentScreen = 'list'; // 'list', 'create', 'edit'
    public $blogId;
    public $title;
    public $slug;
    public $content;
    public $featured_image;
    public $status = true;
    public $excerpt;
    public $tags;
    public $published_at;
    public $existing_image;

    public function mount()
    {
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function updatedCurrentScreen()
    {
        $this->resetPage();
    }

    public function showList()
    {
        $this->currentScreen = 'list';
        $this->resetForm();
    }

    public function showCreate()
    {
        $this->currentScreen = 'create';
        $this->resetForm();
    }

    public function showEdit($id)
    {
        $this->currentScreen = 'edit';
        $this->loadBlog($id);
    }

    private function resetForm()
    {
        $this->reset([
            'blogId', 'title', 'slug', 'content', 'featured_image',
            'status', 'excerpt', 'tags', 'published_at', 'existing_image'
        ]);
        $this->resetErrorBag();
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    private function loadBlog($id)
    {
        $blog = Blog::findOrFail($id);
        
        $this->blogId = $blog->id;
        $this->title = $blog->title;
        $this->slug = $blog->slug;
        $this->content = $blog->content;
        $this->status = $blog->status;
        $this->excerpt = $blog->excerpt;
        $this->tags = $blog->tags;
        $this->published_at = $blog->published_at->format('Y-m-d\TH:i');
        $this->existing_image = $blog->featured_image;
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    protected function rules()
    {
        return [
            'title' => 'required|min:5|max:255',
            'slug' => 'required|unique:blogs,slug,' . $this->blogId,
            'content' => 'required|min:50',
            'excerpt' => 'nullable|max:300',
            'tags' => 'nullable|string',
            'published_at' => 'required|date',
            'status' => 'boolean',
            'featured_image' => 'nullable|image|max:2048'
        ];
    }

    public function create()
    {
        $this->validate();

        $imagePath = $this->uploadImage();

        Blog::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'excerpt' => $this->excerpt,
            'tags' => $this->tags,
            'published_at' => $this->published_at,
            'featured_image' => $imagePath
        ]);

        session()->flash('success', 'Blog created successfully!');
        $this->showList();
    }

    public function update()
    {
        $this->validate();

        $blog = Blog::findOrFail($this->blogId);
        $imagePath = $this->uploadImage() ?? $blog->featured_image;

        $blog->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'excerpt' => $this->excerpt,
            'tags' => $this->tags,
            'published_at' => $this->published_at,
            'featured_image' => $imagePath
        ]);

        session()->flash('success', 'Blog updated successfully!');
        $this->showList();
    }

    private function uploadImage()
    {
        if (!$this->featured_image) {
            return null;
        }

        return $this->featured_image->store('blogs', 'public');
    }

    public function delete($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Delete associated image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        
        $blog->delete();
        session()->flash('success', 'Blog deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $blog = Blog::find($id);
        $blog->status = !$blog->status;
        $blog->save();
    }

    public function render()
    {
        $blogs = Blog::latest('published_at')->paginate(10);
        return view('livewire.blog-manager', compact('blogs'));
    }
}