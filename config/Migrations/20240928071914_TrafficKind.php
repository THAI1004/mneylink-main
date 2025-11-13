<?php
use Migrations\AbstractMigration;

class TrafficKind extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table_prefix = '';
        $table = $this->table('traffics');
        $this->execute('ALTER TABLE `' . $table_prefix . 'traffics` ADD `kind` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default "google";');
    }

    public function down()
    {
        $table_prefix = '';
        $table = $this->table('traffics');
        $this->execute('ALTER TABLE `' . $table_prefix . 'traffics` DROP `kind`;');
    }
}
