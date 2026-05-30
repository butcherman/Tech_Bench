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
    // TODO - Update this default data
    protected $wbData = '{"body":[{"page":"eb7e0249","title":"Network Information","container":[{"tag":"input","type":"input","index":"94a81e2c","props":{"help":null,"label":"IP Address","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"e8e99141","props":{"help":null,"label":"IPLA Address","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"8f66731b","props":{"help":null,"label":"Subnet Mask","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"1974d703","props":{"help":null,"label":"Default Gateway","placeholder":null},"component":"TextInput"}],"canPublish":false},{"page":"b2c93b2a","title":"Call Flow","container":[{"tag":"input","type":"input","index":"1a0e9706","props":{"help":null,"label":"Primary Phone Number","placeholder":null},"component":"TextInput"},{"tag":"input","type":"input","index":"13946e84","props":{"help":null,"rows":5,"label":"On Hours Auto Attendant Script","placeholder":null},"component":"TextAreaInput"}],"canPublish":true}],"footer":[],"header":[{"tag":"h3","text":"[ Customer Name ]","type":"static","class":"text-center","index":"199a4380"},{"tag":"h3","text":"Demo Equipment","type":"static","class":"text-center","index":"a8f9e89a"}]}';

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'equip_id' => EquipmentType::factory(),
            'workbook_data' => $this->wbData,
            'version_hash' => 'factory',
        ];
    }
}
