<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\TechTipEquipment;
use App\Models\FileUploads;
use App\Models\TechTipFile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TechTipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Create 10 random Tech Tips
        $tips  = TechTip::factory()->count(10)->create([
            'user_id' => 2,
        ]);

        //  Assign equipment to the Tech Tip
        for($i = 0; $i < 10; $i++)
        {
            TechTipEquipment::create([
                'tip_id'   => $tips[$i]->tip_id,
                'equip_id' => EquipmentType::inRandomOrder()->first()->equip_id,
            ]);

            //  For the odd tips, add a comment
            if($i % 2 == 0)
            {
                TechTipComment::factory()->create([
                    'tip_id' => $tips[$i]->tip_id,
                    'user_id' => User::inRandomOrder()->first()->user_id,
                ]);
            }

            //  For the even tips, add a file
            if($i % 2 == 1)
            {
                $file = FileUploads::factory()->create();
                TechTipFile::factory()->create([
                    'tip_id'  => $tips[$i]->tip_id,
                    'file_id' => $file->file_id,
                ]);

                //  Create a basic image file on the filesystem
                Storage::disk('tips')->putFileAs($tips[$i]->tip_id, UploadedFile::fake()->image($file->file_name), $file->file_name);
            }
        }
    }
}
