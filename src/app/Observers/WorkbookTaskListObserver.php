<?php

namespace App\Observers;

use App\Models\WorkbookTaskList;

class WorkbookTaskListObserver extends Observer
{
    /**
     * Handle the WorkbookTaskList "created" event.
     */
    public function created(WorkbookTaskList $workbookTaskList): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskList "updated" event.
     */
    public function updated(WorkbookTaskList $workbookTaskList): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskList "deleted" event.
     */
    public function deleted(WorkbookTaskList $workbookTaskList): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskList "restored" event.
     */
    public function restored(WorkbookTaskList $workbookTaskList): void
    {
        //
    }

    /**
     * Handle the WorkbookTaskList "force deleted" event.
     */
    public function forceDeleted(WorkbookTaskList $workbookTaskList): void
    {
        //
    }
}
