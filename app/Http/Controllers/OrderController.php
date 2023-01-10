<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use Barryvdh\DomPDF\PDF;
use App\Models\Notification;
use App\Models\Product;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Facade\FlareClient\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->paginate(10);
        return view('backend.order.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'string|required',
            'email' => 'string|required',
            'phone' => 'numeric|required',
            'alamat' => 'string|required',
            'provinsi' => 'string|required',
            // 'address2' => 'string|nullable',
            // 'coupon' => 'nullable|numeric',
            // 'post_code' => 'string|nullable',
        ]);
        // return $request->all();

        if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
            request()->session()->flash('error', 'Cart is Empty !');
            return back();
        }
        // $cart = Cart::get();
        // // return $cart;
        // $cart_index = 'ORD-' . strtoupper(uniqid());
        // $sub_total = 0;
        // foreach ($cart as $cart_item) {
        //     $sub_total += $cart_item['amount'];
        //     $data = array(
        //         'cart_id' => $cart_index,
        //         'user_id' => $request->user()->id,
        //         'product_id' => $cart_item['id'],
        //         'quantity' => $cart_item['quantity'],
        //         'amount' => $cart_item['amount'],
        //         'status' => 'new',
        //         'price' => $cart_item['price'],
        //     );

        //     $cart = new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }
        // return dd($cart);


        // $total_prod=0;
        // if(session('cart')){
        //         foreach(session('cart') as $cart_items){
        //             $total_prod+=$cart_items['quantity'];
        //         }
        // }
        // $user = auth()->user();
        // $cart = session('cart');

        // return dd($cart);
        //berhasil ini

        $order_data = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'nama'   => $request->nama,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'negara'   => 'Negara Indonesia ',
            'alamat'   => $request->alamat,
            'provinsi'   => $request->provinsi,
            'quantity'   => Helper::cartCount(),
            'total_amount' => (Helper::totalCartPrice()),
            'status' => 'new',
            'sub_total' => '0',
            'user_id' => $request->user()->id,
        ]);
        $users = auth()->user();
        $users->saldo =  ($request->user()->saldo - Helper::totalCartPrice());
        $users->save();
        $cart = Helper::getAllProductFromCart();
        $order_number = $order_data->order_number;
        foreach ($cart as $cart_item) {
            $cart_item['order_detail_id'] = $order_number;
            $cart_item->save();
        };

        // $sscacrt = $cart->order_id;
        // return dd($cart);
        // tutupberhasil
        // $order_data
        // $order_data['total_amount'] = (Helper::totalCartPrice());
        // $order_data['quantity'] = Helper::cartCount();
        // Order::create($order_data);
        // $order_data['shipping_id'] = $request->shipping;
        // $shipping = Shipping::where('id', $order_data['shipping_id'])->pluck('price');
        // return session('coupon')['value'];



        // if (session('coupon')) {
        //     $order_data['coupon'] = session('coupon')['value'];
        // }
        // if ($request->shipping) {
        //     if (session('coupon')) {
        //         $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0] - session('coupon')['value'];
        //     } else {
        //         $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0];
        //     }
        // } else {
        //     if (session('coupon')) {
        //         $order_data['total_amount'] = Helper::totalCartPrice() - session('coupon')['value'];
        //     } else {
        //         $order_data['total_amount'] = Helper::totalCartPrice();
        //     }
        // }
        // return $order_data['total_amount'];
        // $order_data['status'] = "new";
        // if (request('payment_method') == 'paypal') {
        //     $order_data['payment_method'] = 'paypal';
        //     $order_data['payment_status'] = 'paid';
        // } else {
        //     $order_data['payment_method'] = 'cod';
        //     $order_data['payment_status'] = 'Unpaid';
        // }
        // $order_data->save();
        // $users->save();

        // $order->fill($order_data);
        // $status = $order->save();
        // if ($order)
        //     // dd($order->id);
        //     $users = User::where('role', 'admin')->first();
        // // $details = [
        // //     'title' => 'New order created',
        // //     'actionURL' => route('order.show', $order->id),
        // //     'fas' => 'fa-file-alt'
        // // ];
        // Notification::send($users, new StatusNotification($details));
        // if (request('payment_method') == 'paypal') {
        //     return redirect()->route('payment')->with(['id' => $order->id]);
        // } else {
        //     session()->forget('cart');
        //     session()->forget('coupon');
        // }
        // // Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // dd($users);        
        request()->session()->flash('success', 'Your product successfully placed in order');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('user')->find($id);

        $order_number = $order->order_number;
        $cart = Cart::with('product')->where('order_detail_id', $order_number)->get();
        // $cart = Product::all();
        // if ($cart[0]->id != null) {
        //     $c0 = $cart[0]->id;
        //     $produk = Product::where('id', $c0)->get();
        //     $nm_p0 = $produk[0]->title;
        // } else {
        //     echo '';
        // };
        // if ($cart[1]->id != null) {
        //     $c1 = $cart[1]->id;
        //     $produk = Product::where('id', $c1)->get();
        //     $nm_p1 = $produk[0]->title;
        // } else {
        //     echo '';
        // };
        // if ($cart[2]->id != null) {
        //     $c2 = $cart[2]->id;
        //     $produk = Product::where('id', $c2)->get();
        //     $nm_p2 = $produk[0]->title;
        // } else {
        //     echo '';
        // };
        // if ($cart[3]->id != null) {
        //     $c3 = $cart[3]->id;
        //     $produk = Product::where('id', $c3)->get();
        //     $nm_p3 = $produk[0]->title;
        // } else {
        //     echo '';
        // };
        // if ($cart[4]->id != null) {
        //     $c4 = $cart[4]->id;
        //     $produk = Product::where('id', $c4)->get();
        //     $nm_p4 = $produk[0]->title;
        // } else {
        //     echo '';
        // };
        // if ($cart[5]->id != null) {
        //     $c5 = $cart[5]->id;
        //     $produk = Product::where('id', $c5)->get();
        //     $nm_p5 = $produk[0]->title;
        // } else {
        //     echo '';
        // };

        // return dd($nm_p2);
        // $cart_asli = [$cart];

        // return dd($i);

        // return dd($c1);
        // $order = Order::find($id);
        // return dd($cart);
        return view('backend.order.show', compact('cart', $cart))->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('backend.order.edit')->with('order', $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, [
            'status' => 'required|in:new,process,delivered,cancel'
        ]);
        $data = $request->all();
        // return $request->status;
        if ($request->status == 'delivered') {
            foreach ($order->cart as $cart) {
                $product = $cart->product;
                // return $product;
                $product->stock -= $cart->quantity;
                $product->save();
            }
        }
        $status = $order->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated order');
        } else {
            request()->session()->flash('error', 'Error while updating order');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $status = $order->delete();
            if ($status) {
                request()->session()->flash('success', 'Order Successfully deleted');
            } else {
                request()->session()->flash('error', 'Order can not deleted');
            }
            return redirect()->route('order.index');
        } else {
            request()->session()->flash('error', 'Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack()
    {
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request)
    {
        // return $request->all();
        $order = Order::where('user_id', auth()->user()->id)->where('order_number', $request->order_number)->first();
        if ($order) {
            if ($order->status == "new") {
                request()->session()->flash('success', 'Your order has been placed. please wait.');
                return redirect()->route('home');
            } elseif ($order->status == "process") {
                request()->session()->flash('success', 'Your order is under processing please wait.');
                return redirect()->route('home');
            } elseif ($order->status == "delivered") {
                request()->session()->flash('success', 'Your order is successfully delivered.');
                return redirect()->route('home');
            } else {
                request()->session()->flash('error', 'Your order canceled. please try again');
                return redirect()->route('home');
            }
        } else {
            request()->session()->flash('error', 'Invalid order numer please try again');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request)
    {
        $order = Order::getAllOrder($request->id);
        // return $order;
        $file_name = $order->order_number . '-' . $order->first_name . '.pdf';
        // return $file_name;
        $pdf = PDF::loadview('backend.order.pdf', compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request)
    {
        $year = \Carbon\Carbon::now()->year;
        // dd($year);
        $items = Order::with(['cart_info'])->whereYear('created_at', $year)->where('status', 'delivered')->get()
            ->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        // dd($items);
        $result = [];
        foreach ($items as $month => $item_collections) {
            foreach ($item_collections as $item) {
                $amount = $item->cart_info->sum('amount');
                // dd($amount);
                $m = intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount : $result[$m] = $amount;
            }
        }
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $data[$monthName] = (!empty($result[$i])) ? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
