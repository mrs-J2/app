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
    Schema::create('logs', function (Blueprint $table) {
        $table->id();
        $table->string('action'); // Like 'login' or 'delete_user'
        $table->unsignedBigInteger('user_id')->nullable(); // Who did it
        $table->dateTime('date');
        $table->string('ip')->nullable(); // Their computer address
        $table->string('resource')->nullable(); // Like 'user/3' or 'course/5'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
