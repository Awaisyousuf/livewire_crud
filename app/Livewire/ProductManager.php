<?php
// app/Livewire/ProductManager.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    
    public $currentScreen = 'list'; // 'list', 'create', 'edit'
    public $productId;
    public $name;
    public $slug;
    public $category_id;
    public $brand_id;
    public $tags;
    public $description;
    public $specs = [];
    public $price;
    public $discounted_price;
    public $in_stock = 'yes';
    public $status = true;
    public $spec_key = '';
    public $spec_value = '';
    public $categories;
    public $brands;

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
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
        $this->loadProduct($id);
    }

    private function resetForm()
    {
        $this->reset([
            'productId', 'name', 'slug', 'category_id', 'brand_id', 'tags',
            'description', 'specs', 'price', 'discounted_price', 'in_stock', 'status',
            'spec_key', 'spec_value'
        ]);
        $this->resetErrorBag();
        $this->specs = [];
    }

    private function loadProduct($id)
    {
        $product = Product::findOrFail($id);
        
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->tags = $product->tags;
        $this->description = $product->description;
        $this->specs = $product->specs ?: [];
        $this->price = $product->price;
        $this->discounted_price = $product->discounted_price;
        $this->in_stock = $product->in_stock;
        $this->status = $product->status;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addSpec()
    {
        $this->validate([
            'spec_key' => 'required|string|max:255',
            'spec_value' => 'required|string|max:255',
        ]);

        $this->specs[$this->spec_key] = $this->spec_value;
        $this->spec_key = '';
        $this->spec_value = '';
    }

    public function removeSpec($key)
    {
        unset($this->specs[$key]);
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'slug' => 'required|unique:products,slug,' . $this->productId,
            'category_id' => 'required|exists:categories,cat_id',
            'brand_id' => 'required|exists:brands,id',
            'tags' => 'nullable|string',
            'description' => 'required|min:10',
            'specs' => 'nullable|array',
            'price' => 'required|numeric|min:0.01',
            'discounted_price' => 'nullable|numeric|min:0|lt:price',
            'in_stock' => 'required|in:yes,no',
            'status' => 'boolean',
        ];
    }

    public function create()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'tags' => $this->tags,
            'description' => $this->description,
            'specs' => $this->specs,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'in_stock' => $this->in_stock,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Product created successfully!');
        $this->showList();
    }

    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);
        $product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'tags' => $this->tags,
            'description' => $this->description,
            'specs' => $this->specs,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'in_stock' => $this->in_stock,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Product updated successfully!');
        $this->showList();
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('success', 'Product deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $product = Product::find($id);
        $product->status = !$product->status;
        $product->save();
    }

    public function toggleStock($id)
    {
        $product = Product::find($id);
        $product->in_stock = $product->in_stock === 'yes' ? 'no' : 'yes';
        $product->save();
    }

    public function render()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(10);
            
        return view('livewire.product-manager', compact('products'));
    }
}