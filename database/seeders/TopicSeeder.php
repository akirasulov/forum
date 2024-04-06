<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'General discussion',
            ],
            [
                'slug' => 'announcements',
                'name' => 'Announcements',
                'description' => 'Announcements',
            ],
            [
                'slug' => 'bug-reports',
                'name' => 'Bug Reports',
                'description' => 'Bug Reports',
            ],
            [
                'slug' => 'feature-requests',
                'name' => 'Feature Requests',
                'description' => 'Feature Requests',
            ],
        ];

        Topic::insert($data, ['slug']);
    }
}
