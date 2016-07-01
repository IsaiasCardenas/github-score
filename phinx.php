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
      'name' => 'githubScore',
      'user' => 'root',
      'pass' => 'isaias1994',
      'port' => 3306
    ]
  ]
];
