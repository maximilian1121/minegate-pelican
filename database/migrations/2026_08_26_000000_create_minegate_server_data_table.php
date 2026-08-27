<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('minegate_server_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')
                ->constrained('servers')
                ->cascadeOnDelete();
            $table->string('subdomain')->nullable();
            $table->timestamps();

            $table->unique('server_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minegate_server_data');
    }
};