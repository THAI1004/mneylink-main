<?php

use Migrations\AbstractMigration;

class Tuhoversion2022 extends AbstractMigration
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
           ->addColumn('img_mobile', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('img_desktop', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('keywords', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();
        $this->table('datetfs')
            ->changeColumn('views', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->update();
        $this->table('jobtfs')
            ->addColumn('device', 'integer', [
                'comment' => '0 = Mobile/Tablet, 1 = Desktop',
                'default' => 0,
                'limit' => 10,
                'null' => false,
            ])
            ->update();
    }
}
