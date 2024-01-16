<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

/**
 * Class CustomerRepository.
 */
class CustomerRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var Order
     */
    public Order|null $order;

    /**
     * Get All Orders.
     *
     * @return collections Array of Category Collection
     */
    public function getAll()
    {
    }

    /**
     * Get Paginated OrderItem Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Order Collection
     */
    public function getPaginatedData($perPage)
    {
    }

    /**
     * Create a new Order for customer.
     *
     * @param  array  $data  The order data
     * @return Order  The created OrderItem object
     */
    public function create(array $data): Order
    {
        $customerData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'zipcode' => $data['zipcode'],
            'huisnummer' => $data['huisnummer'],
            'address' => $data['address'],
            'city' => $data['city'],
            'total' => $data['total'],
        ];

        $order = Order::create($customerData);

        if (isset($data['products'])) {
            foreach ($data['products'] as $productData) {
                $product = Product::where('name', $productData['name'])->first();
                if ($product) {
                    $orderItem = $order->OrderItem()->create([
                        'order_id' => $order->id,
                        // add any relevant order item data
                    ]);

                    $orderItem->products()->attach($product, ['quantity' => $productData['quantity']]);
                }
            }
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
    public function getByID(int $id)
    {
    }

    /**
     * Update Order By ID.
     *
     * @return object Updated Category Object
     */
    public function update(int $id, array $data)
    {
    }
}
