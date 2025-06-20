<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Cocina - Orden #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            width: 80mm; /* Ancho estándar para tickets */
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .restaurant-name {
            font-size: 16px;
            font-weight: bold;
        }
        .ticket-title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .ticket-info {
            margin-bottom: 10px;
        }
        .ticket-info div {
            margin-bottom: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .items-table td {
            padding: 5px 0;
        }
        .item-notes {
            font-style: italic;
            font-size: 10px;
        }
        .ticket-footer {
            margin-top: 10px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        @media print {
            @page {
                margin: 0;
                size: 80mm 100%;
            }
            body {
                margin: 0;
                padding: 5mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-header">
        <div class="restaurant-name">D'BRASAS Y CARBON</div>
        <div class="ticket-title">*** TICKET DE COCINA ***</div>
    </div>
    
    <div class="ticket-info">
        <div><strong>Orden #:</strong> {{ $order->id }}</div>
        <div><strong>Mesa:</strong> {{ $order->table->number }}</div>
        <div><strong>Mesero:</strong> {{ $order->user->name }}</div>
        <div><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <div class="divider"></div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Cant</th>
                <th>Producto</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="divider"></div>
    
    <div class="ticket-footer">
        <div>Fecha/Hora de impresión: {{ now()->format('d/m/Y H:i:s') }}</div>
        <div>Preparar lo antes posible. ¡Gracias!</div>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Imprimir Ticket</button>
        <a href="{{ route('mesero.tickets.download', $order->id) }}" style="margin-left: 10px;">Descargar PDF</a>
        <a href="{{ route('mesero.orders.show', $order->id) }}" style="margin-left: 10px;">Volver a la Orden</a>
    </div>
</body>
</html>