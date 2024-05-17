<?php

namespace EventSource\Database\Seeders;

use EventSource\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {

        Setting::query()->truncate();

        $items = [
            [
                'key' => 'last_event_id',
                'value' => '0',
            ],
        ];

        foreach ($items as $item) {
            Setting::query()->create($item);
        }
    }
}
