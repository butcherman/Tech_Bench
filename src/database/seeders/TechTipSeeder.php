<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\TechTipComment;
use Illuminate\Database\Seeder;

class TechTipSeeder extends Seeder
{
    /**
     * Create 50 Tech Tips
     */
    public function run(): void
    {
        $equipTypeCount = EquipmentType::all()->count();

        /**
         * Create 60 new Tech Tips.  Assign a random number of equipment types
         */
        for ($i = 0; $i < 60; $i++) {
            $newTip = TechTip::factory()->create();

            // Assign random equipment to the Tech Tip
            $randomNum = rand(1, $equipTypeCount);
            $equipToLink = EquipmentType::inRandomOrder()
                ->limit($randomNum)
                ->get()
                ->pluck('equip_id');

            // Add a file to every third tech tip
            if ($i % 3 === 0) {
                $newFile = FileUpload::factory()->create();
                $newTip->FileUpload()->attach($newFile);
            }

            // Add comments to every 7th file
            if ($i % 7 === 0) {
                $numOfComments = rand(1, 5);
                TechTipComment::factory()
                    ->count($numOfComments)
                    ->create(['tip_id' => $newTip->tip_id]);
            }

            $newTip->EquipmentType()->sync($equipToLink);
        }

        /**
         * Disable 10 of the Tech Tips
         */
        $disabledTips = TechTip::inRandomOrder()->limit(10)->get();

        foreach ($disabledTips as $tip) {
            $tip->delete();
        }
    }
}
