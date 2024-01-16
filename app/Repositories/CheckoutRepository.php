<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
//use Your Model

use Illuminate\Support\Facades\Hash;

/**
 * Class ProductRepository.
 */
class CheckoutRepository implements CrudInterface
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
     * @return collections Array of Staff Collection
     */
    public function getAll(): Paginator
    {
        if (! $this->user) {
            return Product::query()->paginate(10);
        }

        return $this->user->product()
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate(10);
    }

    /**
     * Get Paginated Staff Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Staff Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;

        return Product::orderBy('id', 'desc')
            ->with('user')
            ->paginate($perPage);
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

        return Product::create($data);
    }

    /**
     * Delete Staff.
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
     * Get Staff Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Product|null
    {
        return Product::with('user')->find($id);
    }

// get list of all checkoutproducts

public function getAllCheckoutProducts()
{
    if (! $this->user) {
        return ReceiptProduct::all();
    }

    return $this->user->receiptProduct()
        ->orderBy('id', 'desc')
        ->with('user')
        ->get();
}

        /**
         *GET PRODUCT BY BARCODE
         *
         * @param  int  $barcode
         * @return object Product Object
         */
        public function getProductByCode(int $productCode)
        {
            $product = Product::with('user')->where('barcode', $productCode)->first();

            if ($product) {
                return response()->json($product);
            } else {
                return response()->json(['error' => 'Product not found'], 404);
            }
        }

    /**
     * Update Staff By ID.
     *
     * @return object Updated Staff Object
     */
    public function update(int $id, array $data)
    {
        $product = Product::find($id);

        // If everything is OK, then update.
        $product->update($data);

        // Finally return the updated staff.
        return $this->getByID($product->id);
    }

    // post checkoutproduct to table

    public function postCheckoutProduct(array $data): ReceiptProduct
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        return ReceiptProduct::create($data);
    }

    // make a function to post products to other table with the same data from the product table and searching by barcode

public function postProductByBarcode(int $barcode)
{
    $product = Product::where('barcode', $barcode)->first();

    if ($product) {
        // Controleer of het product al is toegevoegd aan ReceiptProduct
        $receiptProduct = ReceiptProduct::where('article_number', $product->article_number)->first();

        if ($receiptProduct) {
            // Verhoog de quantity met 1
            $receiptProduct->quantity += 1;
            $receiptProduct->save();
        } else {
            // Maak een nieuwe ReceiptProduct instantie aan
            $receiptProduct = new ReceiptProduct();
            $receiptProduct->name = $product->name;
            $receiptProduct->price = $product->price;
            $receiptProduct->article_number = $product->article_number;
            $receiptProduct->quantity = 1;
            $receiptProduct->user_id = $product->user_id;

            // Sla de ReceiptProduct instantie op in de database
            $receiptProduct->save();
        }

        return response()->json($product);
    } else {
        return response()->json(['error' => 'Product not found'], 404);
    }
}

// delete checkoutproduct by id

    public function deleteCheckoutProductById(int $id): bool
    {
        $ReceiptProduct = ReceiptProduct::find($id);
        if (empty($ReceiptProduct)) {
            return false;
        }
        $ReceiptProduct->delete($ReceiptProduct);

        return true;
    }

//  make function to delete all checkoutproducts

    public function deleteAllCheckoutProducts(): bool
    {
        $ReceiptProduct = ReceiptProduct::all();
        if (empty($ReceiptProduct)) {
            return false;
        }
        $ReceiptProduct->delete($ReceiptProduct);

        return true;
    }

    //Hieronder vindt u een voorbeeld van een functie die alle gegevens uit de 'receipt_product' tabel haalt en deze overzet naar de 'receipt' tabel in Laravel.
    public function postReceipt()
    {
        // Get all data from receiptproduct
        $receiptProducts = ReceiptProduct::all();
        $user = Auth::user();
        try {
            // Combine all product data into one string
            $receiptData = '';
            $userAdded = false;

            $totalPrice = 0; // Initialize totalPrice
            $totalQuantity = 0; // Initialize totalQuantity
            foreach ($receiptProducts as $receiptProduct) {
                // Check if the receipt product has a user_id
                if (isset($receiptProduct->user_id) && ! $userAdded) {
                    $user = User::find($receiptProduct->user_id);
                    // $user = auth()->user();
                    if ($user) {
                        $receiptData .= '| Cashier Number: '.$user->cashier_number.' | '.
                            'Cashier Name: '.$user->name.' | '.
                            'Cashier Email: '.$user->email.' | ';
                        $userAdded = true; // User information has been added
                    }
                }

                $receiptData .= 'Product: '.$receiptProduct->name.' | '.
                    'Price: '.$receiptProduct->price.' | '.
                    'Article Number: '.$receiptProduct->article_number.' | ';

                // Update the total price and quantity calculations
                $totalPrice += $receiptProduct->price * $receiptProduct->quantity;
                $totalQuantity += $receiptProduct->quantity;

                $receiptData .= ';';
            }

            // Add total price and quantity to receiptData
            $receiptData .= 'Total Price: '.$totalPrice.' | '.
                'Total Quantity: '.$totalQuantity.' | '.
                'Total Price and Quantity: '.$totalPrice * $totalQuantity.' | ';

            // Create a new Receipt instance
            $receipt = new Receipt();
            $receipt->data = $receiptData;

            // Save the Receipt instance to the database
            $receipt->save();

            // Update product stock
            foreach ($receiptProducts as $receiptProduct) {
                $product = Product::find($receiptProduct->product_id);
                if ($product) {
                    $product->stock -= $receiptProduct->quantity;

                    // Check if stock is 0
                    if ($product->stock == 0) {
                        return response()->json(['message' => 'Product is out of stock']);
                    }

                    $product->save();
                }
            }

            // Delete all products from the receiptproduct table
            ReceiptProduct::query()->delete();

            return response()->json(['message' => 'Receipt created successfully']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // get all receipts

    public function getAllReceipts()
    {
        return Receipt::all();
    }

      // loginWithCustomernumber

      public function loginWithCustomernumber(array $data)
      {
          $user = User::where('customernumber', $data['customernumber'])->first();

          if ($user) {
              if (Hash::check($data['password'], $user->password)) {
                  $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                  $response = ['token' => $token];

                  return response($response, 200);
              } else {
                  $response = 'Password missmatch';

                  return response($response, 422);
              }
          } else {
              $response = 'User does not exist';

              return response($response, 422);
          }
      }

      //getUserByCustomernumber

      public function getUserByCustomernumber(int $customernumber)
      {
          $user = User::where('customernumber', $customernumber)->first();

          if ($user) {
              return response()->json($user);
          } else {
              return response()->json(['error' => 'User not found'], 404);
          }
      }

    //   get all customers

    public function getAllCustomers()
    {
    //   get all users
        $users = User::all();

        //   get all users where name is customernumber
        $customers = $users->where('name', 'customernumber');

        return response()->json($customers);


    }

}
