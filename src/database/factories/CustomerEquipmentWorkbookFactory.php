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
        $wbData = '{"body":[{"page":"eb7e0249","title":"Network Information","container":[{"tag":"input","type":"input","index":"94a81e2c","props":{"help":null,"label":"IP Address","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"e8e99141","props":{"help":null,"label":"IPLA Address","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"8f66731b","props":{"help":null,"label":"Subnet Mask","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"1974d703","props":{"help":null,"label":"Default Gateway","placeholder":null},"component":"TextInput"}],"canPublish":false},{"page":"b2c93b2a","title":"Call Flow","container":[{"tag":"input","type":"input","index":"1a0e9706","props":{"help":null,"label":"Primary Phone Number","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"13946e84","props":{"help":null,"rows":5,"label":"On Hours Auto Attendant Script","placeholder":null},"component":"TextAreaInput"}],"canPublish":true}],"footer":[],"header":[{"tag":"h3","text":"[ Customer Name ]","type":"static","class":"text-center","index":"199a4380"},{"tag":"h3","text":"Demo Equipment","type":"static","class":"text-center","index":"a8f9e89a"}]}';
        $cust = Customer::factory()->create();

        return [
            'wb_hash' => Str::uuid(),
            'cust_id' => $cust->cust_id,
            'cust_equip_id' => CustomerEquipment::factory(['cust_id' => $cust->cust_id]),
            'wb_skeleton' => $wbData,
            'wb_version' => 'factory',
            'publish_until' => null,
        ];
    }
}
