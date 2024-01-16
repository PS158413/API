<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductRepository.
 */
class CategoryRepository implements CrudInterface
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
     * Get All Staff.
     *
     * @return collections Array of Category Collection
     */
    public function getAll(): Paginator
    {
        if (! $this->user) {
            return User::query()->paginate(10);
        }

        return $this->user->categorys()
            ->orderBy('id', 'desc')
            ->with('user', 'products')
            ->paginate(10);
    }

    /**
     * Get Paginated Category Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Category Collection
     */
    public function getPaginatedData($perPage)
    {
        return Category::with('products')->orderBy('id', 'asc')->get();
    }

    public function getSKU(Product $data, Category $category): string
    {
        $categoryPrefix = substr($category->category,0,3);
        $productPrefix = substr($data->name,0,3);
        $id = str_pad($data->id,9,'0',STR_PAD_LEFT);

        return strtoupper($categoryPrefix).strtoupper($productPrefix).$id;
    }

    /**
     * Create New Category.
     *
     * @return object Product Object
     */
    /**
     * Create New Category.
     *
     * @return object Category Object
     */
    public function create(array $data): Category
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        // Check if the category already exists
        $category = Category::where('category', $data['category'])->first();

        if (! $category) {
            // Category does not exist, create a new one
            $category = Category::create([
                'category' => $data['category'],
                'user_id' => $user->id,
            ]);
        }

        // Assign the product to the category if provided
        if (isset($data['name'])) {
            $product = Product::where('name', $data['name'])->first();
            if ($product) {
                $product->category()->attach($category);
                // SKU
                $sku = $this->getSKU($product, $category);
                $product->sku = $sku;
                $product->save();
            }
        }

        return $category;
    }

    /**
     * Delete Category.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $category = Category::find($id);
        if (empty($category)) {
            return false;
        }
        $category->delete($category);

        return true;
    }

    /**
     * Detach a product from a category.
     *
     * @param  int  $categoryId
     * @param  int  $productId
     * @return bool
     */
    public function detach(int $id, array $data)
    {
        $category = Category::find($id);
        if (! $category) {
            return false; // Category not found
        }

        // Detach the product from the category if provided
        if (isset($data['name'])) {
            $product = Product::where('name', $data['name'])->first();
            if ($product) {
                $category->products()->detach($product);
            }
        }

        return true;
    }

    /**
     * Get Staff Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Category|null
    {
        return Category::with('user', 'products')->find($id);
    }

    public function getProductByCode($categoryCode)
    {
        $category = Category::where('category_code', $categoryCode)->first();

        if ($category) {
            return response()->json($category);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Update Category By ID.
     *
     * @return object Updated Category Object
     */
    public function update(int $id, array $data)
    {
        $category = Category::find($id);

        // If everything is OK, then update.
        $category->update($data);

        // Finally return the updated staff.
        return $this->getByID($category->id);
    }
}
