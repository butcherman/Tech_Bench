<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavbarView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE OR REPLACE VIEW navbar_view AS SELECT `system_categories`.`name` AS `category`, `system_types`.`name` AS `sys_name`, `system_types`.`sys_id`, `system_types`.`parent_id` 
            FROM `system_categories` 
            LEFT JOIN `system_types` ON `system_categories`.`cat_id` = `system_types`.`cat_id` ORDER BY `system_categories`.`cat_id`, `system_types`.`parent_id`, `system_types`.`name`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `navbar_view`');
    }
}
