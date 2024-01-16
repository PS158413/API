<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User|null $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    /**
     * Get All Products.
     *
     * @return collections Array of Product Collection
     */
    public function getAll(): Paginator
    {
        if (! $this->user) {
            return User::query()->paginate(1000);
        }

        return $this->user->products()
            ->orderBy('id', 'asc')
            ->with('user', 'category')
            ->paginate(10);
    }

    /**
     * Get Paginated Product Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Product Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;

        return Product::orderBy('id', 'asc')->with('category')->paginate($perPage);
    }

    public function getSKU(Product $data): string
    {
        $prefix = substr($data->name, 0, 3);

        $id = str_pad($data->article_number, 9, '0', STR_PAD_LEFT);

        // get category from product by relation if exists else return null
        return  strtoupper($prefix).$id;
    }

    /**
     * Create New Product.
     *
     * @return object Product Object
     */
    public function create(array $data): Product
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        // Create the product with SKU
        $product = new Product($data);
        $data['sku'] = $this->getSKU($product);

        return Product::create($data);
    }

    /**
     * Delete Product.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $product = Product::find($id);
        if (empty($product)) {
            return false;
        }
        $product->delete($product);

        return true;
    }

    /**
     * Get Product Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Product|null
    {
        return Product::with('user', 'category')->find($id);
    }

    /**
     * Update Staff By ID.
     *
     * @return object Updated Staff Object
     */
    public function update(int $id, array $data)
    {
        $product = Product::find($id);

        if ($product) {
            // If the product exists, update it
            $product->update($data);

            if (isset($data['category'])) {
                // Update the category if provided
                $category = Category::where('category', $data['category'])->first();

                if ($category) {
                    $product->category()->sync($category);
                }
            }
        }

        // Return the updated product with its category
        return $product->load('category');
    }

    public function postProductByBarcode(int $barcode)
    {
        $product = Product::where('barcode', $barcode)->first();

        if ($product) {
            // Maak een nieuwe ReceiptProduct instantie aan
            $ReceiptProduct = new ReceiptProduct();
            $ReceiptProduct->name = $product->name;
            $ReceiptProduct->price = $product->price;
            $ReceiptProduct->article_number = $product->article_number;
            // set stock to 1

            //$ReceiptProduct->barcode = $product->barcode;
            //$ReceiptProduct->user_id = $product->user_id;
            // if product exists, add 1 to stock
            if ($ReceiptProduct->stock > 0) {
                $ReceiptProduct->stock = $ReceiptProduct->stock + 1;
            } else {
                $ReceiptProduct->stock = 1;
            }

            // Sla de ReceiptProduct instantie op in de database
            $ReceiptProduct->save();

            return response()->json($product);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
