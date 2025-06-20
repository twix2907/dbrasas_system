<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa - D'Brasas y Carbon</title>
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
        h1 {
            color: #ffc107;
            margin: 0;
        }
        .content {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ffc107;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #555;
            background-color: #333;
            color: white;
            border-radius: 4px;
        }
        .btn {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back {
            background-color: #555;
            color: white;
            margin-right: 10px;
        }
        .error {
            color: #f44336;
            font-size: 14px;
            margin-top: 5px;
        }
        .form-actions {
            margin-top: 30px;
            display: flex;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <header>
        <h1>Editar Mesa {{ $table->number }}</h1>
    </header>
    
    <div class="content">
        <div class="status-badge status-{{ $table->status }}">
            Estado actual: {{ ucfirst($table->status) }}
        </div>
        
        <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="number">Número de Mesa:</label>
                <input type="number" id="number" name="number" value="{{ old('number', $table->number) }}" required>
                @error('number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="capacity">Capacidad (personas):</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $table->capacity) }}" required min="1">
                @error('capacity')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-actions">
                <a href="{{ route('admin.tables') }}" class="btn btn-back">Cancelar</a>
                <button type="submit" class="btn">Actualizar Mesa</button>
            </div>
        </form>
    </div>
</body>
</html>