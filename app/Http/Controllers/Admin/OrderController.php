<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send status update email
        try {
            if ($order->billing_email && $oldStatus !== $request->status) {
                \Illuminate\Support\Facades\Mail::to($order->billing_email)
                    ->queue(new \App\Mail\OrderStatusUpdate($order, $oldStatus));
            }
        } catch (\Exception $e) {
            // Don't fail if email fails
        }

        return redirect()->back()->with('success', "Order #{$order->id} status updated to {$request->status}");
    }

    public function downloadInvoice(Order $order)
    {
        $order->load('items.product');
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        return $pdf->download("invoice-order-{$order->id}.pdf");
    }
}
