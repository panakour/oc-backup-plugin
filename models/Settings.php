<?php namespace Panakour\Backup\Models;

use October\Rain\Database\Model;

class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'panakour_backup_settings';
    public $settingsFields = 'fields.yaml';

    public static function getIncludedFiles()
    {
        $includedPaths = [];
        foreach (self::get('include_files') as $item) {
            $includedPaths[] = base_path($item['path']);
        }
        return $includedPaths;
    }

    public static function getExcludedFiles()
    {
        $excludedPaths = [];
        foreach (self::get('exclude_files') as $item) {
            $excludedPaths[] = base_path($item['path']);
        }
        return $excludedPaths;
    }

    public static function getDatabaseDriver()
    {
        if(!self::get('database_driver')) {
            return 'mysql';
        }
        return self::get('database_driver');
    }

    public static function isGzipEnabled()
    {
        if(!self::get('gzip_database_dumps')) {
            return false;
        }
        return self::get('gzip_database_dumps');
    }

    public static function getFileNamePrefix()
    {
        if(!self::get('filename_prefix')) {
            return '';
        }
        return self::get('filename_prefix');
    }

}