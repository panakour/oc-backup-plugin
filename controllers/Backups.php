<?php namespace PanaKour\Backup\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Artisan;
use Backend;
use Flash;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Config;
use PanaKour\Backup\Dropbox;
use Panakour\Backup\Models\Settings;
use PanaKour\Backup\Repository;

class Backups extends Controller
{
    public $pageTitle = 'Backups';

    public $requiredPermissions = ['panakour.backup.access'];

    private $repo;

    public function __construct(Repository $repository)
    {
        parent::__construct();
        $this->repo = $repository;
        BackendMenu::setContext('PanaKour.Backup', 'backup', 'backups');
    }

    public function index()
    {
        $this->addJs('/plugins/panakour/backup/assets/js/backups-page.js');
        $this->addCss('/plugins/panakour/backup/assets/css/main.css');
        $this->vars['backupFiles'] = $this->repo->getAll();
        $this->vars['oldPathBackupFiles'] = $this->repo->getLocalBackupsInTheOldPath();
    }

    public function createBackup($artisanArguments)
    {
        set_time_limit(Settings::getMaximumExecutionTime());
        Config::set('filesystems.disks.local.root', storage_path(Settings::UPLOAD_PATH));
        Artisan::call('backup:run', $artisanArguments);
        Flash::success('Backup has been created.');

        return Redirect::to(Backend::url('panakour/backup/backups'));
    }

    public function onCreateBackup()
    {
        return $this->createBackup(['--disable-notifications' => true]);
    }

    public function onCreateDatabaseBackup()
    {
        return $this->createBackup(['--disable-notifications' => true, '--only-db' => true]);
    }

    public function onCreateFilesBackup()
    {
        return $this->createBackup(['--disable-notifications' => true, '--only-files' => true]);
    }

    public function downloadDropboxBackup($baseName)
    {
        (new Dropbox())->downloadBackup($baseName);
    }

    public function onCreateWholeProjectBackup()
    {
        config([
            "backup.backup.source.files.include" => base_path(),
            "backup.backup.source.files.exclude" => [],

        ]);
        return $this->createBackup(['--disable-notifications' => true, '--filename' => "whole_project_backup.zip"]);
    }
}