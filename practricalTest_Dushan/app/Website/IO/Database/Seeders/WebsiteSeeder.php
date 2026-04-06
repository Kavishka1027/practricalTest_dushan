<?php

namespace App\Website\IO\Database\Seeders;

use App\Website\Entities\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $websites = [
            [
                'name' => 'TechCrunch',
                'url' => 'https://techcrunch.com',
            ],
            [
                'name' => 'The Verge',
                'url' => 'https://theverge.com',
            ],
            [
                'name' => 'Wired',
                'url' => 'https://wired.com',
            ],
            [
                'name' => 'Engadget',
                'url' => 'https://engadget.com',
            ],
            [
                'name' => 'Mashable',
                'url' => 'https://mashable.com',
            ],
        ];

        foreach ($websites as $website) {
            Website::updateOrCreate(
                ['url' => $website['url']],
                ['name' => $website['name']]
            );
        }
    }
}
