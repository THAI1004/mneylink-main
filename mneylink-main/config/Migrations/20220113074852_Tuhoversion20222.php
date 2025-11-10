<?php

use Migrations\AbstractMigration;

class Tuhoversion20222 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $databaseName = $this->getAdapter()->getOption('name');

        try {
            $this->execute("ALTER DATABASE `{$databaseName}` CHARACTER SET utf8 COLLATE utf8_general_ci;");
        } catch (\Exception $exception) {
        }

        

        $this->table('jobtfs')
        ->addColumn('status', 'integer', [
            'comment' => '0 = Pending, 1 = Complete',
            'default' => 0,
            'limit' => 10,
            'null' => false,
            'signed' => false
        ])
        ->update();
    }
}
