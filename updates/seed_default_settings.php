<?php namespace PanaKour\Backup\Updates;

use October\Rain\Database\Updates\Seeder;
use Panakour\Backup\Models\Settings;

class SeedDefaultSettings extends Seeder
{
    public function run()
    {
        $pathsToInclude = [
            ['path' => 'themes'],
            ['path' => 'plugins'],
        ];
        $pathsToExclude = [
            ['path' => 'vendor'],
            ['path' => 'plugins/rainlab'],
        ];
        Settings::set('include_files', $pathsToInclude);
        Settings::set('exclude_files', $pathsToExclude);
        Settings::set('maximum_execution_time', 30);
    }
}
