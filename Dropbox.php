<?php namespace PanaKour\Backup;

use Illuminate\Support\Facades\Config;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class Dropbox
{
    protected $client;

    protected $adapter;

    protected $fileSystem;

    protected $path;

    public function __construct()
    {
        $accessToken = Config::get('filesystems.disks.dropbox.authorizationToken') ? Config::get('filesystems.disks.dropbox.authorizationToken') : '';
        $this->client = new Client($accessToken);
        $this->adapter = new DropboxAdapter($this->client);
        $this->fileSystem = new Filesystem($this->adapter);
        if (isset($this->fileSystem->listContents()[0])) {
            $this->path = $this->fileSystem->listContents()[0]['path'];
        }
    }

    public function getBackups()
    {
        return $this->fileSystem->listContents($this->path);
    }

    public function downloadBackup($baseName)
    {
        $file = $this->adapter->read($this->path.'/'.$baseName)['contents'];
        header("Content-Type: application/zip");
        echo $file;
    }
}
