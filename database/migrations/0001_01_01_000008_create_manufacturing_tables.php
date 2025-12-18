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
        // Production Teams / Tim Produksi
        Schema::create('production_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Productions / Produksi
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // PRD-2024-0001
            $table->date('date');
            $table->foreignId('production_team_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
        });

        // Production Materials / Bahan yang digunakan untuk produksi
        Schema::create('production_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_planned')->default(0);
            $table->integer('quantity_used')->default(0);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Production Products / Produk yang dihasilkan dari produksi
        Schema::create('production_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_planned')->default(0);
            $table->integer('quantity_produced')->default(0);
            $table->integer('quantity_rejected')->default(0);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Delivery Notes / Surat Jalan
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // SJ-2024-0001
            $table->date('date');
            $table->foreignId('consumer_id')->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->text('shipping_address')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->enum('status', ['draft', 'shipped', 'delivered', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
        });

        // Delivery Note Items
        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Returns / Retur Barang
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // RTR-2024-0001
            $table->date('date');
            $table->foreignId('delivery_note_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('consumer_id')->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->enum('type', ['customer_return', 'expedition_return'])->default('customer_return');
            $table->enum('status', ['draft', 'approved', 'processed', 'cancelled'])->default('draft');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
            $table->index('type');
        });

        // Return Items
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->enum('condition', ['baik', 'rusak', 'cacat'])->default('baik');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('delivery_note_items');
        Schema::dropIfExists('delivery_notes');
        Schema::dropIfExists('production_products');
        Schema::dropIfExists('production_materials');
        Schema::dropIfExists('productions');
        Schema::dropIfExists('production_teams');
    }
};
