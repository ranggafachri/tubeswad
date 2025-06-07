<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\OpenFoodFactsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    protected $openFoodFactsService;

    public function __construct(OpenFoodFactsService $openFoodFactsService)
    {
        $this->openFoodFactsService = $openFoodFactsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');
        
        // If barcode is provided, try to fetch product data from Open Food Facts
        if ($request->filled('barcode')) {
            $productData = $this->openFoodFactsService->getProductByBarcode($request->barcode);
            
            if ($productData['success']) {
                // Only use API data if fields are empty in request
                if (empty($request->name)) {
                    $data['name'] = $productData['name'];
                }
                if (empty($request->description)) {
                    $data['description'] = $productData['description'];
                }
                // If no image uploaded but API has image
                if (!$request->hasFile('image') && !empty($productData['image'])) {
                    // Download and store image from API
                    $imageContents = file_get_contents($productData['image']);
                    if ($imageContents !== false) {
                        $imageName = 'products/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($imageName, $imageContents);
                        $data['image'] = $imageName;
                    }
                }
            }
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        // If barcode is changed, try to fetch new product data
        if ($request->filled('barcode') && $product->barcode !== $request->barcode) {
            $productData = $this->openFoodFactsService->getProductByBarcode($request->barcode);
            
            if ($productData['success']) {
                // Only use API data if fields are empty in request
                if (empty($request->name)) {
                    $data['name'] = $productData['name'];
                }
                if (empty($request->description)) {
                    $data['description'] = $productData['description'];
                }
                // If no new image uploaded but API has image
                if (!$request->hasFile('image') && !empty($productData['image'])) {
                    // Download and store image from API
                    $imageContents = file_get_contents($productData['image']);
                    if ($imageContents !== false) {
                        // Delete old image if exists
                        if ($product->image) {
                            Storage::disk('public')->delete($product->image);
                        }
                        $imageName = 'products/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($imageName, $imageContents);
                        $data['image'] = $imageName;
                    }
                }
            }
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // Method untuk menampilkan katalog produk (untuk user)
    public function catalog(Request $request)
    {
        $query = Product::query();

        // Filter berdasarkan kategori
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Hanya tampilkan produk yang aktif dan stok > 0
        $query->where('is_active', true)
              ->where('stock', '>', 0);

        // Urutkan berdasarkan yang terbaru
        $query->latest();

        // Get categories for filter
        $categories = Category::where('is_active', true)->get();

        // Pagination
        $products = $query->paginate(12);

        return view('products.catalog', compact('products', 'categories'));
    }

    public function fetchProductData(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:50'
        ]);

        $productData = $this->openFoodFactsService->getProductByBarcode($request->barcode);
        
        return response()->json($productData);
    }

    public function searchApi(Request $request)
    {
        $search = $request->input('search');
        $products = [];
        $categories = Category::where('is_active', true)->get();
        
        if ($search) {
            try {
                $response = Http::timeout(30)
                    ->get("https://world.openfoodfacts.org/cgi/search.pl", [
                        'search_terms' => $search,
                        'search_simple' => 1,
                        'action' => 'process',
                        'json' => 1,
                        'page_size' => 24
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $products = $data['products'] ?? [];
                }
            } catch (\Exception $e) {
                return view('products.search-api', [
                    'products' => [],
                    'search' => $search,
                    'categories' => $categories,
                    'error' => 'Failed to fetch products: ' . $e->getMessage()
                ]);
            }
        }
        
        return view('products.search-api', compact('products', 'search', 'categories'));
    }

    public function importFromApi(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        // Check if product already exists
        if (Product::where('barcode', $request->barcode)->exists()) {
            return redirect()->back()->with('error', 'Produk dengan barcode ini sudah ada dalam katalog.');
        }

        $productData = $this->openFoodFactsService->getProductByBarcode($request->barcode);
        
        if (!$productData['success']) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan di Open Food Facts.');
        }

        // Download and store image
        $imagePath = null;
        if (!empty($productData['image'])) {
            $imageContents = file_get_contents($productData['image']);
            if ($imageContents !== false) {
                $imageName = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imageName, $imageContents);
                $imagePath = $imageName;
            }
        }

        // Create product
        Product::create([
            'name' => $productData['name'],
            'barcode' => $request->barcode,
            'description' => $productData['description'],
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'is_active' => true
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diimpor dari Open Food Facts!');
    }
}
