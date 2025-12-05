<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('leads', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('phone');
        $table->string('email');
        $table->text('comment')->nullable();

        $table->date('schedule_date')->nullable();
        $table->string('schedule_time')->nullable();

        $table->string('type'); // lead OR schedule

        $table->timestamps();
    });
}
    

};
