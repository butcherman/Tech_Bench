<?php

namespace App\Observers;

use App\Models\WorkbookTaskListItem;

class WorkbookTaskListItemObserver
{
    /**
     * Handle the WorkbookTaskListItem "created" event.
     */
    public function created(WorkbookTaskListItem $workbookTaskListItem): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskListItem "updated" event.
     */
    public function updated(WorkbookTaskListItem $workbookTaskListItem): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskListItem "deleted" event.
     */
    public function deleted(WorkbookTaskListItem $workbookTaskListItem): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskListItem "restored" event.
     */
    public function restored(WorkbookTaskListItem $workbookTaskListItem): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskListItem "force deleted" event.
     */
    public function forceDeleted(WorkbookTaskListItem $workbookTaskListItem): void
    {
        //
    }
}
