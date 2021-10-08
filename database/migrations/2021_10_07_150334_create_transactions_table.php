<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_payer_id')->comment('Represents the wallet of the user sending the money.');
            $table->foreign('wallet_payer_id')->references('id')->on('wallets');
            $table->unsignedBigInteger('wallet_payee_id')->comment('Represents the wallet of the user receiving the money.');
            $table->foreign('wallet_payee_id')->references('id')->on('wallets');
            $table->decimal('ammount', 10)->comment('Represents the ammount transfered between two wallets.');
            $table->tinyInteger('status')->comment('Represents the current status of the transaction.');
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
        Schema::dropIfExists('transactions');
    }
}
