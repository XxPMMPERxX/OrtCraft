<?php

use App\Models\Server;
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
        Schema::create('server_identities', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Server::class)->constrained()->cascadeOnDelete();

            $table->string('address')->comment('サーバのIP');
            $table->integer('je_port', unsigned: true)->nullable()->comment('サーバ(JE)のポート');
            $table->integer('be_port', unsigned: true)->nullable()->comment('サーバ(BE)のポート');

            $table->string('auth_code')->nullable()->comment('認証コード');
            $table->smallInteger('is_verify')->default(0)->comment('認証されたかどうか');
            $table->dateTime('activated_at')->nullable()->comment('有効化日時');

            $table->unique(['server_id', 'address', 'je_port']);
            $table->unique(['server_id', 'address', 'be_port']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_identities');
    }
};
