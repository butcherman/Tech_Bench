<?php

use Illuminate\Database\Seeder;
use App\SystemTypes;

class SystemTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Add test systems for phones
        $sys1                   = new SystemTypes;
        $sys1->sys_id           = 1;
        $sys1->cat_id           = 1;
        $sys1->name             = 'NEC';
        $sys1->parent_id        = NULL;
        $sys1->folder_location  = 'nec'; 
        $sys1->save();
        
        $sys2                   = new SystemTypes;
        $sys2->sys_id           = 2;
        $sys2->cat_id           = 1;
        $sys2->name             = 'IPK II';
        $sys2->parent_id        = NULL;
        $sys2->folder_location  = 'nec_ipkii';
        $sys2->save();
        
        $sys3                   = new SystemTypes;
        $sys3->sys_id           = 3;
        $sys3->cat_id           = 1;
        $sys3->name             = 'SV8100';
        $sys3->parent_id        = NULL;
        $sys3->folder_location  = 'nec_sv8100';
        $sys3->save();
        
        $sys4                   = new SystemTypes;
        $sys4->sys_id           = 4;
        $sys4->cat_id           = 1;
        $sys4->name             = 'SV9100';
        $sys4->parent_id        = NULL;
        $sys4->folder_location  = 'nec_sv9100';
        $sys4->save();
        
        $sys5                   = new SystemTypes;
        $sys5->sys_id           = 5;
        $sys5->cat_id           = 1;
        $sys5->name             = 'IPK';
        $sys5->parent_id        = NULL;
        $sys5->folder_location  = 'nec_ipk';
        $sys5->save();
        
        $sys6                   = new SystemTypes;
        $sys6->sys_id           = 6;
        $sys6->cat_id           = 1;
        $sys6->name             = 'Older NEC';
        $sys6->parent_id        = NULL;
        $sys6->folder_location  = 'nec_oldernec';
        $sys6->save();
        
        $sys7                   = new SystemTypes;
        $sys7->sys_id           = 7;
        $sys7->cat_id           = 1;
        $sys7->name             = 'ShoreTel';
        $sys7->parent_id        = NULL;
        $sys7->folder_location  = 'shoretel';
        $sys7->save();
        
        $sys8                   = new SystemTypes;
        $sys8->sys_id           = 8;
        $sys8->cat_id           = 1;
        $sys8->name             = 'Zultys';
        $sys8->parent_id        = NULL;
        $sys8->folder_location  = 'zultys';
        $sys8->save();
        
        $sys9                   = new SystemTypes;
        $sys9->sys_id           = 9;
        $sys9->cat_id           = 1;
        $sys9->name             = 'Miscellaneous';
        $sys9->parent_id        = NULL;
        $sys9->folder_location  = 'miscellaneous';
        $sys9->save();
        
        //  Add test systems for security
        $sys10                  = new SystemTypes;
        $sys10->sys_id          = 10;
        $sys10->cat_id          = 2;
        $sys10->name            = 'Pelco';
        $sys10->parent_id       = NULL;
        $sys10->folder_location = 'pelco';
        $sys10->save();
        
        $sys11                  = new SystemTypes;
        $sys11->sys_id          = 11;
        $sys11->cat_id          = 2;
        $sys11->name            = 'Digital Sentry';
        $sys11->parent_id       = NULL;
        $sys11->folder_location = 'pelco_digitalsentry';
        $sys11->save();
        
        $sys12                  = new SystemTypes;
        $sys12->sys_id          = 12;
        $sys12->cat_id          = 2;
        $sys12->name            = 'DX8100';
        $sys12->parent_id       = NULL;
        $sys12->folder_location = 'pelco_dx8100';
        $sys12->save();
        
        $sys13                  = new SystemTypes;
        $sys13->sys_id          = 13;
        $sys13->cat_id          = 2;
        $sys13->name            = 'IC Realtime';
        $sys13->parent_id       = NULL;
        $sys13->folder_location = 'icrealtime';
        $sys13->save();
        
        $sys14                  = new SystemTypes;
        $sys14->sys_id          = 14;
        $sys14->cat_id          = 2;
        $sys14->name            = 'Paxton';
        $sys14->parent_id       = NULL;
        $sys14->folder_location = 'paxton';
        $sys14->save();
        
        //  Add test systems for security
        $sys15                  = new SystemTypes;
        $sys15->sys_id          = 15;
        $sys15->cat_id          = 3;
        $sys15->name            = 'Class Connection';
        $sys15->parent_id       = NULL;
        $sys15->folder_location = 'classconnection';
        $sys15->save();
        
        $sys16                  = new SystemTypes;
        $sys16->sys_id          = 16;
        $sys16->cat_id          = 3;
        $sys16->name            = 'SynApps';
        $sys16->parent_id       = NULL;
        $sys16->folder_location = 'synapps';
        $sys16->save();
    }
}
