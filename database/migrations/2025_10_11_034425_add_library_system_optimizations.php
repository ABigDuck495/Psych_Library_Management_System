<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['return_date', 'transaction_status']);
        });

        // Create triggers for automatic updates
        if (config('database.default') === 'mysql') {
            DB::unprepared('
                DROP TRIGGER IF EXISTS update_book_availability_on_borrow;
                CREATE TRIGGER update_book_availability_on_borrow 
                AFTER INSERT ON transactions 
                FOR EACH ROW 
                BEGIN
                    IF NEW.transaction_status = "borrowed" THEN
                        UPDATE book_copies SET is_available = FALSE WHERE copy_id = NEW.copy_id;
                    END IF;
                END
            ');

            DB::unprepared('
                DROP TRIGGER IF EXISTS update_book_availability_on_return;
                CREATE TRIGGER update_book_availability_on_return 
                AFTER UPDATE ON transactions 
                FOR EACH ROW 
                BEGIN
                    IF NEW.transaction_status = "returned" AND OLD.transaction_status != "returned" THEN
                        UPDATE book_copies SET is_available = TRUE WHERE copy_id = NEW.copy_id;
                    END IF;
                END
            ');

            DB::unprepared('
                DROP TRIGGER IF EXISTS auto_update_overdue_status;
                CREATE TRIGGER auto_update_overdue_status 
                BEFORE UPDATE ON transactions 
                FOR EACH ROW 
                BEGIN
                    IF NEW.return_date IS NULL AND NEW.due_date < NOW() AND NEW.transaction_status != "returned" THEN
                        SET NEW.transaction_status = "overdue";
                    END IF;
                END
            ');
            // Create a MySQL event to update overdue transactions every midnight
            DB::unprepared('
                DROP EVENT IF EXISTS update_overdue_transactions_daily;
            ');
            // DB::unprepared('
            //     CREATE EVENT update_overdue_transactions_daily
            //     ON SCHEDULE EVERY 1 DAY
            //     STARTS CURRENT_DATE + INTERVAL 1 DAY
            //     DO
            //       UPDATE transactions
            //       SET transaction_status = "overdue"
            //       WHERE return_date IS NULL
            //         AND due_date < NOW()
            //         AND transaction_status != "returned"
            //         AND transaction_status != "overdue";
            // ');
        }
    }

    public function down()
    {
        // Remove additional indexes
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['isbn']);
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropIndex(['advisor']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['return_date', 'transaction_status']);
        });

        // Drop triggers
        if (config('database.default') === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_book_availability_on_borrow');
            DB::unprepared('DROP TRIGGER IF EXISTS update_book_availability_on_return');
            DB::unprepared('DROP TRIGGER IF EXISTS auto_update_overdue_status');
        }
    }
};