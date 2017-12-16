<?php namespace PanaKour\Backup;

class Repository
{

    public function getBackupsPath()
    {
        return storage_path('app/panakour-backup');
    }

    public function getAll()
    {
        $backups = [];
        $backupFiles = array_values(array_diff(scandir($this->getBackupsPath()), array('.', '..')));
        foreach ($backupFiles as $index => $file) {
            $backups[$index]['fileInfo'] = pathinfo($this->getBackupsPath() . "/" . $file);
            $backups[$index]['size'] = ceil(filesize($this->getBackupsPath() . "/" . $file) / 1024);
            $backups[$index]['lastModified'] = date("d.m.Y", filemtime($this->getBackupsPath() . "/" . $file));
        }
        return $backups;
    }

}