<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Mesas - D'Brasas y Carbon</title>
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
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .table-card {
            background-color: #333;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        .table-card.disponible {
            border: 2px solid #4CAF50;
        }
        .table-card.ocupada {
            border: 2px solid #f44336;
        }
        .table-card.cuenta_pendiente {
            border: 2px solid #ffc107;
        }
        .table-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .table-status {
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-disponible {
            background-color: #4CAF50;
            color: white;
        }
        .status-ocupada {
            background-color: #f44336;
            color: white;
        }
        .status-cuenta_pendiente {
            background-color: #ffc107;
            color: #333;
        }
        .table-capacity {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .table-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
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
        .status-actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 10px;
        }
        .add-table {
            margin-top: 20px;
        }
        
        /* Estilos para dispositivos móviles */
        @media (max-width: 600px) {
            .tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 10px;
            }
            .table-card {
                padding: 10px;
            }
            .table-number {
                font-size: 18px;
            }
            .action-btn {
                padding: 4px 8px;
                font-size: 10px;
            }
        }
        
        .admin-menu {
            background-color: #333;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .admin-menu a {
            color: #ffc107;
            margin-right: 15px;
            text-decoration: none;
        }
        
        .admin-menu a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Administración de Mesas</h1>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </header>
    
    <div class="content">
        <div class="admin-menu">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.tables') }}">Mesas</a>
            <!-- Otros enlaces del menú de administración -->
        </div>
        
        <h2>Gestión de Mesas</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="add-table">
            <a href="{{ route('admin.tables.create') }}" class="btn">Agregar Mesa</a>
        </div>
        
        <div class="tables-grid">
            @foreach($tables as $table)
                <div class="table-card {{ $table->status }}">
                    <div class="table-number">Mesa {{ $table->number }}</div>
                    <div class="table-status">
                        <span class="status-badge status-{{ $table->status }}">
                            {{ ucfirst($table->status) }}
                        </span>
                    </div>
                    <div class="table-capacity">
                        Capacidad: {{ $table->capacity }} personas
                    </div>
                    
                    <div class="status-actions">
                        @if($table->status != 'disponible')
                            <form action="{{ route('admin.tables.status', ['table' => $table->id, 'status' => 'disponible']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn">Marcar Disponible</button>
                            </form>
                        @endif
                        
                        @if($table->status != 'ocupada')
                            <form action="{{ route('admin.tables.status', ['table' => $table->id, 'status' => 'ocupada']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn">Marcar Ocupada</button>
                            </form>
                        @endif
                        
                        @if($table->status != 'cuenta_pendiente')
                            <form action="{{ route('admin.tables.status', ['table' => $table->id, 'status' => 'cuenta_pendiente']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn">Cuenta Pendiente</button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="table-actions">
                        <a href="{{ route('admin.tables.edit', $table->id) }}" class="action-btn">Editar</a>
                        
                        <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta mesa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>