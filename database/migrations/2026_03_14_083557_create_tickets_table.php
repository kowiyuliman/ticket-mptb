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
    Schema::create('tickets', function (Blueprint $table) {

        $table->id();

        $table->string('ticket_code')->unique();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('nama');

        $table->string('nomor_meja')->nullable();

        $table->string('nomor_ruangan')->nullable();

        $table->string('ip_address')->nullable();

        $table->enum('kategori',[
            'hardware',
            'software',
            'network'
        ]);

        $table->text('deskripsi');

        $table->string('screenshot')->nullable();

        $table->enum('status',[
            'open',
            'pending',
            'on_progress',
            'closed',
            'cancelled'
        ])->default('open');

        $table->foreignId('assigned_to')->nullable();

        $table->timestamps();

        $table->index('status');
        $table->index('assigned_to');
        $table->index('created_at');

    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
