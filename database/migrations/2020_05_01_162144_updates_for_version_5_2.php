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
        $this->updateFilesTable();
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
}
