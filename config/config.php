<?php

return [

    'packages' => [
        'spatie/laravel-backup' => [
            'providers' => [
                \Spatie\Backup\BackupServiceProvider::class,
                \PanaKour\Backup\DropboxServiceProvider::class,
            ],

            'config_namespace' => 'backup',

            'config' => [
                'backup' => [
                    'name' => 'panakour-backup',
                    'source' => [
                        'files' => [

                            /*
                             * The list of directories and files that will be included in the backup.
                             */
                            'include' => \Panakour\Backup\Models\Settings::getIncludedFiles(),

                            /*
                             * These directories and files will be excluded from the backup.
                             *
                             * Directories used by the backup process will automatically be excluded.
                             */
                            'exclude' => \Panakour\Backup\Models\Settings::getExcludedFiles(),

                            /*
                             * Determines if symlinks should be followed.
                             */
                            'followLinks' => false,
                        ],

                        /*
                         * The names of the connections to the databases that should be backed up
                         * MySQL, PostgreSQL, SQLite and Mongo databases are supported.
                         */
                        'databases' => [
                            \Panakour\Backup\Models\Settings::getDatabaseDriver(),
                        ],
                    ],

                    /*
                     * The database dump can be gzipped to decrease diskspace usage.
                     */
                    'gzip_database_dump' => \Panakour\Backup\Models\Settings::isGzipEnabled(),

                    'destination' => [

                        /*
                         * The filename prefix used for the backup zip file.
                         */
                        'filename_prefix' => \Panakour\Backup\Models\Settings::getFileNamePrefix(),

                        /*
                         * The disk names on which the backups will be stored.
                         */
                        'disks' => [
                            \Panakour\Backup\Models\Settings::getStorage(),
                        ],
                    ],
                ],

                /*
                 * Here you can specify which backups should be monitored.
                 * If a backup does not meet the specified requirements the
                 * UnHealthyBackupWasFound event will be fired.
                 */
                'monitorBackups' => [
                    [
                        'name' => 'panakour-backup',
                        'disks' => [\Panakour\Backup\Models\Settings::getStorage()],
                        'newestBackupsShouldNotBeOlderThanDays' => 1,
                        'storageUsedMayNotBeHigherThanMegabytes' => 5000,
                    ],

                    /*
                    [
                        'name' => 'name of the second app',
                        'disks' => ['local', 's3'],
                        'newestBackupsShouldNotBeOlderThanDays' => 1,
                        'storageUsedMayNotBeHigherThanMegabytes' => 5000,
                    ],
                    */
                ],

                'cleanup' => [
                    /*
                     * The strategy that will be used to cleanup old backups. The default strategy
                     * will keep all backups for a certain amount of days. After that period only
                     * a daily backup will be kept. After that period only weekly backups will
                     * be kept and so on.
                     *
                     * No matter how you configure it the default strategy will never
                     * delete the newest backup.
                     */
                    'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

                    'defaultStrategy' => [

                        /*
                         * The number of days for which backups must be kept.
                         */
                        'keepAllBackupsForDays' => 7,

                        /*
                         * The number of days for which daily backups must be kept.
                         */
                        'keepDailyBackupsForDays' => 16,

                        /*
                         * The number of weeks for which one weekly backup must be kept.
                         */
                        'keepWeeklyBackupsForWeeks' => 8,

                        /*
                         * The number of months for which one monthly backup must be kept.
                         */
                        'keepMonthlyBackupsForMonths' => 4,

                        /*
                         * The number of years for which one yearly backup must be kept.
                         */
                        'keepYearlyBackupsForYears' => 2,

                        /*
                         * After cleaning up the backups remove the oldest backup until
                         * this amount of megabytes has been reached.
                         */
                        'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
                    ],
                ],
            ],
        ],
    ],
];