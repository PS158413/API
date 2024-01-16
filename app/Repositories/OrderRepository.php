<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductRepository.
 */
class OrderRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     */
    public ?User $user;

    /**
     * Order Instance.
     */
    public ?Order $order;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
        $this->order = new Order();
    }

    /**
     * Get All Orders.
     *
     * @return collections Array of Category Collection
     */
    public function getAll(): Paginator
    {
        if (! $this->user) {
            return User::query()->paginate(10);
        }

        return OrderItem::orderBy('id', 'asc')
            ->with(['user', 'order', 'products' => function ($query) {
                $query->withPivot('quantity');
            }])
            ->paginate(10);
    }

    /**
     * Get Paginated OrderItem Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Order Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return OrderItem::orderBy('id', 'asc')
            ->with(['user', 'order', 'products' => function ($query) {
                $query->withPivot('quantity');
            }])
            ->paginate($perPage);
    }

    /**
     * Create a new OrderItem.
     *
     * @param  array  $data  The order data
     * @return OrderItem  The created OrderItem object
     */
    public function create(array $data): OrderItem
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        // OrderItem doesn't exist, create a new one
        $order = OrderItem::create([
            'user_id' => $user->id,
        ]);

        $product = Product::where('name', $data['name'])->first();
        if ($product) {
            $order->products()->attach($product, ['quantity' => $data['quantity']]);
        }

        return $order;
    }

    /**
     * Delete Category.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id)
    {
    }

    /**
     * Get Staff Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): OrderItem|null
    {
        return OrderItem::with('user')->find($id);
    }

    /**
     * Update Order By ID.
     *
     * @return object Updated Category Object
     */
    public function update(int $id, array $data)
    {
        $orderitem = OrderItem::find($id);

        // If everything is OK, then update.
        $orderitem->update($data);

        // Finally return the updated staff.
        return $this->getByID($orderitem->id);
    }
}
