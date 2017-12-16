<?php namespace PanaKour\Backup\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Artisan;
use Backend;
use Flash;
use Illuminate\Support\Facades\Redirect;
use PanaKour\Backup\Repository;

class Backups extends Controller
{

    public $pageTitle = 'Backups';
    private $repo;

    public function __construct(Repository $repository)
    {
        parent::__construct();
        $this->repo = $repository;
        BackendMenu::setContext('PanaKour.Backup', 'backups', 'backups');
    }

    public function index()
    {
        $this->addJs('/plugins/panakour/backup/assets/js/backups-page.js');
        $this->addCss('/plugins/panakour/backup/assets/css/main.css');
        $this->vars['backupFiles'] = $this->repo->getAll();;
    }

    public function onCreateBackup()
    {
        Artisan::call('backup:run', ['--disable-notifications' => true]);
        Flash::success('Created Backup of whole app');
        return Redirect::to(Backend::url('panakour/backup/backups'));
    }

    public function onCreateDatabaseBackup()
    {
        Artisan::call('backup:run', ['--disable-notifications' => true, '--only-db' => true]);
        Flash::success('Created Backup of database only');
        return Redirect::to(Backend::url('panakour/backup/backups'));
    }

    public function onCreateFilesBackup()
    {
        Artisan::call('backup:run', ['--disable-notifications' => true, '--only-files' => true]);
        Flash::success('Created Backup of files only');
        return Redirect::to(Backend::url('panakour/backup/backups'));
    }

}