<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Thesis;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $theses = Thesis::with('authors')->paginate(10);
        return view('theses.index', compact('theses'));
    }

    public function userInterface()
    {
        $theses = Thesis::with('authors')->get();
        $books = collect();
        return view('userInterface.index', compact('theses', 'books'));
    }

    public function create()
    {
        $departments = ['AB Psychology', 'BS Psychology'];
        $authors = Author::all();
        return view('theses.create', compact('departments', 'authors'));
    }

    public function store(Request $request)
    {
        // dd($request->all(), $request->file('pdf_file'));
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'department' => 'required|string',
            'authors' => 'sometimes|array|min:1',
            'authors.*.first_name' => 'required_with:authors|string|max:255',
            'authors.*.last_name' => 'required_with:authors|string|max:255',
            'authors.*.middle_name' => 'nullable|string|max:255',
            'authors.*.appellation' => 'nullable|string|max:255',
            'authors.*.extension' => 'nullable|string|max:255',
            'copies_count' => 'sometimes|integer|min:1',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240' // 10MB max, PDF only
        ]);

        DB::beginTransaction();
        try {
            $copiesCount = $validated['copies_count'] ?? 1;
            $authorsData = $validated['authors'] ?? [];

            // Remove non-thesis fields
            $thesisData = collect($validated)->except(['copies_count', 'authors', 'pdf_file'])->toArray();

            // Handle PDF file upload - FIXED: Use 'thesis' disk consistently
            if ($request->hasFile('pdf_file')) {
                $pdfFile = $request->file('pdf_file');
                
                // Validate file type (additional check)
                if ($pdfFile->getClientOriginalExtension() !== 'pdf') {
                    throw new \Exception('The file must be a PDF.');
                }

                // Validate file size (additional check)
                if ($pdfFile->getSize() > 10 * 1024 * 1024) { // 10MB in bytes
                    throw new \Exception('The PDF file must be less than 10MB.');
                }

                // Generate unique filename
                $filename = 'thesis_' . time() . '_' . uniqid() . '.pdf';
                
                // Store the file in theses_pdfs directory using 'thesis' disk
                $pdfPath = $pdfFile->storeAs('', $filename, 'thesis');
                
                if (!$pdfPath) {
                    throw new \Exception('Failed to store the PDF file.');
                }

                // Add PDF path to thesis data
                $thesisData['thesis_pdf'] = $pdfPath;
            }

            // Create the thesis
            $thesis = Thesis::create($thesisData);

            // Create or attach authors
            foreach ($authorsData as $authorData) {
                $author = Author::firstOrCreate(
                    [
                        'first_name' => $authorData['first_name'],
                        'last_name' => $authorData['last_name'],
                        'extension' => $authorData['extension'] ?? null,
                    ],
                    [
                        'middle_name' => $authorData['middle_name'] ?? null,
                        'appellation' => $authorData['appellation'] ?? null,
                    ]
                );

                $thesis->authors()->attach($author->id);
            }

            // Create thesis copies
            for ($i = 0; $i < $copiesCount; $i++) {
                ThesisCopy::create([
                    'thesis_id' => $thesis->id,
                    'is_available' => true
                ]);
            }

            DB::commit();
            return redirect()->route('theses.index')->with('success', 'Thesis added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if transaction fails - FIXED: Use 'thesis' disk
            if (isset($pdfPath)) {
                Storage::disk('thesis')->delete($pdfPath);
            }

            return redirect()->back()->withErrors('Failed to create thesis: ' . $e->getMessage())->withInput();
        }
    }


    public function show(Thesis $thesis)
    {
        $thesis->load('authors', 'copies');
        $activeTransaction = $thesis->transactions()
            ->where('user_id', Auth::id())
            ->where('transaction_status', 'requested')
            ->latest()
            ->first();

        return view('theses.show', compact('thesis', 'activeTransaction'));
    }

    public function edit(Thesis $thesis)
    {
        $departments = ['AB Psychology', 'BS Psychology'];
        $authors = Author::all();
        $copies_count = $thesis->copies()->count();
        return view('theses.update', compact('thesis', 'departments', 'authors', 'copies_count'));
    }

     public function update(Request $request, Thesis $thesis)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'department' => 'required|string',
            'authors' => 'sometimes|array|min:1',
            'authors.*.first_name' => 'required_with:authors|string|max:255',
            'authors.*.last_name' => 'required_with:authors|string|max:255',
            'authors.*.middle_name' => 'nullable|string|max:255',
            'authors.*.appellation' => 'nullable|string|max:255',
            'authors.*.extension' => 'nullable|string|max:255',
            'copies_count' => 'sometimes|integer|min:1',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240' // 10MB max, PDF only
        ]);

        DB::transaction(function () use ($validated, $thesis, $request) {
            $oldPdfPath = $thesis->thesis_pdf;

            // Update thesis data
            $thesisData = collect($validated)->except(['copies_count', 'authors', 'pdf_file'])->toArray();

            // Handle PDF file upload - FIXED: Use 'thesis' disk consistently
            if ($request->hasFile('pdf_file')) {
                $pdfFile = $request->file('pdf_file');
                
                // // Validate file type (additional check)
                // if ($pdfFile->getClientOriginalExtension() !== 'pdf') {
                //     throw new \Exception('The file must be a PDF.');
                // }

                // // Validate file size (additional check)
                // if ($pdfFile->getSize() > 10 * 1024 * 1024) {
                //     throw new \Exception('The PDF file must be less than 10MB.');
                // }

                // Generate unique filename
                $filename = 'thesis_' . time() . '_' . uniqid() . '.pdf';
                
                // Store the file using 'thesis' disk (no subdirectory)
                $pdfPath = $pdfFile->storeAs('', $filename, 'thesis');
                
                if (!$pdfPath) {
                    throw new \Exception('Failed to store the PDF file.');
                }

                // Add PDF path to thesis data
                $thesisData['thesis_pdf'] = $pdfPath;
            }

            // Update thesis
            $thesis->update($thesisData);

            // Update authors (detach old, attach new)
            if (!empty($validated['authors'])) {
                $authorIds = [];

                foreach ($validated['authors'] as $authorData) {
                    $author = Author::firstOrCreate(
                        [
                            'first_name' => $authorData['first_name'],
                            'last_name' => $authorData['last_name'],
                            'extension' => $authorData['extension'] ?? null,
                        ],
                        [
                            'middle_name' => $authorData['middle_name'] ?? null,
                            'appellation' => $authorData['appellation'] ?? null,
                        ]
                    );
                    $authorIds[] = $author->id;
                }

                $thesis->authors()->sync($authorIds);
            }

            // Update copies
            if ($request->filled('copies_count')) {
                $desired = (int)$validated['copies_count'];
                $existing = $thesis->copies()->count();

                if ($desired > $existing) {
                    for ($i = 0; $i < ($desired - $existing); $i++) {
                        ThesisCopy::create([
                            'thesis_id' => $thesis->id,
                            'is_available' => true
                        ]);
                    }
                } elseif ($desired < $existing) {
                    $availableCopies = $thesis->copies()
                        ->where('is_available', true)
                        ->whereDoesntHave('transactions', function ($query) {
                            $query->whereIn('transaction_status', ['requested', 'approved', 'borrowed']);
                        })
                        ->limit($existing - $desired)
                        ->get();

                    foreach ($availableCopies as $copy) {
                        $copy->delete();
                    }
                }
            }

            // Delete old PDF if a new one was uploaded - FIXED: Use 'thesis' disk
            if ($request->hasFile('pdf_file') && $oldPdfPath) {
                Storage::disk('thesis')->delete($oldPdfPath);
            }
        });

        return redirect()->route('theses.index')->with('success', 'Thesis updated successfully.');
    }
    public function destroy(Thesis $thesis)
    {
        DB::transaction(function () use ($thesis) {
            // Delete copies first
            $thesis->copies()->delete();
            // Detach authors
            $thesis->authors()->detach();
            // Delete thesis
            $thesis->delete();
        });

        return redirect()->route('theses.index')->with('success', 'Thesis deleted successfully.');
    }

    public function availableCopies($thesisId)
    {
        return ThesisCopy::where('thesis_id', $thesisId)
            ->where('is_available', true)
            ->count();
    }
    public function downloadPdf(Thesis $thesis)
    {
        // FIXED: Use 'thesis' disk instead of 'public'
        if (!$thesis->thesis_pdf || !Storage::disk('thesis')->exists($thesis->thesis_pdf)) {
            return redirect()->back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('thesis')->download($thesis->thesis_pdf, $thesis->title . '.pdf');
    }

    public function viewPdf(Thesis $thesis)
    {
        // FIXED: Use 'thesis' disk instead of 'public'
        if (!$thesis->thesis_pdf || !Storage::disk('thesis')->exists($thesis->thesis_pdf)) {
            return redirect()->back()->with('error', 'PDF file not found.');
        }

        $filePath = Storage::disk('thesis')->path($thesis->thesis_pdf);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}