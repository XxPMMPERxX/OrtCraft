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

            $table->string('name')->comment('サーバ名');
            $table->string('address')->comment('サーバのIP');
            $table->integer('je_port', unsigned: true)->comment('サーバ(JE)のポート');
            $table->integer('be_port', unsigned: true)->comment('サーバ(BE)のポート');
            $table->text('description')->nullable()->comment('サーバの説明');
            $table->string('platform')->nullable()->default('')->comment('サーバの対象プラットフォーム');
            $table->string('tags')->nullable()->default('')->comment('サーバのタグ');
            $table->smallInteger('is_verified')->default(0)->comment('サーバが認証済みかどうか');

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
