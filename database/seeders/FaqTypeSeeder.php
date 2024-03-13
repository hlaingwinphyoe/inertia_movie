<?php

namespace Database\Seeders;

use App\Models\FaqType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'slug' => "faq",
                'name' => "FAQ",
            ],
            [
                'slug' => "terms-and-conditions",
                'name' => "Terms and Conditions"
            ],
            [
                'slug' => "privacy-policy",
                'name' => "Privacy Policy"
            ], [
                'slug' => "about-us",
                'name' => "About Us"
            ]
        ];

        foreach ($faqs as $faq) {
            FaqType::firstOrCreate([
                'slug' => $faq['slug']
            ], [
                'slug' => $faq['slug'],
                'name' => $faq['name']
            ]);
        }
    }
}
