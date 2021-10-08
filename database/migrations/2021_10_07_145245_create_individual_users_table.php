<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('individual_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment("Represents the id on the users table.");
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('cpf', 20)->unique()->comment("This user`s CPF, unique to this table.");
            $table->string('rg', 20)->unique()->nullable()->comment("This user`s RG, unique to this table.");
            $table->date('date_of_birthday')->comment("This user`s date of birthday.");
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
        Schema::dropIfExists('individual_users');
    }
}
