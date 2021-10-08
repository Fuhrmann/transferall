<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Represents the id of this company on the users table.');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('cnpj', 20)->unique()->comment('Represents the company`s CNPJ, unique to this table.');
            $table->string('trading_name')->comment('Represents the company`s trading name.');
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
        Schema::dropIfExists('company_users');
    }
}
