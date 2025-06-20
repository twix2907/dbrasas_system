<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Orden - D'Brasas y Carbon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1, h2, h3 {
            color: #ffc107;
            margin: 0;
        }
        .logout-form {
            display: inline;
        }
        .logout-btn, .btn {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .content {
            padding: 20px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #4CAF50;
            color: white;
        }
        .alert-error {
            background-color: #f44336;
            color: white;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            color: #ffc107;
            margin-right: 15px;
            text-decoration: none;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .order-details {
            background-color: #333;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #ffc107;
            margin-right: 10px;
        }
        .order-items {
            background-color: #333;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .items-title {
            margin-bottom: 15px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th, .items-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        .items-table th {
            color: #ffc107;
        }
        .action-btn {
            background-color: #555;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .action-btn:hover {
            background-color: #777;
        }
        .action-btn.delete {
            background-color: #f44336;
        }
        .order-total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
        }
        .total-amount {
            color: #ffc107;
            font-weight: bold;
        }
        .order-actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .product-note {
            font-size: 12px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <header>
        <h1>Detalles de Orden #{{ $order->id }}</h1>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </header>
    
    <div class="content">
        <div class="nav-links">
            <a href="{{ route('mesero.tables') }}">Volver a Mesas</a>
            <a href="{{ route('mesero.orders.index') }}">Todas las Órdenes</a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="order-details">
            <h2>Información de la Orden</h2>
            
            <div class="detail-row">
                <span class="detail-label">Mesa:</span>
                <span>{{ $order->table->number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Estado:</span>
                <span>{{ ucfirst($order->status) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Mesero:</span>
                <span>{{ $order->user->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Creada:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="order-items">
            <h3 class="items-title">Productos</h3>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                {{ $item->product->name }}
                                @if($item->notes)
                                    <div class="product-note">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>S/. {{ number_format($item->price, 2) }}</td>
                            <td>S/. {{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('mesero.order-items.remove', $item->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="order-total">
                <span>Total: </span>
                <span class="total-amount">S/. {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        
        <div class="order-actions">
    <a href="{{ route('mesero.orders.edit', $order->id) }}" class="btn">Añadir Productos</a>
    <a href="{{ route('mesero.tickets.preview', $order->id) }}" class="btn" style="background-color: #4CAF50; color: white;">Imprimir Ticket</a>
    <a href="{{ route('mesero.tickets.last', $order->id) }}" class="btn" style="background-color: #2196F3; color: white;">Imprimir Nuevos</a>
    <a href="{{ route('mesero.orders.prebill', $order->id) }}" class="btn">Generar Precuenta</a>
</div>
    </div>
</body>
</html>