<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'movie' => ['Draft', 'Published'],
        ];

        foreach ($statuses as $index => $name_ary) {
            foreach ($name_ary as $name) {
                Status::create([
                    'name' => $name,
                    'type' => $index,
                ]);
            }
        }
    }
}
