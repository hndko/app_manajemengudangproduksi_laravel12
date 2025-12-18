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
        // Sales Transactions / Transaksi Penjualan
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // INV-2024-0001
            $table->date('date');
            $table->foreignId('consumer_id')->constrained()->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->foreignId('price_type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('installment_type_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('status', ['draft', 'confirmed', 'shipped', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('delivery_note_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('date');
            $table->index('status');
            $table->index('payment_status');
        });

        // Sales Transaction Items
        Schema::create('sales_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Sales Payments / Pembayaran
        Schema::create('sales_payments', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // PAY-2024-0001
            $table->foreignId('sales_transaction_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->enum('method', ['cash', 'transfer', 'giro', 'other'])->default('cash');
            $table->string('reference')->nullable(); // Reference number
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('date');
        });

        // Expenses / Pengeluaran
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // EXP-2024-0001
            $table->date('date');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->string('receipt_image')->nullable();
            $table->enum('status', ['draft', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
        });

        // Backups
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->unsignedBigInteger('size'); // in bytes
            $table->string('type')->default('full'); // full, database, files
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('sales_payments');
        Schema::dropIfExists('sales_transaction_items');
        Schema::dropIfExists('sales_transactions');
    }
};
