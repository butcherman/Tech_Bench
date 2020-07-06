<?php

namespace Tests\Unit\TechTips;

use App\Domains\TechTips\GetTechTips;
use App\Files;
use App\SystemTypes;
use App\TechTipFiles;
use App\TechTips;
use App\TechTipSystems;
use Tests\TestCase;

class GetTechTipsTest extends TestCase
{
    public function test_get_tip()
    {
        $tip   = factory(TechTips::class)->create();
        $sys   = factory(SystemTypes::class)->create();
        $file  = factory(Files::class)->create();
        $files = factory(TechTipFiles::class)->create(['tip_id' => $tip->tip_id, 'file_id' => $file->file_id]);
        factory(TechTipSystems::class)->create(['tip_id' => $tip->tip_id, 'sys_id' => $sys->sys_id]);
        $data = [
            'tip_id'       => $tip->tip_id,
            'sticky'       => false,
            'tip_type_id'  => $tip->tip_type_id,
            'subject'      => $tip->subject,
            'description'  => $tip->description,
            'system_types' => [
                [
                    'sys_id'              => $sys->sys_id,
                    'name'                => $sys->name,
                    'laravel_through_key' => $tip->tip_id,
                ]
            ],
            'tech_tip_files' => [
                [
                    'tip_file_id' => $files->tip_file_id,
                    'tip_id'      => $tip->tip_id,
                    'file_id'     => $files->file_id,
                    'files' => [
                        'file_id'   => $file->file_id,
                        'file_name' => $file->file_name,
                    ]
                ]
            ]
        ];

        $res = (new GetTechTips)->getTip($tip->tip_id);
        $this->assertEquals($data, $res->makeHidden(['TechTipFiles.Files', 'summary', 'created_at', 'updated_at'])->toArray());
    }
}
