<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class TableController extends Controller
{
    
    public function index()
{
    $tables = Table::orderBy('number')->get();
    $isAdmin = Auth::user()->role === 'admin';
    
    if ($isAdmin) {
        return view('admin.tables', compact('tables', 'isAdmin'));
    } else {
        return view('mesero.tables', compact('tables', 'isAdmin'));
    }
}

    public function create()
{
    if (Auth::user()->role === 'admin') {
        return view('admin.tables_create');
    } else {
        return view('mesero.tables_create');
    }
}

    public function store(Request $request)
{
    $request->validate([
        'number' => 'required|integer|unique:tables',
        'capacity' => 'required|integer|min:1',
    ]);

    Table::create([
        'number' => $request->number,
        'capacity' => $request->capacity,
        'status' => 'disponible',
    ]);

    // Redirigir según el rol del usuario
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.tables')->with('success', 'Mesa creada exitosamente');
    } else {
        return redirect()->route('mesero.tables')->with('success', 'Mesa creada exitosamente');
    }
}

    public function edit(Table $table)
{
    if (Auth::user()->role === 'admin') {
        return view('admin.tables_edit', compact('table'));
    } else {
        return view('mesero.tables_edit', compact('table'));
    }
}

    public function update(Request $request, Table $table)
{
    $request->validate([
        'number' => 'required|integer|unique:tables,number,' . $table->id,
        'capacity' => 'required|integer|min:1',
    ]);

    $table->update([
        'number' => $request->number,
        'capacity' => $request->capacity,
    ]);

    // Redirigir según el rol del usuario
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.tables')->with('success', 'Mesa actualizada exitosamente');
    } else {
        return redirect()->route('mesero.tables')->with('success', 'Mesa actualizada exitosamente');
    }
}

public function destroy(Table $table)
{
    // Verificar si la mesa tiene órdenes activas
    $hasActiveOrders = Order::where('table_id', $table->id)
                          ->where('status', 'activa')
                          ->exists();
    
    if ($hasActiveOrders) {
        $route = Auth::user()->role === 'admin' ? 'admin.tables' : 'mesero.tables';
        return redirect()->route($route)->with('error', 'No se puede eliminar una mesa con órdenes activas');
    }
    
    $table->delete();
    
    $route = Auth::user()->role === 'admin' ? 'admin.tables' : 'mesero.tables';
    return redirect()->route($route)->with('success', 'Mesa eliminada exitosamente');
}

public function changeStatus(Table $table, $status)
{
    if (!in_array($status, ['disponible', 'ocupada', 'cuenta_pendiente'])) {
        $route = Auth::user()->role === 'admin' ? 'admin.tables' : 'mesero.tables';
        return redirect()->route($route)->with('error', 'Estado inválido');
    }

    $table->update(['status' => $status]);
    
    $route = Auth::user()->role === 'admin' ? 'admin.tables' : 'mesero.tables';
    return redirect()->route($route)->with('success', 'Estado de mesa actualizado');
}
}