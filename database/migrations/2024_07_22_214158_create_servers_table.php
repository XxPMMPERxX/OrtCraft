<?php

use App\Models\ServerIdentity;
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
            $table->text('description')->nullable()->comment('サーバの説明');
            $table->string('tags')->nullable()->default('')->comment('サーバのタグ');
            $table->dateTime('verified_at')->nullable()->comment('サーバが認証された日時');
            $table->string('auth_code')->nullable()->comment('認証コード');

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
