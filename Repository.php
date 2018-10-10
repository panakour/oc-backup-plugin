<?php namespace PanaKour\Backup;

use File;
use Panakour\Backup\Models\Settings;

class Repository
{
    public function getAll()
    {
        return array_merge($this->getLocalBackups(), $this->getDropBoxBackups());
    }

    public function getLocalBackups()
    {
        $backups = [];
        $localBackupFiles = array_values(array_diff(scandir(Settings::getBackupsPath()), ['.', '..']));
        foreach ($localBackupFiles as $index => $file) {
            $backups[$index]['storage'] = 'Local';
            $backups[$index]['fileInfo'] = pathinfo(Settings::getBackupsPath().'/'.$file);
            $backups[$index]['size'] = ceil(filesize(Settings::getBackupsPath().'/'.$file) / 1024);
            $backups[$index]['lastModified'] = date('d.m.Y', filemtime(Settings::getBackupsPath().'/'.$file));
        }

        return $backups;
    }

    /**
     * Will be removed in the future.
     */
    public function getLocalBackupsInTheOldPath()
    {
        $backups = [];
        $path = storage_path('app/panakour-backup');
        if (File::exists($path)) {
            $localBackupFiles = array_values(array_diff(scandir($path), ['.', '..']));
            foreach ($localBackupFiles as $index => $file) {
                $backups[$index]['storage'] = 'Local';
                $backups[$index]['fileInfo'] = pathinfo($path.'/'.$file);
                $backups[$index]['size'] = ceil(filesize($path.'/'.$file) / 1024);
                $backups[$index]['lastModified'] = date('d.m.Y', filemtime($path.'/'.$file));
            }
        }

        return $backups;
    }

    public function getDropboxBackups()
    {
        $backups = [];
        $dropboxBackupFiles = (new Dropbox())->getBackups();
        foreach ($dropboxBackupFiles as $index => $file) {
            $backups[$index]['storage'] = 'Dropbox';
            $backups[$index]['fileInfo'] = $file;
            $backups[$index]['size'] = ceil($file['size'] / 1024);
            $backups[$index]['lastModified'] = date('d.m.Y', ($file['timestamp']));
        }

        return $backups;
    }
}
