<?php

use App\TechTips;
use App\SystemFiles;
use App\TechTipFiles;
use App\TechTipSystems;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelocateSystemFilesToTechTips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //  Relocate any system files into the Tech Tips knowledge base
    public function up()
    {
//        Schema::table('tech_tips', function (Blueprint $table) {
//            //
//        });
        
        
        $sysFiles = SystemFiles::all();
        
        foreach($sysFiles as $sysFile)
        {
            $newTip = TechTips::create([
                'user_id'       => $sysFile->user_id,
                'public'        => 0,
                'documentation' => 1,
                'subject'       => $sysFile->name,
                'description'   => empty($sysFile->description) ? $sysFile->name : $sysFile->description,
                'created_at'    => $sysFile->created_at,
                'updated_at'    => $sysFile->updated_at
            ]);
            
            $tipId = $newTip->tip_id;
            TechTipFiles::create([
                'tip_id'  => $tipId,
                'file_id' => $sysFile->file_id
            ]);
            TechTipSystems::create([
                'tip_id' => $tipId,
                'sys_id' => $sysFile->sys_id
            ]);
        }
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('tech_tips', function (Blueprint $table) {
//            //
//        });
    }
}
