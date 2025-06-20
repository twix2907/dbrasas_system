<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'activa')
            ->with(['table', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mesero.orders.index', compact('orders'));
    }

    public function create(Table $table)
    {
        // Verificar si la mesa ya tiene una orden activa
        $existingOrder = Order::where('table_id', $table->id)
            ->where('status', 'activa')
            ->first();

        if ($existingOrder) {
            return redirect()->route('mesero.orders.edit', $existingOrder->id)
                ->with('info', 'Esta mesa ya tiene una orden activa.');
        }

        $categories = Category::with('products')->get();

        return view('mesero.orders.create', compact('table', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Crear la orden
        $order = Order::create([
            'table_id' => $request->table_id,
            'user_id' => Auth::id(),
            'status' => 'activa',
            'total' => 0, // Se calculará después
        ]);

        $total = 0;

        // Añadir productos a la orden
        $groupedProducts = [];

        // Agrupar productos idénticos (mismo ID y notas)
        foreach ($request->products as $productData) {
            if (isset($productData['id']) && isset($productData['quantity']) && $productData['quantity'] > 0) {
                $key = $productData['id'] . '-' . ($productData['notes'] ?? '');

                if (!isset($groupedProducts[$key])) {
                    $groupedProducts[$key] = [
                        'id' => $productData['id'],
                        'quantity' => $productData['quantity'],
                        'notes' => $productData['notes'] ?? null,
                    ];
                } else {
                    $groupedProducts[$key]['quantity'] += $productData['quantity'];
                }
            }
        }

        // Crear items para los productos agrupados
        foreach ($groupedProducts as $groupedProduct) {
            $product = Product::find($groupedProduct['id']);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $groupedProduct['quantity'],
                'price' => $product->price,
                'notes' => $groupedProduct['notes'],
            ]);

            $total += ($product->price * $groupedProduct['quantity']);
        }

        // Actualizar el total de la orden
        $order->update(['total' => $total]);

        // Actualizar estado de la mesa
        $table = Table::find($request->table_id);
        $table->update(['status' => 'ocupada']);

        return redirect()->route('mesero.tickets.preview', $order->id)
            ->with('success', 'Orden creada exitosamente. Imprima el ticket para cocina.');
    }

    public function show(Order $order)
    {
        $order->load(['table', 'orderItems.product']);

        return view('mesero.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['table', 'orderItems.product']);
        $categories = Category::with('products')->get();

        return view('mesero.orders.edit', compact('order', 'categories'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $newTotal = 0;

        // Añadir nuevos productos a la orden
        foreach ($request->products as $productData) {
            if (isset($productData['id']) && isset($productData['quantity']) && $productData['quantity'] > 0) {
                $product = Product::find($productData['id']);
                $notes = $productData['notes'] ?? null;

                // Buscar si el producto ya existe con las mismas notas
                $existingItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $product->id)
                    ->where('notes', $notes)
                    ->first();

                if ($existingItem) {
                    // Actualizar cantidad del producto existente
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $productData['quantity']
                    ]);
                    $newTotal += ($product->price * $productData['quantity']);
                } else {
                    // Crear nuevo item si no existe
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $productData['quantity'],
                        'price' => $product->price,
                        'notes' => $productData['notes'] ?? null,
                        'printed' => false, // Marcar como no impreso
                    ]);
                    $newTotal += ($product->price * $productData['quantity']);
                }
            }
        }

        // Recalcular el total de la orden
        $order->refresh(); // Actualizar la relación con los nuevos items
        $total = 0;
        foreach ($order->orderItems as $item) {
            $total += ($item->price * $item->quantity);
        }

        // Actualizar el total de la orden
        $order->update(['total' => $total]);

        return redirect()->route('mesero.tickets.preview', $order->id)
            ->with('success', 'Orden actualizada exitosamente. Imprima el ticket para cocina.');
    }

    public function generatePrebill(Order $order)
    {
        $order->load(['table', 'orderItems.product']);

        // Cambiar el estado de la mesa a cuenta pendiente
        $order->table->update(['status' => 'cuenta_pendiente']);

        return view('mesero.orders.prebill', compact('order'));
    }

    public function removeItem(OrderItem $item)
    {
        $order = $item->order;

        // Restar el precio del item del total de la orden
        $itemTotal = $item->price * $item->quantity;
        $order->update(['total' => $order->total - $itemTotal]);

        // Eliminar el item
        $item->delete();

        return redirect()->route('mesero.orders.show', $order->id)
            ->with('success', 'Producto eliminado de la orden.');
    }
}
