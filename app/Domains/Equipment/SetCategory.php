<?php

namespace App\Domains\Equipment;

use App\SystemCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SetCategory
{
    public function createCategory($request)
    {
        SystemCategories::create([
            'name' => $request->name,
        ]);

        return true;
    }

    public function updateCategory($request, $catID)
    {
        SystemCategories::findOrFail($catID)->update([
            'name' => $request->name,
        ]);

        return true;
    }

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
