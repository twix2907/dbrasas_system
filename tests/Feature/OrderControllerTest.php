<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\OrderController;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un usuario mesero para autenticación
        $this->user = User::factory()->create(['role' => 'mesero']);
        $this->actingAs($this->user);
    }
    
    /** @test */
    public function it_displays_active_orders()
    {
        // Crear órdenes activas
        $activeOrders = Order::factory()->count(3)->create([
            'status' => 'activa',
            'user_id' => $this->user->id
        ]);
        
        // Crear una orden cerrada (no debe aparecer)
        $closedOrder = Order::factory()->create([
            'status' => 'cerrada',
            'user_id' => $this->user->id
        ]);
        
        $response = $this->get(route('mesero.orders.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('mesero.orders.index');
        $response->assertViewHas('orders');
        
        // Verificar que solo se muestren órdenes activas
        $ordersInView = $response->viewData('orders');
        $this->assertEquals(3, $ordersInView->count());
        $this->assertTrue($ordersInView->contains($activeOrders[0]));
        $this->assertFalse($ordersInView->contains($closedOrder));
    }
    
    /** @test */
    public function it_shows_create_form_for_available_table()
    {
        $table = Table::factory()->create(['status' => 'disponible']);
        
        $response = $this->get(route('mesero.orders.create', $table));
        
        $response->assertStatus(200);
        $response->assertViewIs('mesero.orders.create');
        $response->assertViewHas('table');
        $response->assertViewHas('categories');
    }
    
    /** @test */
    public function it_redirects_to_edit_if_table_has_active_order()
    {
        $table = Table::factory()->create(['status' => 'ocupada']);
        $order = Order::factory()->create([
            'table_id' => $table->id,
            'status' => 'activa',
            'user_id' => $this->user->id
        ]);
        
        $response = $this->get(route('mesero.orders.create', $table));
        
        $response->assertRedirect(route('mesero.orders.edit', $order->id));
        $response->assertSessionHas('info', 'Esta mesa ya tiene una orden activa.');
    }
    
    /** @test */
    public function it_stores_a_new_order()
    {
        $table = Table::factory()->create(['status' => 'disponible']);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 50
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 75
        ]);
        
        $response = $this->post(route('mesero.orders.store'), [
            'table_id' => $table->id,
            'products' => [
                [
                    'id' => $product1->id,
                    'quantity' => 2,
                    'notes' => 'Sin sal'
                ],
                [
                    'id' => $product2->id,
                    'quantity' => 1,
                    'notes' => null
                ]
            ]
        ]);
        
        // Comprobar que se creó la orden
        $this->assertDatabaseHas('orders', [
            'table_id' => $table->id,
            'user_id' => $this->user->id,
            'status' => 'activa',
            'total' => 175 // (50*2) + 75
        ]);
        
        // Comprobar que se crearon los items
        $order = Order::latest()->first();
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 50,
            'notes' => 'Sin sal'
        ]);
        
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 75,
            'notes' => null
        ]);
        
        // Comprobar que se actualizó el estado de la mesa
        $this->assertDatabaseHas('tables', [
            'id' => $table->id,
            'status' => 'ocupada'
        ]);
        
        $response->assertRedirect(route('mesero.tickets.preview', $order->id));
        $response->assertSessionHas('success');
    }
    
    /** @test */
    public function it_groups_identical_products_when_storing()
    {
        $table = Table::factory()->create();
        $product = Product::factory()->create(['price' => 25]);
        
        $response = $this->post(route('mesero.orders.store'), [
            'table_id' => $table->id,
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                    'notes' => 'Extra queso'
                ],
                [
                    'id' => $product->id,
                    'quantity' => 3,
                    'notes' => 'Extra queso' // Misma nota, debe agruparse
                ]
            ]
        ]);
        
        $order = Order::latest()->first();
        
        // Debe existir solo un item con cantidad 5
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 5, // 2+3
            'notes' => 'Extra queso'
        ]);
        
        // Verificar que solo hay un item en esta orden
        $this->assertEquals(1, $order->orderItems()->count());
    }
    
    /** @test */
    public function it_shows_order_details()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        OrderItem::factory()->count(3)->create(['order_id' => $order->id]);
        
        $response = $this->get(route('mesero.orders.show', $order));
        
        $response->assertStatus(200);
        $response->assertViewIs('mesero.orders.show');
        $response->assertViewHas('order');
    }
    
    /** @test */
    public function it_shows_edit_form()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        
        $response = $this->get(route('mesero.orders.edit', $order));
        
        $response->assertStatus(200);
        $response->assertViewIs('mesero.orders.edit');
        $response->assertViewHas('order');
        $response->assertViewHas('categories');
    }
    
    /** @test */
    public function it_updates_an_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'total' => 100
        ]);
        
        $existingItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'quantity' => 2,
            'price' => 50, // Total existente: 100
            'notes' => 'Nota original'
        ]);
        
        $newProduct = Product::factory()->create(['price' => 75]);
        
        $response = $this->put(route('mesero.orders.update', $order), [
            'products' => [
                [
                    'id' => $existingItem->product_id,
                    'quantity' => 1, // Añadir 1 más
                    'notes' => 'Nota original' // Misma nota para que se agregue al item existente
                ],
                [
                    'id' => $newProduct->id,
                    'quantity' => 2,
                    'notes' => 'Nueva nota'
                ]
            ]
        ]);
        
        // Verificar que el item existente se actualizó
        $this->assertDatabaseHas('order_items', [
            'id' => $existingItem->id,
            'quantity' => 3, // 2 originales + 1 nuevo
        ]);
        
        // Verificar que se añadió el nuevo item
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $newProduct->id,
            'quantity' => 2,
            'price' => 75,
            'notes' => 'Nueva nota'
        ]);
        
        // Verificar que el total se actualizó correctamente
        // Total anterior: 100 (2 * 50)
        // Nuevo total: 100 + 50 + (75 * 2) = 300
        $order->refresh();
        $this->assertEquals(300, $order->total);
        
        $response->assertRedirect(route('mesero.tickets.preview', $order->id));
    }
    
    /** @test */
    public function it_generates_prebill()
    {
        $table = Table::factory()->create(['status' => 'ocupada']);
        $order = Order::factory()->create([
            'table_id' => $table->id,
            'user_id' => $this->user->id
        ]);
        
        $response = $this->get(route('mesero.orders.prebill', $order));
        
        $response->assertStatus(200);
        $response->assertViewIs('mesero.orders.prebill');
        
        // Verificar que el estado de la mesa cambió
        $table->refresh();
        $this->assertEquals('cuenta_pendiente', $table->status);
    }
    
    /** @test */
    public function it_removes_item_from_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'total' => 150
        ]);
        
        $item = OrderItem::factory()->create([
            'order_id' => $order->id,
            'quantity' => 2,
            'price' => 50 // Total: 100
        ]);
        
        $response = $this->delete(route('mesero.order-items.remove', $item));
        
        // Verificar que el item se eliminó
        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
        
        // Verificar que el total de la orden se actualizó
        $order->refresh();
        $this->assertEquals(50, $order->total); // 150 - 100
        
        $response->assertRedirect(route('mesero.orders.show', $order->id));
        $response->assertSessionHas('success');
    }
}