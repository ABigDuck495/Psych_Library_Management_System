<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if index exists before creating
        $indexExists = DB::select("
            SELECT COUNT(1) AS count
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE table_schema = DATABASE()
              AND table_name = 'transactions'
              AND index_name = 'transactions_return_date_transaction_status_index'
        ")[0]->count;

        if (!$indexExists) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index(['return_date', 'transaction_status']);
            });
        }

        if (config('database.default') === 'mysql') {
            // Triggers
            $triggers = [
                'update_book_availability_on_borrow',
                'update_book_availability_on_return',
                'auto_update_overdue_status'
            ];

            foreach ($triggers as $trigger) {
                DB::unprepared("DROP TRIGGER IF EXISTS {$trigger};");
            }

            DB::unprepared("
                CREATE TRIGGER update_book_availability_on_borrow
                AFTER INSERT ON transactions
                FOR EACH ROW
                BEGIN
                    IF NEW.transaction_status = 'borrowed' THEN
                        IF NEW.borrowable_type = 'App\\\\Models\\\\BookCopy' THEN
                            UPDATE book_copies
                            SET is_available = FALSE
                            WHERE copy_id = NEW.borrowable_id;
                        ELSEIF NEW.borrowable_type = 'App\\\\Models\\\\ThesisCopy' THEN
                            UPDATE thesis_copies
                            SET is_available = FALSE
                            WHERE copy_id = NEW.borrowable_id;
                        END IF;
                    END IF;
                END;
            ");

            DB::unprepared("
                CREATE TRIGGER update_book_availability_on_return
                AFTER UPDATE ON transactions
                FOR EACH ROW
                BEGIN
                    IF NEW.transaction_status = 'returned' AND OLD.transaction_status != 'returned' THEN
                        IF NEW.borrowable_type = 'App\\\\Models\\\\BookCopy' THEN
                            UPDATE book_copies
                            SET is_available = TRUE
                            WHERE id = NEW.borrowable_id;
                        ELSEIF NEW.borrowable_type = 'App\\\\Models\\\\ThesisCopy' THEN
                            UPDATE thesis_copies
                            SET is_available = TRUE
                            WHERE id = NEW.borrowable_id;
                        END IF;
                    END IF;
                END;
            ");

            DB::unprepared("
                CREATE TRIGGER auto_update_overdue_status
                BEFORE UPDATE ON transactions
                FOR EACH ROW
                BEGIN
                    IF NEW.return_date IS NULL AND NEW.due_date < NOW() AND NEW.transaction_status != 'returned' THEN
                        SET NEW.transaction_status = 'overdue';
                    END IF;
                END;
            ");

            // Drop event if exists
            DB::unprepared("DROP EVENT IF EXISTS update_overdue_transactions_daily;");
        }
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['return_date', 'transaction_status']);
        });

        if (config('database.default') === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_book_availability_on_borrow;');
            DB::unprepared('DROP TRIGGER IF EXISTS update_book_availability_on_return;');
            DB::unprepared('DROP TRIGGER IF EXISTS auto_update_overdue_status;');
            DB::unprepared('DROP EVENT IF EXISTS update_overdue_transactions_daily;');
        }
  
    }
};