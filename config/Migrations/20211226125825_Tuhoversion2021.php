<?php

use Migrations\AbstractMigration;

class Tuhoversion2021 extends AbstractMigration
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
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('count_day', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('views', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('count', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '0 = Pause, 1 = Runing, 2 = Complete, 3 = Stop',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'user_agent',
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
        $this->table('datetfs')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('traffic_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('views', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
        $this->table('jobtfs')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('traffic_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $this->dropTable('traffics');
        $this->dropTable('jobtfs');
    }
}
