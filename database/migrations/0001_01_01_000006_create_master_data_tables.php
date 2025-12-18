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
        // Consumers / Konsumen
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // CUST-001
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('npwp')->nullable();
            $table->string('contact_person')->nullable();
            $table->enum('type', ['perorangan', 'perusahaan'])->default('perorangan');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        // Categories / Kategori
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['material', 'produk'])->default('produk');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
        });

        // Units / Satuan
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // PCS, KG, M, etc
            $table->string('name'); // Pieces, Kilogram, Meter
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Price Types / Jenis Harga
        Schema::create('price_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Harga Retail, Harga Grosir, Harga Distributor
            $table->text('description')->nullable();
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Warehouses / Gudang
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('person_in_charge')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Installment Types / Jenis Cicilan
        Schema::create('installment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cash, Cicilan 3 Bulan, Cicilan 6 Bulan
            $table->integer('tenor')->default(0); // in months, 0 = cash
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Site Settings / Pengaturan Website
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, boolean, number
            $table->string('group')->default('general'); // general, company, invoice, etc
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('installment_types');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('price_types');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('consumers');
    }
};
