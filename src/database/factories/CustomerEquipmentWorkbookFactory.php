<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CustomerEquipmentWorkbook>
 */
class CustomerEquipmentWorkbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $publicTableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $privateTableIndex = 'a905cdb0-dfaf-4d2e-9b0a-f86f01e8a7fb';

        $wbData = '{"body": [{"page": "6dfb3585-ad3a-4b10-9904-39e586205617", "title": "Page 1", "contents": [{"type": "data-table", "index": "'.
            $publicTableIndex.'", "props": {"columns": [{"name": "Public Col 1", "type": "string"}, {"list": ["Pub 1", "Pub 2"], "name": "Public Col 2", "type": "enum"}, {"name": "Public Col 3", "type": "boolean"}, {"name": "Public Col 4", "type": "integer"}], "numberRows": false, "allowAddRow": true, "allowExport": true, "allowImport": true, "defaultRows": 10, "hideBorders": false, "allowDeleteRow": true}}], "canPublish": true}, {"page": "1d663d4c-b4d1-4986-9e4e-fb640c0833d8", "title": "Page 2", "contents": [{"type": "grid-wrapper", "index": "24b60746-aa8a-42c9-87b6-69291d21f375", "props": {"class": "grid grid-cols-2 gap-2 my-2 min-h-20"}, "contents": [{"type": "wrapper", "index": "882d6e2c-de73-4a70-bbab-41209ef61aac", "props": {"class": "min-h-20"}, "contents": [{"type": "input", "index": "108b369a-a531-482e-91a2-76a25e36338e", "props": {"help": null, "label": "Input 1", "component": "TextInput", "placeholder": null}}, {"type": "input", "index": "3038f100-1fb7-4805-a977-30f8d0122142", "props": {"help": null, "label": "Input 2", "component": "TextInput", "placeholder": null}}]}, {"type": "wrapper", "index": "7dbfd8db-02ef-48c2-ade5-a0adb7e95372", "props": {"class": "min-h-20"}, "contents": [{"type": "input", "index": "12dabe74-39df-4499-a53e-b9d04e79aef1", "props": {"help": null, "label": "Input 3", "component": "TextInput", "placeholder": null}}, {"type": "input", "index": "a48ef960-d68f-4065-86ed-7a29fd242efc", "props": {"help": null, "label": "Input 4", "component": "TextInput", "placeholder": null}}]}]}, {"type": "data-table", "index": "'.
            $privateTableIndex.'", "props": {"columns": [{"name": "Private Col 1", "type": "string"}, {"name": "Private Col 2", "type": "boolean"}, {"list": ["Opt 1", "Opt 2", "Opt 3"], "name": "Private Col 3", "type": "enum"}], "numberRows": true, "allowAddRow": true, "allowExport": true, "allowImport": true, "defaultRows": 10, "hideBorders": false, "allowDeleteRow": true}}], "canPublish": false}], "footer": [], "header": [{"type": "static", "index": "6429841c-b9ff-47bd-849a-c0b4cf7641c1", "props": {"tag": "h3", "text": "{{customer_name}}", "class": "text-center"}}, {"type": "static", "index": "01edc7eb-e6f9-4dc7-a9b8-a0f7fdac397b", "props": {"tag": "h3", "text": "{{equipment_name}}", "class": "text-center"}}]}';
        $cust = Customer::factory()->create();

        return [
            'wb_hash' => Str::uuid(),
            'cust_id' => $cust->cust_id,
            'cust_equip_id' => CustomerEquipment::factory(['cust_id' => $cust->cust_id]),
            'wb_skeleton' => json_decode($wbData),
            'wb_version' => 'factory',
            'publish_until' => null,
        ];
    }
}
