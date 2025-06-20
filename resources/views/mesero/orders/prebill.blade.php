<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precuenta - D'Brasas y Carbon</title>
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
            max-width: 800px;
            margin: 0 auto;
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
        .prebill {
            background-color: #fff;
            color: #000;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .prebill-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .prebill-title {
            color: #000;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .prebill-subtitle {
            color: #555;
            font-size: 14px;
        }
        .prebill-details {
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .prebill-items {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .prebill-items th, .prebill-items td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .prebill-items th {
            background-color: #f9f9f9;
        }
        .item-notes {
            font-size: 12px;
            color: #777;
            font-style: italic;
        }
        .prebill-total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .prebill-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        .print-actions {
            margin-top: 20px;
            text-align: center;
        }
        .print-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
        }
        .back-btn {
            background-color: #555;
            color: white;
        }
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }
            header, .nav-links, .print-actions {
                display: none;
            }
            .content {
                padding: 0;
                max-width: 100%;
            }
            .prebill {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Precuenta - Orden #{{ $order->id }}</h1>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </header>
    
    <div class="content">
        <div class="nav-links">
            <a href="{{ route('mesero.orders.show', $order->id) }}">Volver a la Orden</a>
            <a href="{{ route('mesero.tables') }}">Volver a Mesas</a>
        </div>
        
        <div class="prebill">
            <div class="prebill-header">
                <h2 class="prebill-title">D'Brasas y Carbon</h2>
                <div class="prebill-subtitle">Restaurante Peruano</div>
                <div class="prebill-subtitle">Av. Ejemplo 123, Lima</div>
                <div class="prebill-subtitle">Tel: 123-456-7890</div>
            </div>
            
            <div class="prebill-details">
                <div class="detail-row">
                    <div><strong>Precuenta #:</strong> {{ $order->id }}</div>
                    <div><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                </div>
                <div class="detail-row">
                    <div><strong>Mesa:</strong> {{ $order->table->number }}</div>
                    <div><strong>Atendido por:</strong> {{ $order->user->name }}</div>
                </div>
            </div>
            
            <table class="prebill-items">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                {{ $item->product->name }}
                                @if($item->notes)
                                    <div class="item-notes">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td>S/. {{ number_format($item->price, 2) }}</td>
                            <td>S/. {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="prebill-total">
                Total: S/. {{ number_format($order->total, 2) }}
            </div>
            
            <div class="prebill-footer">
                <p>¡Gracias por su preferencia!</p>
                <p>Por favor, realice el pago en caja.</p>
                <p>Vuelva pronto.</p>
            </div>
        </div>
        
        <div class="print-actions">
            <button class="print-btn" onclick="window.print()">Imprimir Precuenta</button>
            <a href="{{ route('mesero.tables') }}" class="btn back-btn">Volver a Mesas</a>
        </div>
    </div>
</body>
</html>