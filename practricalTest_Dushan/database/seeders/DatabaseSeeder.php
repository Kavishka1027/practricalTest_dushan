<?php

namespace Database\Seeders;

use App\Website\IO\Database\Seeders\WebsiteSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(WebsiteSeeder::class);
    }
}
