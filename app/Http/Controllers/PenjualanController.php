<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\detail_transact;
use App\Models\Members;
use App\Models\Selling;
use App\Models\Products;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction = Selling::with('user', 'member', 'details.product')->get();
        return view('pembelian.index', compact('transaction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Products::all();
        return view('pembelian.tambah', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member' => 'required|in:non-member,member',
            'total_bayar' => 'required|numeric',
        ]);

        $user = Auth::user();
        $carts = Cart::with('product')->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->qty;
        }

        $kembalian = $request->total_bayar - $totalPrice;

        if ($request->member == 'member') {
            $request->validate([
                'phoneNumber' => 'required|numeric',
            ]);

            $phonenumber = $request->phoneNumber;

            $member = Members::where('phone_number', $phonenumber)->first();


            if ($member == null) {
                $poinmember = $totalPrice * 10 / 100;

                $member = Members::create([
                    'phone_number' => $phonenumber,
                    'poin_member' => $poinmember,
                ]);
            }
            // else {
            //     $poinmember = $member->poin_member;

            //     if ($poinmember == 0) {
            //         $poinmember = $totalPrice * 10 / 100;
            //         $member->poin_member += $poinmember;
            //         $member->save();
            //     }

            // }

            $sellingData = [];
            foreach ($carts as $cart) {

                $sellingData[] = [
                    'product_name' => $cart->product->name,
                    'price' => $cart->product->price,
                    'qty' => $cart->qty,
                    'subtotal' => $cart->product->price * $cart->qty,
                ];

                $checkpoin = 0;

                if ($member) {
                    $checkpoin = Selling::where('member_id', $member->id)->count();
                }
            }
            return view('pembelian.checkMember', [
                'dataTransaction' => $sellingData,
                'member' => $member,
                'totalBayar' => $request->total_bayar,
                'subtotal' => $cart->product->price * $cart->qty,
                'poinmember' => $member->poin_member,
                'checkPoint' => $checkpoin
            ]);
        }

        $sellingData = [];
        $transaction = Selling::create([
            'member_id' => null,
            'total_price' => $totalPrice,
            'user_id' => $user->id,
        ]);
        foreach ($carts as $cart) {
            detail_transact::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
            ]);

            $product = Products::find($cart->product->id);
            $product->stock = $product->stock - $cart->qty;
            $product->save();

            $sellingData[] = [
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'subtotal' => $cart->product->price * $cart->qty,
            ];
        }

        Cart::truncate();

        $invoiceNumber = Selling::orderBy('created_at', 'desc')->count();
        $userName = $user->name;

        return view('pembelian.result', [
            'sellingData' => $sellingData,
            'totalPrice' => $totalPrice,
            'userName' => $userName,
            'kembalian' => $kembalian,
            'invoiceNumber' => $invoiceNumber
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $cartData = $request->query('products', []);

        $products = Products::whereIn('id', array_keys($cartData))->get();

        return view('pembelian.member', compact('products', 'cartData'));
    }

    public function checkMember(Request $request)
    {

        $member = Members::where('phone_number', $request->phone_number)->first();

        if ($member && $request->name) {
            $member->name = $request->name;
            $member->save();
        }

        $user = Auth::user();
        $carts = Cart::with('product')->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->qty;
        }
        $poinmember = $totalPrice * 10 / 100;
        if ($request->checkPoin) {
            $totalPrice -= $member->poin_member;
            if ($totalPrice < 0) {
                $totalPrice = 0;
            }
            $member->poin_member = 0;
            $member->save();
        }

        $totalPrice = (int) $totalPrice;

        $kembalian = $request->total_bayar - $totalPrice;

        $sellingData = [];
        $transaction = Selling::create([
            'member_id' => $member->id,
            'total_price' => $totalPrice,
            'user_id' => $user->id,
        ]);
        foreach ($carts as $cart) {
            detail_transact::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
            ]);

            $product = Products::find($cart->product->id);
            $product->stock = $product->stock - $cart->qty;
            $product->save();

            $sellingData[] = [
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'subtotal' => $cart->product->price * $cart->qty,
            ];
        }

        $member->poin_member += $poinmember;
        $member->save();
        Cart::truncate();

        $invoiceNumber = Selling::orderBy('created_at', 'desc')->count() + 1;
        $userName = $user->name;


        return view('pembelian.result', [
            'sellingData' => $sellingData,
            'totalPrice' => $totalPrice,
            'userName' => $userName,
            'kembalian' => $kembalian,
            'invoiceNumber' => $invoiceNumber
        ]);
    }


    public function CetakPdf(Request $request, $id)
    {

        $transaction = Selling::where('id', $id)->with('user', 'member', 'details.product')->first();

        $data = [
            'transaction' => $transaction,
            'member' => $transaction->member,
            'details' => $transaction->details,
        ];

        $pdf = Pdf::loadView('pembelian.invoice', $data);
        return $pdf->stream('bukti-pembelian.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cart(Request $request)
    {

        $request->validate([
            'cart_data' => 'required|json'
        ]);

        $cartItem = json_decode($request->cart_data, true);

        foreach ($cartItem as $productList => $qty) {
            Cart::create([
                'product_id' => $productList,
                'qty' => $qty,
            ]);
        }

        $cartItems = Cart::all();

        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $product = Products::find($item->product_id);
            if ($product) {
                $totalPrice += $product->price * $item->qty;
            }
        }

        return view('pembelian.member', compact('cartItems', 'totalPrice'));
    }
}
