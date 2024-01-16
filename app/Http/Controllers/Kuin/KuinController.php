<?php

namespace App\Http\Controllers\Kuin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

// use log

class KuinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = env('Kuin_token');
        $response = Http::withToken($token)->get('https://kuin.summaict.nl/api/product');
        $products = $response->json();

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //get all checked products
        $token = env('Kuin_token');
        //        dd($request->all());
        // Get the data from the form submission
        $data = $request->validate([
            'selected.*' => 'array',
            'selected.*' => 'integer',
            'quantity.*' => 'array',
            'quantity.*' => 'integer',
        ]);
        //        dd($data);
        // Loop through the checked products and quantities and make a post request for each
        $orderIds = [];
        $hasErrors = false;
        foreach ($data['selected'] as $index => $productId) {
            //            dd($productId);
            if (! empty($productId)) {
                $quantity = $data['quantity'][$index];
                //                dd($quantity);
                $response = Http::withToken($token)->post('https://kuin.summaict.nl/api/orderItem', [
                    '_token' => $request->input('_token'),
                    //                    dd($request->input('_token')),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
                if ($response->successful()) {
                    $orderIds[] = $response->json()['order_id'];
                } else {
                    $hasErrors = true;
                    break;
                }
            }
        }

        if ($hasErrors) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        } elseif (! empty($orderIds)) {
            return response()->json(['orderIds' => $orderIds], 200);
        } else {
            return response()->json(['error' => 'No products selected.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show($order)
    {
        $token = env('Kuin_token');
        $response = Http::withToken($token)->get('https://kuin.summaict.nl/api/orderItem?order_id='.$order);
        $orderItems = $response->json();
        $products = [];

        foreach ($orderItems as $orderItem) {
            $productId = $orderItem['product_id'];
            $response = Http::withToken($token)->get('https://kuin.summaict.nl/api/product/'.$productId);
            $product = $response->json();
            $products[] = $product;
        }

        return view('admin.show', ['orderItems' => $orderItems, 'products' => $products]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getOrder()
    {
        $token = env('Kuin_token');
        $response = Http::withToken($token)->get('https://kuin.summaict.nl/api/order');
        $kuinorder = $response->json();
        //pagination
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $itemCollection = collect($kuinorder);
        // $perPage = 10;
        // $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        // $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        // $paginatedItems->setPath(request()->url());
        // $kuinorder = $paginatedItems;

        return $kuinorder;
    }
}
