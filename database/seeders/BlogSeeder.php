<?php
namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Blog::create([
                'title' => "Sample Blog Post $i",
                'slug' => Str::slug("Sample Blog Post $i"),
                'content' => "<p>This is the <strong>content</strong> for blog post $i using <em>Trix editor</em>.</p>",
                'excerpt' => "This is an excerpt of blog post $i.",
                'tags' => "tag$i,example,blog",
                'status' => rand(0, 1),
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
