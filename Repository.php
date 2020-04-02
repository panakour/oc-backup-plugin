<?php namespace PanaKour\Backup;

use File;
use Illuminate\Support\Facades\Config;
use Log;
use Storage;
use Panakour\Backup\Models\Settings;

class Repository
{
    public function getAll()
    {
        return array_merge($this->getLocalBackups(), $this->getWebdavBackups(), $this->getDropBoxBackups());
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

    public function getWebdavBackups()
    {
        if (Config::get('filesystems.disks.webdav') === null) {
            return [];
        }
        $backups = [];
        $path = "/panakour-backup";
        $webdavBackupFiles = Storage::disk('webdav')->files($path);
        foreach ($webdavBackupFiles as $index => $file) {

            //$size = ceil(Storage::disk('webdav')->size($file)/1024);
            //$lastModified = date('d.m.Y', Storage::disk('webdav')->lastModified($file));

            $size = 0;
            $lastModified = "00.00.0000";

            $backups[$index]['storage'] = 'Webdav';
            $backups[$index]['fileInfo']['basename'] = basename($file);
            $backups[$index]['fileInfo']['path'] = $file;
            $backups[$index]['size'] = $size;
            $backups[$index]['lastModified'] = $lastModified;
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
