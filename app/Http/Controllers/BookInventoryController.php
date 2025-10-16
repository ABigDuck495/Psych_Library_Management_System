<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookInventoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();

        return view('books.addInventory', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        // Get the data and ensure authors are strings
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'year_published' => 'required|digits:4|integer',
            'category_id'    => 'required|exists:categories,id',
            'authors'        => 'required|array',
            'copies'         => 'required|integer|min:1',
        ]);
        
        // Convert all author values to strings
        $validated['authors'] = array_map(function($author) {
            return (string) $author;
        }, $request->authors);

        DB::transaction(function () use ($validated) {
            // 1️⃣ Create Book
            $book = Book::create([
                'title'          => $validated['title'],
                'description'    => $validated['description'] ?? null,
                'year_published' => $validated['year_published'],
                'category_id'    => $validated['category_id'],
            ]);

            // 2️⃣ Handle Authors (check if they exist or create)
            $authorIds = [];
            foreach ($validated['authors'] as $authorName) {
                $nameParts = array_values(array_filter(explode(' ', trim($authorName))));
                
                // Ensure we have at least a first and last name
                if (count($nameParts) >= 2) {
                    $lastName = array_pop($nameParts); // Get last word as lastName
                    $firstName = implode(' ', $nameParts); // Rest is firstName
                    
                    if (!empty($firstName) && !empty($lastName)) {
                        $author = Author::firstOrCreate([
                            'first_name' => $firstName,
                            'last_name'  => $lastName
                        ]);
                        $authorIds[] = $author->id;
                    }
                }
            }
            
            // Only sync if we have valid authors
            if (!empty($authorIds)) {
                $book->authors()->sync($authorIds);
            } else {
                throw new \Exception('No valid authors provided. Each author must have at least a first and last name.');
            }

            // 3️⃣ Create Copies
            for ($i = 0; $i < $validated['copies']; $i++) {
                BookCopy::create([
                    'book_id'      => $book->id,
                    'is_available' => true,
                ]);
            }
        });

        return redirect()->route('books.index')
                         ->with('success', 'Book inventory added successfully!');
    }
}
