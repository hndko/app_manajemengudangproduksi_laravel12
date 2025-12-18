<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Materials / Bahan Baku
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->constrained()->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        // Products / Produk Jadi
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->constrained()->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('base_price', 15, 2)->default(0); // Harga pokok produksi
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        // Product Price per Price Type
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('price_type_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'price_type_id']);
        });

        // Stocks / Stok per Gudang
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->string('stockable_type'); // App\Models\Material or App\Models\Product
            $table->unsignedBigInteger('stockable_id');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'stockable_type', 'stockable_id']);
            $table->index(['stockable_type', 'stockable_id']);
        });

        // Stock Movements / Histori Pergerakan Stok
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('materials');
    }
};
