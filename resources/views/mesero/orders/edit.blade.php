<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Productos - D'Brasas y Carbon</title>
    
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
        .table-info {
            background-color: #333;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .categories-tabs {
            display: flex;
            margin-bottom: 15px;
            overflow-x: auto;
            padding-bottom: 5px;
        }
        .category-tab {
            padding: 10px 15px;
            margin-right: 5px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px 4px 0 0;
            cursor: pointer;
        }
        .category-tab.active {
            background-color: #ffc107;
            color: #333;
            font-weight: bold;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .product-card {
            background-color: #333;
            border-radius: 8px;
            padding: 15px;
            position: relative;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-price {
            color: #ffc107;
            margin-bottom: 10px;
        }
        .product-description {
            font-size: 14px;
            color: #ccc;
            margin-bottom: 15px;
        }
        .product-controls {
            display: flex;
            align-items: center;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }
        .quantity-btn {
            background-color: #555;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
            margin: 0 5px;
            background-color: #444;
            border: none;
            color: white;
            padding: 5px;
            border-radius: 4px;
        }
        .add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .product-notes {
            margin-top: 10px;
        }
        .notes-input {
            width: 100%;
            background-color: #444;
            border: none;
            color: white;
            padding: 8px;
            border-radius: 4px;
            margin-top: 5px;
        }
        .order-summary {
            background-color: #333;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .summary-title {
            margin-bottom: 15px;
            color: #ffc107;
        }
        .selected-products {
            margin-bottom: 20px;
        }
        .selected-product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #444;
        }
        .product-info {
            flex-grow: 1;
        }
        .product-note {
            font-size: 12px;
            color: #999;
            font-style: italic;
            margin-top: 5px;
        }
        .remove-product {
            color: #f44336;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        .total-section {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #444;
        }
        .total-label {
            font-weight: bold;
        }
        .total-amount {
            color: #ffc107;
            font-weight: bold;
        }
        .form-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .category-content {
            display: none;
        }
        .category-content.active {
            display: block;
        }
    </style>

</head>
<body>
    <header>
        <h1>Añadir Productos - Orden #{{ $order->id }}</h1>
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
        
        <div class="table-info">
            <h3>Mesa: {{ $order->table->number }}</h3>
            <p>Total actual: S/. {{ number_format($order->total, 2) }}</p>
        </div>
        
        <form action="{{ route('mesero.orders.update', $order->id) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')
            
            <div class="categories-tabs">
                @foreach($categories as $index => $category)
                    <button type="button" class="category-tab {{ $index === 0 ? 'active' : '' }}" 
                            onclick="showCategory({{ $index }})">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            
            @foreach($categories as $index => $category)
                <div class="category-content {{ $index === 0 ? 'active' : '' }}" id="category-{{ $index }}">
                    <div class="products-grid">
                        @foreach($category->products as $product)
                            <div class="product-card">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">S/. {{ number_format($product->price, 2) }}</div>
                                <div class="product-description">{{ $product->description }}</div>
                                <div class="product-controls">
                                    <div class="quantity-control">
                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity({{ $product->id }})">-</button>
                                        <input type="number" class="quantity-input" id="quantity-{{ $product->id }}" value="1" min="1" max="99">
                                        <button type="button" class="quantity-btn" onclick="increaseQuantity({{ $product->id }})">+</button>
                                    </div>
                                    <button type="button" class="add-btn" onclick="addProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">Añadir</button>
                                </div>
                                <div class="product-notes">
                                    <label for="notes-{{ $product->id }}">Notas:</label>
                                    <input type="text" class="notes-input" id="notes-{{ $product->id }}" placeholder="Especificaciones para cocina">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <div class="order-summary">
                <h3 class="summary-title">Productos a Añadir</h3>
                
                <div class="selected-products" id="selectedProducts">
                    <!-- Aquí se añaden dinámicamente los productos seleccionados -->
                    <div class="no-products" id="noProductsMessage">
                        No hay productos seleccionados
                    </div>
                </div>
                
                <div class="total-section">
                    <div class="total-label">Total Adicional:</div>
                    <div class="total-amount" id="totalAmount">S/. 0.00</div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('mesero.orders.show', $order->id) }}" class="btn" style="background-color: #555; color: white;">Cancelar</a>
                    <button type="submit" class="btn" id="submitBtn" disabled>Añadir a la Orden</button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        let selectedProducts = [];
        let total = 0;
        
        function showCategory(index) {
            // Ocultar todas las categorías
            document.querySelectorAll('.category-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Quitar active de todas las pestañas
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostrar la categoría seleccionada
            document.getElementById('category-' + index).classList.add('active');
            
            // Activar la pestaña seleccionada
            document.querySelectorAll('.category-tab')[index].classList.add('active');
        }
        
        function decreaseQuantity(productId) {
            const input = document.getElementById('quantity-' + productId);
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
        
        function increaseQuantity(productId) {
            const input = document.getElementById('quantity-' + productId);
            input.value = parseInt(input.value) + 1;
        }
        
        function addProduct(productId, productName, productPrice) {
    const quantity = parseInt(document.getElementById('quantity-' + productId).value);
    const notes = document.getElementById('notes-' + productId).value;
    
    // Buscar si el producto ya existe con las mismas notas
    const existingIndex = selectedProducts.findIndex(p => 
        p.id === productId && p.notes === notes
    );
    
    if (existingIndex >= 0) {
        // Actualizar cantidad del producto existente
        selectedProducts[existingIndex].quantity += quantity;
        selectedProducts[existingIndex].subtotal = selectedProducts[existingIndex].price * selectedProducts[existingIndex].quantity;
    } else {
        // Añadir nuevo producto
        const product = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            notes: notes,
            subtotal: productPrice * quantity
        };
        selectedProducts.push(product);
    }
    
    updateOrderSummary();
    
    // Limpiar campos
    document.getElementById('quantity-' + productId).value = 1;
    document.getElementById('notes-' + productId).value = '';
}
        
        function removeProduct(index) {
            selectedProducts.splice(index, 1);
            updateOrderSummary();
        }
        
        function updateOrderSummary() {
            const container = document.getElementById('selectedProducts');
            const noProductsMessage = document.getElementById('noProductsMessage');
            const totalAmountElement = document.getElementById('totalAmount');
            const submitBtn = document.getElementById('submitBtn');
            
            // Calcular total
            total = selectedProducts.reduce((sum, product) => sum + product.subtotal, 0);
            
            // Actualizar el total
            totalAmountElement.textContent = 'S/. ' + total.toFixed(2);
            
            // Limpiar el contenedor
            container.innerHTML = '';
            
            // Mostrar productos seleccionados
            if (selectedProducts.length > 0) {
                selectedProducts.forEach((product, index) => {
                    const div = document.createElement('div');
                    div.className = 'selected-product';
                    div.innerHTML = `
                        <div class="product-info">
                            <div>${product.quantity} x ${product.name} - S/. ${product.subtotal.toFixed(2)}</div>
                            ${product.notes ? `<div class="product-note">${product.notes}</div>` : ''}
                            <input type="hidden" name="products[${index}][id]" value="${product.id}">
                            <input type="hidden" name="products[${index}][quantity]" value="${product.quantity}">
                            <input type="hidden" name="products[${index}][notes]" value="${product.notes}">
                        </div>
                        <button type="button" class="remove-product" onclick="removeProduct(${index})">×</button>
                    `;
                    container.appendChild(div);
                });
                
                // Ocultar mensaje de no productos
                if (noProductsMessage) {
                    noProductsMessage.style.display = 'none';
                }
                
                // Habilitar botón de envío
                submitBtn.disabled = false;
            } else {
                // Mostrar mensaje de no productos
                const div = document.createElement('div');
                div.className = 'no-products';
                div.textContent = 'No hay productos seleccionados';
                container.appendChild(div);
                
                // Deshabilitar botón de envío
                submitBtn.disabled = true;
            }
        }
    </script>
</body>
</html>