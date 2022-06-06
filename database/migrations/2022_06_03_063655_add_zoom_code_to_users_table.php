<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoomCodeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('zoom_code')->nullable()->default(null);
            $table->longText('zoom_access_token')->nullable()->default(null);
            $table->longText('zoom_refresh_token')->nullable()->default(null);
            $table->timestamp('zoom_expires_in', 0)->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('zoom_code')->nullable()->default(null);
            $table->dropColumn('zoom_access_token')->nullable()->default(null);
            $table->dropColumn('zoom_refresh_token')->nullable()->default(null);
            $table->dropColumn('zoom_expires_in', 0)->nullable()->default(null);
        });
    }
}
