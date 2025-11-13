<?php

use Migrations\AbstractMigration;

class CreateReportsTables extends AbstractMigration
{
    /**
     * Create buyer_reports and member_reports tables
     * Add missing device column to traffics table
     */
    public function up()
    {
        // Add device column to traffics table if not exists
        $trafficsTable = $this->table('traffics');
        if (!$trafficsTable->hasColumn('device')) {
            $trafficsTable->addColumn('device', 'integer', [
                'default' => 0,
                'limit' => 4,
                'null' => false,
                'signed' => false,
                'after' => 'kind'
            ])
                ->update();
        }
        // Create buyer_reports table
        $table = $this->table('buyer_reports');
        $table->addColumn('traffic_id', 'integer', [
            'default' => 0,
            'limit' => 10,
            'null' => false,
            'signed' => false,
        ])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('status', 'boolean', [
                'default' => 0,
                'null' => false,
            ])
            ->addColumn('date', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['traffic_id'], ['name' => 'idx_traffic_id'])
            ->addIndex(['user_id'], ['name' => 'idx_user_id'])
            ->addIndex(['status'], ['name' => 'idx_status'])
            ->create();

        // Create member_reports table
        $table = $this->table('member_reports');
        $table->addColumn('traffic_id', 'integer', [
            'default' => 0,
            'limit' => 10,
            'null' => false,
            'signed' => false,
        ])
            ->addColumn('ip', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('status', 'boolean', [
                'default' => 0,
                'null' => false,
            ])
            ->addColumn('date', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['traffic_id'], ['name' => 'idx_traffic_id'])
            ->addIndex(['status'], ['name' => 'idx_status'])
            ->create();
    }

    public function down()
    {
        $this->table('buyer_reports')->drop()->save();
        $this->table('member_reports')->drop()->save();
    }
}
