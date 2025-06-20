<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Brasas y Carbon - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #333;
            border-radius: 8px;
            padding: 30px;
            width: 300px;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #ffc107; /* Amarillo */
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #ffc107;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #666;
            border-radius: 4px;
            background-color: #444;
            color: white;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ffc107;
            color: #333;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #ffca2c;
        }
        .error {
            color: #ff6b6b;
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>D'Brasas y Carbon</h1>
        
        @if ($errors->any())
            <div class="error">
                {{ $errors->first('pin_code') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="pin_code">Ingrese su PIN:</label>
                <input type="password" id="pin_code" name="pin_code" maxlength="6" required>
            </div>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>