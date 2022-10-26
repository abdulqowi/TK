<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->string('mother');   
            $table->string('mother_email');
            $table->string('student_name');
            $table->string('mother_phone');
            $table->string('birthplace');
            $table->string('birthday');
            $table->string('father_degree');
            $table->string('mother_degree');
            $table->string('father_job');
            $table->string('mother_job');
            $table->string('address');
            $table->enum('gender', ['L', 'P']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate(DB::raw('NO ACTION'))
                ->onDelete(DB::raw('NO ACTION'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
