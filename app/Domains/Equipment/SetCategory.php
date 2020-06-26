<?php

namespace App\Domains\Equipment;

use App\SystemCategories;

use Illuminate\Support\Facades\Log;

class SetCategory
{
    //  Create a new equipment category
    public function createCategory($request)
    {
        SystemCategories::create([
            'name' => $request->name,
        ]);

        return true;
    }

    //  Modify the name of an existing category
    public function updateCategory($request, $catID)
    {
        SystemCategories::findOrFail($catID)->update([
            'name' => $request->name,
        ]);

        return true;
    }

    //  Delete a category - note cannot delete if the category is in use
    public function deleteCategory($catID)
    {
        try
        {
            SystemCategories::findOrFail($catID)->delete();
            return true;
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            Log::warning('An attempt to delete Equipment Category ID '.$catID.' failed.  Reason - ', array($e));
            return false;
        }
    }
}
