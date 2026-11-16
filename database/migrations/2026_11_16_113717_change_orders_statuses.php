<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite' || ! Schema::hasTable('orders')) {
            return;
        }

        $this->rebuildOrdersTable(
            ['New Order', 'Order In Production', 'Order Dispatched', 'Order Cancelled'],
            'New Order',
            "CASE status
                WHEN 'New' THEN 'New Order'
                WHEN 'In production' THEN 'Order In Production'
                WHEN 'Despatched' THEN 'Order Dispatched'
                WHEN 'Cancelled' THEN 'Order Cancelled'
                ELSE status
            END",
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite' || ! Schema::hasTable('orders')) {
            return;
        }

        $this->rebuildOrdersTable(
            ['New', 'In production', 'Despatched', 'Cancelled'],
            'New',
            "CASE status
                WHEN 'New Order' THEN 'New'
                WHEN 'Order In Production' THEN 'In production'
                WHEN 'Order Dispatched' THEN 'Despatched'
                WHEN 'Order Cancelled' THEN 'Cancelled'
                ELSE status
            END",
        );
    }

    /**
     * Rebuild the SQLite orders table with the supplied status constraint.
     *
     * @param  array<int, string>  $statuses
     */
    private function rebuildOrdersTable(array $statuses, string $defaultStatus, string $statusExpression): void
    {
        Schema::withoutForeignKeyConstraints(function () use ($statuses, $defaultStatus, $statusExpression): void {
            DB::transaction(function () use ($statuses, $defaultStatus, $statusExpression): void {
                Schema::rename('orders', 'orders_previous');

                Schema::create('orders_replacement', function (Blueprint $table) use ($statuses, $defaultStatus): void {
                    $table->id();
                    $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                    $table->string('order_number')->unique();
                    $table->string('product');
                    $table->unsignedInteger('quantity');
                    $table->decimal('unit_price', 10, 2);
                    $table->enum('status', $statuses)->default($defaultStatus);
                    $table->string('due_date')->nullable();
                    $table->timestamps();
                });

                DB::statement("INSERT INTO orders_replacement (id, customer_id, order_number, product, quantity, unit_price, status, due_date, created_at, updated_at)
                    SELECT id, customer_id, order_number, product, quantity, unit_price, {$statusExpression}, due_date, created_at, updated_at
                    FROM orders_previous");

                Schema::drop('orders_previous');
                Schema::rename('orders_replacement', 'orders');
            });
        });
    }
};
