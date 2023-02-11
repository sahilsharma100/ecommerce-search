<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::withHeaders([ 
            'Accept'=> '*/*', 
            'User-Agent'=> 'Thunder Client (https://www.thunderclient.com)', 
        ]) 
        ->get('https://dummyjson.com/products/categories');

        $categories = json_decode($response->body());
        foreach ($categories as $category) {
            Category::create(['title' => $category, 'slug' => Str::slug($category)]);
        }
    }
}
