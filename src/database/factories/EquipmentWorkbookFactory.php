<?php

namespace Database\Factories;

use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipmentWorkbook>
 */
class EquipmentWorkbookFactory extends Factory
{
    protected $wbData = '{"body": [{"page": "642d9f0b-2088-4a92-a360-a7a52a76e164", "title": "Page 1", "contents": [{"type": "grid-wrapper", "index": "195aaa3f-da5f-415f-bd76-6186662b0acb", "props": {"class": "grid grid-cols-2 gap-2 my-2 min-h-20"}, "contents": [{"type": "wrapper", "index": "466516d9-7d12-4be4-aaa5-e73b293e9120", "props": {"class": "min-h-20"}, "contents": [{"type": "input", "index": "1c7cd952-6a6f-429c-bb76-f76a9b8807f0", "props": {"help": null, "label": null, "component": "TextInput", "placeholder": null}, "nodeHelper": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}}]}, {"type": "wrapper", "index": "4c7f3fd5-832e-4f4f-8483-62699ca53fc0", "props": {"class": "min-h-20"}, "contents": [{"type": "input", "index": "57d1ffb2-25e6-4ba7-ac86-15ad51352514", "props": {"help": null, "label": null, "component": "TextInput", "placeholder": null}, "nodeHelper": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}}]}]}, {"type": "input", "index": "7a6f8c28-8fb5-450f-9ae8-2687c8548abd", "props": {"help": null, "label": null, "component": "TextInput", "placeholder": null}, "nodeHelper": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}}, {"type": "input", "index": "32edcf08-3b57-404d-aad3-b00fa4a1528a", "props": {"help": null, "list": ["option 1", "option 2"], "label": null, "component": "SelectInput", "placeholder": null}, "nodeHelper": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "list": {"help": "Comma separated list of available button options", "type": "array", "label": "List of Options"}, "label": {"help": "Label for Input Text", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}}], "canPublish": true}], "footer": [], "header": [{"type": "static", "index": "2fbf2ac5-bb35-44e6-82b1-6a7a07a97777", "props": {"tag": "h3", "text": "{{customer_name}}", "class": "text-center"}}, {"type": "static", "index": "bccbf284-94dc-4f17-ae15-7c522c2dc2c2", "props": {"tag": "h3", "text": "{{equipment_name}}", "class": "text-center"}}]}';

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'equip_id' => EquipmentType::factory(),
            'workbook_data' => json_decode($this->wbData),
            'version_hash' => 'factory',
        ];
    }
}
