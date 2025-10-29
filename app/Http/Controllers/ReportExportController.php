<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Thesis;
use App\Models\Penalty;
use App\Models\BookCopy;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportExportController extends Controller
{
    public function exportUser($query)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'University ID');
        $sheet->setCellValue('C1', 'First Name');
        $sheet->setCellValue('D1', 'Last Name');
        $sheet->setCellValue('E1', 'Username');
        $sheet->setCellValue('F1', 'Email');
        $sheet->setCellValue('G1', 'Role');
        $sheet->setCellValue('H1', 'User Type');
        $sheet->setCellValue('I1', 'Account Status');
        $sheet->setCellValue('J1', 'Phone Number');
        $sheet->setCellValue('K1', 'Registration Date');

        // Query based on parameter
        switch($query) {
            case 'user':
                $users = User::where('role', 'user')->get();
                break;
            case 'librarian':
                $users = User::where('role', 'librarian')->get();
                break;
            case 'admin':
                $users = User::where('role', 'admin')->get();
                break;
            case 'super-admin':
                $users = User::where('role', 'super-admin')->get();
                break;
            case 'active':
                $users = User::where('account_status', 'Active')->get();
                break;
            case 'inactive':
                $users = User::where('account_status', 'Inactive')->get();
                break;
            case 'student':
                $users = User::where('user_type', 'student')->get();
                break;
            case 'employee':
                $users = User::where('user_type', 'employee')->get();
                break;
            default:
                $users = User::all();
        }

        if($users->isEmpty()) {
            return redirect()->back()->with('error', 'No users found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach($users as $user) {
            $sheet->setCellValue('A'.$row, $user->id);
            $sheet->setCellValue('B'.$row, $user->university_id);
            $sheet->setCellValue('C'.$row, $user->first_name);
            $sheet->setCellValue('D'.$row, $user->last_name);
            $sheet->setCellValue('E'.$row, $user->username);
            $sheet->setCellValue('F'.$row, $user->email);
            $sheet->setCellValue('G'.$row, $user->role);
            $sheet->setCellValue('H'.$row, $user->user_type);
            $sheet->setCellValue('I'.$row, $user->account_status);
            $sheet->setCellValue('J'.$row, $user->phone_number);
            $sheet->setCellValue('K'.$row, $user->registration_date);
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'users.xlsx');
    }

    public function exportBook(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Year Published');
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Author(s)');
        $sheet->setCellValue('G1', 'Created At');

        // Build query with filters
        $query = Book::with(['authors', 'category']);

        if ($request->has('year_published') && $request->year_published) {
            $query->where('year_published', $request->year_published);
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('author') && $request->author) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('first_name', 'LIKE', '%' . $request->author . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->author . '%');
            });
        }

        $books = $query->get();

        if ($books->isEmpty()) {
            return redirect()->back()->with('error', 'No books found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach ($books as $book) {
            $authorNames = $book->authors->map(function($author) {
                return $author->first_name . ' ' . $author->last_name;
            })->join(', ');

            $sheet->setCellValue('A'.$row, $book->id);
            $sheet->setCellValue('B'.$row, $book->title);
            $sheet->setCellValue('C'.$row, $book->description);
            $sheet->setCellValue('D'.$row, $book->year_published);
            $sheet->setCellValue('E'.$row, $book->category->category_name ?? 'N/A');
            $sheet->setCellValue('F'.$row, $authorNames);
            $sheet->setCellValue('G'.$row, $book->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'books.xlsx');
    }

    public function exportThesis(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Abstract');
        $sheet->setCellValue('D1', 'Year Published');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Author(s)');
        $sheet->setCellValue('G1', 'Created At');

        // Build query
        $query = Thesis::with('authors');

        if ($request->has('year_published') && $request->year_published) {
            $query->where('year_published', $request->year_published);
        }

        if ($request->has('department') && $request->department) {
            if (is_array($request->department)) {
                $query->whereIn('department', $request->department);
            } else {
                $query->where('department', $request->department);
            }
        }

        $theses = $query->get();

        if ($theses->isEmpty()) {
            return redirect()->back()->with('error', 'No theses found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach ($theses as $thesis) {
            $authorNames = $thesis->authors->map(function($author) {
                return $author->first_name . ' ' . $author->last_name;
            })->join(', ');

            $sheet->setCellValue('A'.$row, $thesis->id);
            $sheet->setCellValue('B'.$row, $thesis->title);
            $sheet->setCellValue('C'.$row, $thesis->abstract);
            $sheet->setCellValue('D'.$row, $thesis->year_published);
            $sheet->setCellValue('E'.$row, $thesis->department);
            $sheet->setCellValue('F'.$row, $authorNames);
            $sheet->setCellValue('G'.$row, $thesis->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'theses.xlsx');
    }

    public function exportTransaction($query)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Transaction ID');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'University ID');
        $sheet->setCellValue('D1', 'Item Type');
        $sheet->setCellValue('E1', 'Item Title');
        $sheet->setCellValue('F1', 'Borrow Date');
        $sheet->setCellValue('G1', 'Due Date');
        $sheet->setCellValue('H1', 'Return Date');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Days Overdue');
        $sheet->setCellValue('K1', 'Created At');

        // Query based on parameter with polymorphic relationships
        $transactionQuery = Transaction::with(['user', 'borrowable' => function ($morphTo) {
            $morphTo->morphWith([
                BookCopy::class => ['book'],
                ThesisCopy::class => ['thesis'],
            ]);
        }]);

        switch($query) {
            case 'overdue':
                $transactions = $transactionQuery->overdue()->get();
                break;
            case 'borrowed':
                $transactions = $transactionQuery->borrowed()->get();
                break;
            case 'returned':
                $transactions = $transactionQuery->returned()->get();
                break;
            case 'requested':
                $transactions = $transactionQuery->requested()->get();
                break;
            case 'active':
                $transactions = $transactionQuery->whereIn('transaction_status', ['borrowed', 'requested'])->get();
                break;
            default:
                $transactions = $transactionQuery->get();
        }

        if($transactions->isEmpty()) {
            return redirect()->route('transactions.index')->with('error', 'No transactions found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach($transactions as $transaction) {
            $itemType = 'Unknown';
            $itemTitle = 'Unknown';
            
            if ($transaction->borrowable_type === 'App\Models\BookCopy' && $transaction->borrowable && $transaction->borrowable->book) {
                $itemType = 'Book';
                $itemTitle = $transaction->borrowable->book->title;
            } elseif ($transaction->borrowable_type === 'App\Models\ThesisCopy' && $transaction->borrowable && $transaction->borrowable->thesis) {
                $itemType = 'Thesis';
                $itemTitle = $transaction->borrowable->thesis->title;
            }

            // Calculate overdue days
            $overdueDays = 0;
            if ($transaction->transaction_status === 'overdue' || 
                ($transaction->transaction_status === 'borrowed' && now()->gt($transaction->due_date))) {
                (int) $overdueDays = now()->diffInDays($transaction->due_date);
            }

            $sheet->setCellValue('A'.$row, $transaction->id);
            $sheet->setCellValue('B'.$row, $transaction->user->first_name . ' ' . $transaction->user->last_name);
            $sheet->setCellValue('C'.$row, $transaction->user->university_id);
            $sheet->setCellValue('D'.$row, $itemType);
            $sheet->setCellValue('E'.$row, $itemTitle);
            $sheet->setCellValue('F'.$row, $transaction->borrow_date->format('Y-m-d H:i:s'));
            $sheet->setCellValue('G'.$row, $transaction->due_date->format('Y-m-d H:i:s'));
            $sheet->setCellValue('H'.$row, $transaction->return_date ? $transaction->return_date->format('Y-m-d H:i:s') : 'Not Returned');
            $sheet->setCellValue('I'.$row, ucfirst($transaction->transaction_status));
            $sheet->setCellValue('J'.$row, $overdueDays);
            $sheet->setCellValue('K'.$row, $transaction->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'transactions.xlsx');
    }

    public function exportPenalty($query)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Penalty ID');
        $sheet->setCellValue('B1', 'Transaction ID');
        $sheet->setCellValue('C1', 'User');
        $sheet->setCellValue('D1', 'University ID');
        $sheet->setCellValue('E1', 'Amount');
        $sheet->setCellValue('F1', 'Reason');
        $sheet->setCellValue('G1', 'Created At');
        $sheet->setCellValue('H1', 'Updated At');

        // Query based on parameter
        $penaltyQuery = Penalty::with(['user', 'transaction']);

        switch($query) {
            case 'unpaid':
                // Since we don't have a status field, we'll assume all penalties are unpaid
                // You might want to add a 'paid' field to your penalties table
                $penalties = $penaltyQuery->get();
                break;
            case 'recent':
                $penalties = $penaltyQuery->orderBy('created_at', 'desc')->get();
                break;
            case 'high-value':
                $penalties = $penaltyQuery->orderBy('amount', 'desc')->get();
                break;
            default:
                $penalties = $penaltyQuery->get();
        }

        if($penalties->isEmpty()) {
            return redirect()->back()->with('error', 'No penalties found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach($penalties as $penalty) {
            $sheet->setCellValue('A'.$row, $penalty->id);
            $sheet->setCellValue('B'.$row, $penalty->transaction_id);
            $sheet->setCellValue('C'.$row, $penalty->user->first_name . ' ' . $penalty->user->last_name);
            $sheet->setCellValue('D'.$row, $penalty->user->university_id);
            $sheet->setCellValue('E'.$row, '₱' . number_format($penalty->amount, 2));
            $sheet->setCellValue('F'.$row, $penalty->reason);
            $sheet->setCellValue('G'.$row, $penalty->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue('H'.$row, $penalty->updated_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'penalties.xlsx');
    }

    public function exportAuthor($query)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Author ID');
        $sheet->setCellValue('B1', 'First Name');
        $sheet->setCellValue('C1', 'Last Name');
        $sheet->setCellValue('D1', 'Middle Name');
        $sheet->setCellValue('E1', 'Appellation');
        $sheet->setCellValue('F1', 'Extension');
        $sheet->setCellValue('G1', 'Total Books');
        $sheet->setCellValue('H1', 'Total Theses');
        $sheet->setCellValue('I1', 'Created At');

        // Query based on parameter
        $authorQuery = Author::withCount(['books', 'theses']);

        switch($query) {
            case 'books':
                $authors = $authorQuery->has('books')->get();
                break;
            case 'theses':
                $authors = $authorQuery->has('theses')->get();
                break;
            case 'prolific':
                $authors = $authorQuery->orderBy('books_count', 'desc')->get();
                break;
            default:
                $authors = $authorQuery->get();
        }

        if($authors->isEmpty()) {
            return redirect()->back()->with('error', 'No authors found for the specified criteria.');
        }

        // Data rows
        $row = 2;
        foreach($authors as $author) {
            $sheet->setCellValue('A'.$row, $author->id);
            $sheet->setCellValue('B'.$row, $author->first_name);
            $sheet->setCellValue('C'.$row, $author->last_name);
            $sheet->setCellValue('D'.$row, $author->middle_name ?? 'N/A');
            $sheet->setCellValue('E'.$row, $author->appellation ?? 'N/A');
            $sheet->setCellValue('F'.$row, $author->extension ?? 'N/A');
            $sheet->setCellValue('G'.$row, $author->books_count);
            $sheet->setCellValue('H'.$row, $author->theses_count);
            $sheet->setCellValue('I'.$row, $author->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach(range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'authors.xlsx');
    }

    /**
     * Helper method to download spreadsheet
     */
    private function downloadSpreadsheet($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response($excelOutput, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}