<?php namespace PanaKour\Backup;

use File;

class Repository
{
    public function getBackupsPath()
    {
        $path = storage_path('app/panakour-backup');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775);
        }

        return $path;
    }

    public function getAll()
    {
        return array_merge($this->getLocalBackups(), $this->getDropBoxBackups());
    }

    public function getLocalBackups()
    {
        $backups = [];
        $localBackupFiles = array_values(array_diff(scandir($this->getBackupsPath()), array('.', '..')));
        foreach ($localBackupFiles as $index => $file) {
            $backups[$index]['storage'] = 'Local';
            $backups[$index]['fileInfo'] = pathinfo($this->getBackupsPath() . '/' . $file);
            $backups[$index]['size'] = ceil(filesize($this->getBackupsPath() . '/' . $file) / 1024);
            $backups[$index]['lastModified'] = date('d.m.Y', filemtime($this->getBackupsPath() . '/' . $file));
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
