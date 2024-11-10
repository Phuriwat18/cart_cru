<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::where('user_id',Auth::id())->where('status',0)->first();
        return view('orders.index')->with('order', $order);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::find($request->product_id);
        $order = Order::where('user_id',Auth::id())->where('status',0)->first();
        if($order) {
            $orderDetail = $order->order_details()->where('product_id', $product->id)->first();
            if($orderDetail){
                $amountNew = $orderDetail->amount + 1;
                $orderDetail->update([
                    'amount' => $amountNew
                ]);
            } else {
                $preparCartDetail = [
                    'order_id' => $order->id,
                    'product_id' => $request->id,
                    'product_name' => $request->name,
                    'amount' => 1,
                    'price' => $product->price,
                ];
                $orderDetail = OrderDetail::create($preparCartDetail);
            }
        } else {
            $preparCart = [
                'status' => 0,
                'user_id' => Auth::id(),
            ];
    
            
            $order = Order::create($preparCart);
    
            
            $preparCartDetail = [
                'order_id' => $order->id,
                'product_id' => $request->id,
                'product_name' => $request->name,
                'amount' => 1,
                'price' => $product->price,
            ];
            $orderDetail = OrderDetail::create($preparCartDetail);
        }

        $totalRaw = 0;
        $total = $order->order_details->map(function ($orderDetail) use ($totalRaw) {
            $totalRaw += $orderDetail->amount * $orderDetail->price;
            return $totalRaw;
        })->update();

        $order->update([
            'total' => array_sum($total)
        ]);
        

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $orderDetail = $order->order_details()->where('product_id', $request->$product_id)->first();
        
        if($request->value = "chekout"){
            $order->update([
                'status' => 1
            ]);
        }
        if($orderDetail) {
            if($request->value == "increase") {
                $amountNew = $orderDetail->amount + 1;
            } else {
                $amountNew = $orderDetail->amount - 1;
            } 
        
            $orderDetail->update([
            'amount' => $amountNew
            ]);  
        }

        $totalRaw = 0;
        $total = $order->order_details->map(function ($orderDetail) use ($totalRaw) {
            $totalRaw += $orderDetail->amount * $orderDetail->price;
            return $totalRaw;
        })->update();

        $order->update([
            'total' => array_sum($total)
        ]);

        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
