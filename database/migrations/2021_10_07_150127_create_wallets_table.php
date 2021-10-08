<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id')->comment('Represents the owner of this wallet, foreign key with the users table, column id.');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->decimal('ammount', 10)->comment('Represents the ammount of money available on this wallet.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('wallets');
    }
}
