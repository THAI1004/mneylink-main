<?php

use Migrations\AbstractMigration;

class Tuhoversion20221 extends AbstractMigration
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

        $this->table('traffics')
           ->addColumn('url', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => null,
            ])
            ->update();
    }
}
