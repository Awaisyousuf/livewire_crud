<?php
namespace
 App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('blogs.index'); // Livewire embedded
    }

    public function create()
    {
        return view('blogs.create'); // Livewire embedded
    }

    public function edit($id)
    {
        return view('blogs.edit', ['blogId' => $id]); // Livewire edit component
    }
}
