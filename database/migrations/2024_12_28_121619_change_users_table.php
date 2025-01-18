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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('name');
            $table->integer('level')->default(0)->after('role');
            $table->integer('experience')->default(0)->after('level');
            $table->integer('coins')->default(0)->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('experience');
            $table->dropColumn('coins');
        });
    }
};
