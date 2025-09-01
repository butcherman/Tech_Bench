<?php

namespace Database\Factories;

use App\Models\CustomerEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerWorkbook>
 */
class CustomerWorkbookFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $wbData = '{"body": [{"page": "eb7e0249-8ae3-42d0-9625-09a2fd9ea152", "title": "SV9100 Network Information", "container": [{"tag": "input", "type": "input", "index": "94a81e2c-6933-4f36-978f-7da61945d8a4", "props": {"help": null, "label": "SV9100 IP Address", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextInput"}, {"tag": "input", "type": "input", "index": "e8e99141-e748-4f99-8fe9-20d4d1a92b76", "props": {"help": null, "label": "IPLA Address", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextInput"}, {"tag": "input", "type": "input", "index": "8f66731b-4f8e-4691-a560-1e8b3b637a75", "props": {"help": null, "label": "Subnet Mask", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextInput"}, {"tag": "input", "type": "input", "index": "1974d703-8fda-4376-8318-e11b4f81cf87", "props": {"help": null, "label": "Default Gateway", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextInput"}], "canPublish": true}, {"page": "b2c93b2a-79a7-46ad-8fb1-e22640271122", "title": "Call Flow", "container": [{"tag": "input", "type": "input", "index": "1a0e9706-7381-4232-aa6c-e4119c82b781", "props": {"help": null, "label": "Primary Phone Number", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "label": {"help": "Label for Input", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextInput"}, {"tag": "input", "type": "input", "index": "13946e84-4fab-4056-bd1e-e8924e272d4e", "props": {"help": null, "rows": 5, "label": "On Hours Auto Attendant Script", "placeholder": null}, "assist": {"help": {"help": "Helpful text to show when Input is active", "type": "string", "label": "Help Text"}, "rows": {"help": "Height of Input", "type": "number", "label": "Rows"}, "label": {"help": "Label for Input Text", "type": "string", "label": "Label"}, "placeholder": {"help": "Placeholder text for empty Input", "type": "string", "label": "Placeholder"}}, "component": "TextAreaInput"}], "canPublish": true}], "footer": [], "header": [{"tag": "h3", "text": "[ Customer Name ]", "type": "static", "class": "text-center", "index": "199a4380-9a19-4156-9bc3-6d44fb8860f4"}, {"tag": "h3", "text": "NEC SV9100", "type": "static", "class": "text-center", "index": "a8f9e89a-18d9-40e0-9570-83d498acd035"}]}';

        return [
            'wb_hash' => Str::uuid(),
            'cust_equip_id' => CustomerEquipment::factory(),
            'wb_data' => $wbData,
            'wb_version' => 'factory',
            'published' => false,
            'by_invite_only' => false,
            'publish_until' => null,
        ];
    }
}
