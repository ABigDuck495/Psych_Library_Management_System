<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Thesis, Category, BookCopy};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /** Home Catalogue Page */
   public function catalogue()
    {
        return view('catalouge.catalogue');
    }

    public function addInventory()
    {
        return view('inventory.add');
    }

    /** Book form */
    public function createBook()
    {
        $categories = Category::whereNotIn('name', ['Thesis', 'Research Paper', 'Case Study', 'Narrative Report'])->get();
        return view('books.createBook', compact('categories'));
    }

    /** Thesis (academic paper) form */
    public function createThesis()
    {
        $categories = ['Thesis', 'Research Paper', 'Case Study', 'Narrative Report'];
        return view('books.createThesis', compact('categories'));
    }

    /** Store Book */
    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year_published' => 'nullable|integer',
            'authors' => 'required|array|min:1',
            'authors.*.first_name' => 'required|string|max:100',
            'authors.*.last_name' => 'required|string|max:100',
            'category_id' => 'required|integer|exists:categories,id',
            'copies' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($validated) {
            $book = Book::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'year_published' => $validated['year_published'] ?? null,
                'category_id' => $validated['category_id'],
            ]);

            // create authors and attach pivot
            foreach ($validated['authors'] as $authorData) {
                $author = Author::firstOrCreate([
                    'first_name' => $authorData['first_name'],
                    'last_name' => $authorData['last_name'],
                ]);
                $book->authors()->attach($author->id);
            }

            // create book copies
            for ($i = 0; $i < $validated['copies']; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'status' => 'available',
                ]);
            }
        });

        return redirect()->route('catalogue')->with('success', 'Book added successfully!');
    }

    /** Store Thesis (academic paper) */
    public function storeThesis(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year_published' => 'required|integer',
            'advisor' => 'required|string|max:255',
            'authors' => 'required|array|min:1',
            'authors.*.first_name' => 'required|string|max:100',
            'authors.*.last_name' => 'required|string|max:100',
            'category' => 'required|string|in:Thesis,Research Paper,Case Study,Narrative Report'
        ]);

        DB::transaction(function () use ($validated) {
            $thesis = Thesis::create([
                'title' => $validated['title'],
                'abstract' => $validated['abstract'],
                'year_published' => $validated['year_published'],
                'advisor' => $validated['advisor'],
            ]);

            foreach ($validated['authors'] as $authorData) {
                $author = Author::firstOrCreate([
                    'first_name' => $authorData['first_name'],
                    'last_name' => $authorData['last_name'],
                ]);
                $thesis->authors()->attach($author->id);
            }
        });

        return redirect()->route('catalogue')->with('success', 'Academic paper added successfully!');
    }
}
