<?php namespace Panakour\Backup\Models;

use October\Rain\Database\Model;
use October\Rain\Support\Facades\Config;
use October\Rain\Support\Facades\File;

class Settings extends Model
{
    const UPLOAD_PATH = 'app/uploads';

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'panakour_backup_settings';

    public $settingsFields = 'fields.yaml';

    public static function getBackupsPath()
    {
        $path = storage_path(self::UPLOAD_PATH.'/'.Config::get('backup.backup.name'));

        if (! File::exists($path)) {
            File::makeDirectory($path, 0775);
        }

        return $path;
    }

    public static function getIncludedFiles()
    {
        $includedPaths = [];
        if (self::get('include_files')) {
            foreach (self::get('include_files') as $item) {
                $includedPaths[] = base_path($item['path']);
            }
        }

        return $includedPaths;
    }

    public static function getExcludedFiles()
    {
        $excludedPaths = [];
        if (self::get('exclude_files')) {
            foreach (self::get('exclude_files') as $item) {
                $excludedPaths[] = base_path($item['path']);
            }
        }

        return $excludedPaths;
    }

    public static function getDatabaseDriver()
    {
        if (self::get('database_driver')) {
            return self::get('database_driver');
        }

        return 'mysql';
    }

    public static function isGzipEnabled()
    {
        if (self::get('gzip_database_dumps')) {
            return self::get('gzip_database_dumps');
        }

        return false;
    }

    public static function getFileNamePrefix()
    {
        if (self::get('filename_prefix')) {
            return self::get('filename_prefix');
        }

        return '';
    }

    public static function getStorage()
    {
        if (self::get('storage')) {
            return self::get('storage');
        }

        return 'local';
    }

    public static function getMaximumExecutionTime()
    {
        if (self::get('maximum_execution_time')) {
            return self::get('maximum_execution_time');
        }

        return 30;
    }

}
