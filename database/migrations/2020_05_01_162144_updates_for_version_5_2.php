<?php

use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Files;
use App\FileLinkFiles;

class UpdatesForVersion52 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $this->updateSystemTypesTable();
        // $this->updateFilesTable();
        // $this->updateTechTipsTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('files', 'public'))
        {
            Schema::table('files', function(Blueprint $table)
            {
                $table->dropColumn('public');
            });
        }

        if(Schema::hasColumn('tech_tips', 'sticky'))
        {
            Schema::table('tech_tips', function(Blueprint $table)
            {
                $table->dropColumn('sticky');
            });
        }

        if(Schema::hasColumn('tech_tips', 'updated_id'))
        {
            Schema::table('tech_tips', function(Blueprint $table)
            {
                $table->dropForeign(['updated_id']);
                $table->dropColumn('updated_id');
            });
        }
    }

    protected function updateSystemTypesTable()
    {
        if(Schema::hasColumn('system_types', 'parent_id'))
        {
            Schema::table('system_types', function(Blueprint $table)
            {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }
    }

    protected function updateFilesTable()
    {
        if(!Schema::hasColumn('files', 'public'))
        {
            Schema::table('files', function(Blueprint $table)
            {
                $table->boolean('public')->default(0)->after('file_link');
            });

            //  Go through all file link files and make them public
            $fileLinkFiles = FileLinkFiles::where('upload', 0)->get();
            Log::debug('files', array($fileLinkFiles));

            foreach($fileLinkFiles as $file)
            {
                Files::find($file->file_id)->update([
                    'public' => 1,
                ]);
            }
        }
    }

    protected function updateTechTipsTable()
    {
        if(!Schema::hasColumn('tech_tips', 'sticky'))
        {
            Schema::table('tech_tips', function(Blueprint $table)
            {
                $table->integer('updated_id')->unsigned()->nullable()->after('user_id');
                $table->boolean('sticky')->default(0)->after('public');
                $table->foreign('updated_id')->references('user_id')->on('users')->onUpdate('cascade');
            });
        }
    }
}
