<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        echo "\n";
        echo "  \033[32m Please wait this may take time ------------------------------------ \033[35m Sahil Sharma \n";
        echo "\n";

        $response = Http::withHeaders([
            'Accept' => '*/*',
            'User-Agent' => 'Thunder Client (https://www.thunderclient.com)',
        ])
            ->get('https://dummyjson.com/products?limit=20');

        $data = json_decode($response->body());

        $path = 'uploads/products/';

        foreach ($data->products as $key => $product) {
            $insert = [
                'title'         => $product->title,
                'slug'          => Str::slug($product->title),
                'description'   => $product->description,
            ];
            $save = Product::create($insert);
            $cat = Category::select('id')->whereTitle($product->category)->first()->toArray();
            $save->categories()->sync($cat);

            //Adding Image
            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true, true);
            }

            $url = $product->thumbnail;
            $contents = file_get_contents($url);
            $name = uniqid('product-').'-'. time().'-'.substr($url, strrpos($url, '/') + 1);
            File::put(public_path($path.$name), $contents);

            $img = [
                'product_id'    => $save->id,
                'path'          => $path,
                'file'          => $name ,
            ];
            Image::create($img);
        }
    }
}
