<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cocina - D'Brasas y Carbon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            margin: 0;
            padding: 0;
        }
        .dashboard {
            padding: 20px;
        }
        header {
            background-color: #333;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            color: #ffc107;
            margin: 0;
        }
        .logout-form {
            display: inline;
        }
        .logout-btn {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Cocina</h1>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </header>
    
    <div class="content">
        <h2>Bienvenido, {{ Auth::user()->name }}</h2>
        <p>Desde aquí podrás ver y gestionar los pedidos pendientes.</p>
        
        <!-- Aquí irá el módulo de cocina -->
    </div>
</body>
</html>