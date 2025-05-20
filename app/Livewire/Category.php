<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category as Categories;

class Category extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Optional: Use Bootstrap pagination

    public $name, $description, $category_id;
    public $updateCategory = false;

    protected $listeners = ['deleteCategory' => 'destroy'];

    // Validation Rules
    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];

    /**
     * Render the component.
     */
    public function render()
    {
        $categories = Categories::select('id', 'name', 'description')->paginate(10);

        return view('livewire.category', ['categories' => $categories]);
    }

    /**
     * Reset form fields.
     */
    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
        $this->category_id = null;
    }

    /**
     * Store a new category.
     */
    public function store()
    {
        $this->validate();

        try {
            Categories::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            session()->flash('success', 'Category Created Successfully!!');
            $this->resetFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while creating the category.');
        }
    }

    /**
     * Edit an existing category.
     */
    public function edit($id)
    {
        $category = Categories::findOrFail($id);

        $this->name = $category->name;
        $this->description = $category->description;
        $this->category_id = $category->id;
        $this->updateCategory = true;
    }

    /**
     * Cancel update operation.
     */
    public function cancel()
    {
        $this->updateCategory = false;
        $this->resetFields();
    }

    /**
     * Update an existing category.
     */
    public function update()
    {
        $this->validate();

        try {
            $category = Categories::findOrFail($this->category_id);

            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            session()->flash('success', 'Category Updated Successfully!!');
            $this->cancel();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while updating the category.');
        }
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        try {
            Categories::findOrFail($id)->delete();
            session()->flash('success', 'Category Deleted Successfully!!');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while deleting the category.');
        }
    }
}
