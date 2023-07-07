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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('invite');
            $table->integer('status');
            $table->string('user_order');
        });

        Schema::create('players', function (Blueprint $table) {
            $table->string('id');
            $table->boolean('me_ready');
            $table->boolean('my_turn');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('player_id');
            $table->timestamp('time');
            $table->text('message');
        });

        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('ship_in_sea', function (Blueprint $table) {
            $table->string('player_id');
            $table->unsignedBigInteger('ship_id');
            $table->string('orientation');
            $table->integer('x_coord');
            $table->integer('y_coord');

            $table->foreign('ship_id')
                    ->references('id')
                    ->on('ships')
                    ->onDelete('cascade');

            $table->foreign('player_id')
                    ->references('id')
                    ->on('player')
                    ->onDelete('cascade');
        });

        Schema::create('shots', function (Blueprint $table) {
            $table->string('player_id');
            $table->integer('x_coord');
            $table->integer('y_coord');

            $table->foreign('player_id')->references('id')->on('player')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('players');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('ships');
        Schema::dropIfExists('ships_in_sea');
        Schema::dropIfExists('shots');
    }
};
