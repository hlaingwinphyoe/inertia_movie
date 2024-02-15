<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = Http::get(config('services.tmdb.endpoint') . 'watch/providers/regions?api_key=' . config('services.tmdb.secret') . '&language=en-US');

        if ($countries->successful()) {
            $countries_data = $countries->json();
            foreach ($countries_data as $country) {
                foreach ($country as $item) {
                    Country::create([
                        'country_code' => $item['iso_3166_1'],
                        'name' => $item['english_name']
                    ]);
                }
            }
        }
    }
}
