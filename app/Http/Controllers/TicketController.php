<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function generateKitchenTicket(Order $order)
    {
        $order->load(['table', 'orderItems.product', 'user']);
        
        $pdf = PDF::loadView('tickets.kitchen', compact('order'));
        
        return $pdf->stream('ticket-cocina-' . $order->id . '.pdf');
    }
    
    public function downloadKitchenTicket(Order $order)
    {
        $order->load(['table', 'orderItems.product', 'user']);
        
        $pdf = PDF::loadView('tickets.kitchen', compact('order'));
        
        return $pdf->download('ticket-cocina-' . $order->id . '.pdf');
    }
    
    public function previewKitchenTicket(Order $order)
    {
        $order->load(['table', 'orderItems.product', 'user']);
        
        return view('tickets.kitchen', compact('order'));
    }
    
    public function printLastItems(Order $order)
    {
        // Obtener los últimos items añadidos (los que no tienen ticket impreso)
        $items = OrderItem::where('order_id', $order->id)
                    ->where('printed', false)
                    ->with('product')
                    ->get();
        
        if ($items->isEmpty()) {
            return redirect()->back()->with('info', 'No hay nuevos productos para imprimir');
        }
        
        // Marcar los items como impresos
        foreach ($items as $item) {
            $item->update(['printed' => true]);
        }
        
        $order->load(['table', 'user']);
        
        $pdf = PDF::loadView('tickets.kitchen_partial', compact('order', 'items'));
        
        return $pdf->stream('ticket-cocina-items-' . $order->id . '.pdf');
    }
}