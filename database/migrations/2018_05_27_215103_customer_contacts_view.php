<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerContactsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE OR REPLACE VIEW `customer_contacts_view` AS 
            SELECT `customer_contacts`.`cont_id`, `customer_contact_phones`.`phone_number`, `customer_contact_phones`.`extension`, `phone_number_types`.`phone_type_id`,  `phone_number_types`.`description`, `phone_number_types`.`icon_class` 
            FROM `customer_contacts` 
            LEFT JOIN `customer_contact_phones` ON `customer_contacts`.`cont_id` = `customer_contact_phones`.`cont_id` 
            LEFT JOIN `phone_number_types` ON `customer_contact_phones`.`phone_type_id` = `phone_number_types`.`phone_type_id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `customer_contacts_view');
    }
}
