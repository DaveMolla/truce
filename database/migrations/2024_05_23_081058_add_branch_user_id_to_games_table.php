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
        Schema::table('games', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_user_id')->after('id');
            $table->foreign('branch_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['branch_user_id']);
            $table->dropColumn('branch_user_id');
        });
    }
};
