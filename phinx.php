<?php
return [
  'paths' => [
    'migrations' => __DIR__ . '/db/migrations'
  ],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'development',
    'development' => [
      'adapter' => 'mysql',
      'host' => 'localhost',
      'name' => getenv('DB_DATABASE'),
      'user' => getenv('DB_USERNAME'),
      'pass' => getenv('DB_PASSWORD'),
      'port' => 3306
    ]
  ]
];
