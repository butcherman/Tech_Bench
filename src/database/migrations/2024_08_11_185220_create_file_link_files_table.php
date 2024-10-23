<?php

use App\Models\FileLinkFile;
use App\Models\FileLinkNote;
use App\Models\FileLinkTimeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('file_link_files')) {
            Schema::create('file_link_files', function (Blueprint $table) {
                $table->id('link_file_id');
                $table->unsignedBigInteger('link_id');
                $table->unsignedBigInteger('file_id');
                $table->unsignedBigInteger('timeline_id');
                $table->boolean('upload');
                $table->timestamps();
                $table->foreign('link_id')
                    ->references('link_id')
                    ->on('file_links')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('file_id')
                    ->references('file_id')
                    ->on('file_uploads')
                    ->onUpdate('cascade');
                $table->foreign('timeline_id')
                    ->references('timeline_id')
                    ->on('file_link_timelines')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        } else {
            Schema::table('file_link_files', function (Blueprint $table) {
                $table->unsignedBigInteger('timeline_id')
                    ->after('file_id')
                    ->nullable();
                $table->foreign('timeline_id')
                    ->references('timeline_id')
                    ->on('file_link_timelines')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });

            // Migrate any existing File Link Files
            $addedFiles = FileLinkFile::all()->groupBy('created_at');
            foreach ($addedFiles as $group) {
                $addedBy = (string) $group[0]->user_id
                    ? $group[0]->user_id
                    : $group[0]->added_by;
                $key = FileLinkTimeline::create([
                    'link_id' => $group[0]->link_id,
                    'added_by' => $addedBy,
                    'created_at' => $group[0]->created_at,
                ]);

                foreach ($group as $individualFile) {
                    $individualFile->timeline_id = $key->timeline_id;
                    $individualFile->save();

                    if ($individualFile->note) {
                        FileLinkNote::create([
                            'note' => $individualFile->note,
                            'timeline_id' => $key->timeline_id,
                        ]);
                    }
                }
            }

            // Cleanup Table
            Schema::table('file_link_files', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
                $table->dropColumn('added_by');
                $table->dropColumn('note');
                $table->unsignedBigInteger('timeline_id')
                    ->after('file_id')
                    ->change();
            });
        }

        Schema::table('file_link_files', function (Blueprint $table) {
            $table->boolean('upload')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_link_files', function (Blueprint $table) {
            $table->dropForeign(['link_id']);
            $table->dropForeign(['file_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('file_link_files');
    }
};
