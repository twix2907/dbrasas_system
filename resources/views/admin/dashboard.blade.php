<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - D'Brasas y Carbon</title>
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
        
        .admin-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .admin-card {
            background-color: #333;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        
        .admin-card h3 {
            color: #ffc107;
            margin-top: 0;
        }
        
        .admin-card a {
            display: inline-block;
            background-color: #ffc107;
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
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
        
        <h2>Bienvenido, {{ Auth::user()->name }}</h2>
        <p>Desde aquí podrás gestionar todo el sistema del restaurante.</p>
        
        <div class="admin-cards">
            <div class="admin-card">
                <h3>Gestión de Mesas</h3>
                <p>Administra las mesas del restaurante</p>
                <a href="{{ route('admin.tables') }}">Administrar</a>
            </div>
            
            <div class="admin-card">
                <h3>Gestión de Productos</h3>
                <p>Administra el menú y los productos</p>
                <a href="#">Próximamente</a>
            </div>
            
            <div class="admin-card">
                <h3>Reportes</h3>
                <p>Visualiza reportes de ventas</p>
                <a href="#">Próximamente</a>
            </div>
            
            <div class="admin-card">
                <h3>Configuración</h3>
                <p>Configuración general del sistema</p>
                <a href="#">Próximamente</a>
            </div>
        </div>
    </div>
</body>
</html>