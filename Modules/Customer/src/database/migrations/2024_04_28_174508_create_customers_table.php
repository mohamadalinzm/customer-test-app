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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('dateOfBirth');
            $table->string('phoneNumber');
            $table->string('email')->unique();
            $table->string('bankAccountNumber');
            $table->timestamps();

            $table->unique(['firstname', 'lastname', 'dateOfBirth']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
