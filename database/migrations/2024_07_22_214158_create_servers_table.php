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
        Schema::create('servers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->comment('サーバーのオーナーのID');

            $table->string('types')->nullable()->default('')->comment('サーバの対象プラットフォーム');
            $table->string('address')->comment('サーバのIP');
            $table->integer('port', false, true)->comment('サーバーのポート');
            $table->text('description')->nullable()->comment('サーバの説明');

            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
