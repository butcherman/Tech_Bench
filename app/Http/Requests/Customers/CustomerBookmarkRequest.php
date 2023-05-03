<?php

namespace App\Http\Requests\Customers;

use App\Models\UserCustomerBookmark;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CustomerBookmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'model_id' => 'required|numeric|exists:customers,cust_id',
            'state' => 'required|boolean',
        ];
    }

    /**
     * Add or remove the bookmark from the user
     */
    public function toggleBookmark()
    {
        if ($this->state) {
            $this->addBookmark();
        } else {
            $this->removeBookmark();
        }
    }

    /**
     * Add the bookmark
     */
    protected function addBookmark()
    {
        try {
            UserCustomerBookmark::create([
                'user_id' => $this->user()->user_id,
                'cust_id' => $this->model_id,
            ]);
            Log::stack(['daily', 'user'])->debug('User '.$this->user()->user_id.' has added Customer ID '.$this->cust_id.' to their bookmarks');
        } catch (QueryException $e) {
            Log::critical('User '.$this->user()->username.' is trying to add a bookmark that already exists', [
                'cust_id' => $this->model_id,
                'state' => $this->state,
                'user_id' => $this->user()->user_id,
            ]);
            abort(409, 'Bookmark Already Exists');
        }
    }

    /**
     * Remove the bookmark
     */
    protected function removeBookmark()
    {
        UserCustomerBookmark::where('user_id', $this->user()->user_id)->where('cust_id', $this->model_id)->first()->delete();
        Log::stack(['daily', 'user'])->debug('User '.$this->user()->username.' has removed Customer ID '.$this->model_id.' from their bookmarks');
    }
}
