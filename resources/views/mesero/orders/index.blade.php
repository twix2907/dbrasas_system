<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes Activas - D'Brasas y Carbon</title>
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
        h1, h2 {
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
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        .orders-table th {
            background-color: #333;
            color: #ffc107;
        }
        .orders-table tr:hover {
            background-color: #2a2a2a;
        }
        .action-link {
            color: #ffc107;
            text-decoration: none;
            margin-right: 10px;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        .no-orders {
            text-align: center;
            margin-top: 50px;
            color: #888;
        }
    </style>
</head>
<body>
    <header>
        <h1>Órdenes Activas</h1>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </header>
    
    <div class="content">
        <div class="nav-links">
            <a href="{{ route('mesero.tables') }}">Volver a Mesas</a>
        </div>
        
        <h2>Listado de Órdenes Activas</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(count($orders) > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mesa</th>
                        <th>Total</th>
                        <th>Creada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>Mesa {{ $order->table->number }}</td>
                            <td>S/. {{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('mesero.orders.show', $order->id) }}" class="action-link">Ver</a>
                                <a href="{{ route('mesero.orders.edit', $order->id) }}" class="action-link">Añadir</a>
                                <a href="{{ route('mesero.orders.prebill', $order->id) }}" class="action-link">Precuenta</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-orders">
                <p>No hay órdenes activas en este momento.</p>
            </div>
        @endif
    </div>
</body>
</html>