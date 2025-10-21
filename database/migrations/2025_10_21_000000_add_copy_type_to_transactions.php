<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // drop FK to book_copies if exists (names may vary)
            try {
                $table->dropForeign(['copy_id']);
            } catch (\Exception $e) {
                // ignore if not present
            }

            $table->string('copy_type')->nullable()->after('copy_id');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('copy_type');
            // Note: FK re-adding is intentionally omitted.
        });
    }
};
