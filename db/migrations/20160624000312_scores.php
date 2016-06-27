<?php

use Phinx\Migration\AbstractMigration;

class Scores extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('scores');
        $table->addColumn('username', 'string')
            ->addColumn('score', 'integer')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('scores');
    }
}
