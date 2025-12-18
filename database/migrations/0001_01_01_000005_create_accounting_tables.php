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
        // Chart of Accounts / Daftar Akun
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // 1-1-001
            $table->string('name');
            $table->enum('type', ['aset', 'liabilitas', 'ekuitas', 'pendapatan', 'beban']);
            $table->enum('normal_balance', ['debit', 'kredit']);
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_locked')->default(false); // Cannot be deleted if true
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });

        // Fiscal Periods / Periode Akuntansi
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 2024, 2025
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('is_active');
        });

        // Journal Entries / Jurnal Umum
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // JU-2024-0001
            $table->date('date');
            $table->text('description');
            $table->string('reference')->nullable(); // Reference to source document
            $table->string('reference_type')->nullable(); // sales, purchase, production, etc
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('status', ['draft', 'posted', 'void'])->default('draft');
            $table->foreignId('fiscal_period_id')->constrained()->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('posted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index('status');
            $table->index(['reference_type', 'reference_id']);
        });

        // Journal Entry Details / Detail Jurnal
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();

            $table->index('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_details');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('fiscal_periods');
        Schema::dropIfExists('chart_of_accounts');
    }
};
