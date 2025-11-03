<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\BookCopy;
use Carbon\Carbon;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create some categories
        $categories = [
            'General Psychology',
            'Clinical Psychology',
            'Cognitive Psychology',
            'Developmental Psychology',
            'Social Psychology',
            'Abnormal Psychology',
            'Research Methods',
            'Biological Psychology',
            'Personality Psychology',
            'Industrial-Organizational Psychology'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['category_name' => $categoryName],
                [
                    'description' => "Books related to {$categoryName}",
                    'created_at' => now(),
                    ]
            );
        }

        $booksData = [
            [
                'title' => 'Introduction to Psychology',
                'description' => 'A comprehensive introduction to the field of psychology covering basic concepts, theories, and research methods.',
                'year_published' => 2020,
                'category_name' => 'General Psychology',
                'authors' => ['James W. Kalat'],
                'created_at' => '2025-10-28 09:00:00'
            ],
            [
                'title' => 'Cognitive Psychology: Mind and Brain',
                'description' => 'Explores the relationship between cognitive processes and brain functions.',
                'year_published' => 2019,
                'category_name' => 'Cognitive Psychology',
                'authors' => ['Edward E. Smith', 'Stephen M. Kosslyn'],
                'created_at' => '2025-10-28 09:05:00'
            ],
            [
                'title' => 'Abnormal Psychology',
                'description' => 'Comprehensive overview of psychological disorders, their diagnosis, and treatment.',
                'year_published' => 2021,
                'category_name' => 'Abnormal Psychology',
                'authors' => ['Ronald J. Comer'],
                'created_at' => '2025-10-28 09:10:00'
            ],
            [
                'title' => 'Social Psychology',
                'description' => 'Examines how individuals think, influence, and relate to one another.',
                'year_published' => 2018,
                'category_name' => 'Social Psychology',
                'authors' => ['David G. Myers', 'Jean M. Twenge'],
                'created_at' => '2025-10-28 09:15:00'
            ],
            [
                'title' => 'Developmental Psychology: Childhood and Adolescence',
                'description' => 'Comprehensive study of human development from conception through adolescence.',
                'year_published' => 2022,
                'category_name' => 'Developmental Psychology',
                'authors' => ['David R. Shaffer', 'Katherine Kipp'],
                'created_at' => '2025-10-28 09:20:00'
            ],
            [
                'title' => 'Research Methods in Psychology',
                'description' => 'Guide to research design, data collection, and analysis in psychological research.',
                'year_published' => 2020,
                'category_name' => 'Research Methods',
                'authors' => ['Beth Morling'],
                'created_at' => '2025-10-28 09:25:00'
            ],
            [
                'title' => 'Biological Psychology',
                'description' => 'Examines the biological bases of behavior and mental processes.',
                'year_published' => 2019,
                'category_name' => 'Biological Psychology',
                'authors' => ['James W. Kalat'],
                'created_at' => '2025-10-28 09:30:00'
            ],
            [
                'title' => 'Personality Psychology: Domains of Knowledge About Human Nature',
                'description' => 'Comprehensive overview of major theoretical perspectives on personality.',
                'year_published' => 2021,
                'category_name' => 'Personality Psychology',
                'authors' => ['Larsen Buss'],
                'created_at' => '2025-10-28 09:35:00'
            ],
            [
                'title' => 'Cultural Psychology',
                'description' => 'Explores how cultural contexts shape human psychology and behavior.',
                'year_published' => 2020,
                'category_name' => 'Social Psychology',
                'authors' => ['Steven J. Heine'],
                'created_at' => '2025-10-28 09:40:00'
            ],
            [
                'title' => 'Industrial-Organizational Psychology',
                'description' => 'Application of psychological principles to workplace issues and organizational behavior.',
                'year_published' => 2022,
                'category_name' => 'Industrial-Organizational Psychology',
                'authors' => ['Paul E. Levy'],
                'created_at' => '2025-10-28 09:45:00'
            ]
        ];

        foreach ($booksData as $bookData) {
            // Get the category
            $category = Category::where('category_name', $bookData['category_name'])->first();

            // Create book
            $book = Book::create([
                'title' => $bookData['title'],
                'description' => $bookData['description'],
                'year_published' => $bookData['year_published'],
                'category_id' => $category->id,
                'created_at' => Carbon::parse($bookData['created_at']),
            ]);

            // Create authors and attach to book
            foreach ($bookData['authors'] as $authorName) {
                $nameParts = explode(' ', $authorName);
                $lastName = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);

                $author = Author::firstOrCreate(
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ],
                    [
                        'created_at' => now(),
                    ]
                );

                $book->authors()->attach($author->id);
            }

            // Create 3 copies for each book
            for ($i = 0; $i < 3; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'is_available' => true,
                    'created_at' => Carbon::parse($bookData['created_at']),
                ]);
            }
        }
    }
}