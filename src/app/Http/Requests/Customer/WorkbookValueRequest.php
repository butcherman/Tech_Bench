<?php

namespace App\Http\Requests\Customer;

use App\Enums\WorkbookValueType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkbookValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->public) {
            return true;
        }

        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return match ($this->input('value_type')) {
            WorkbookValueType::input->value => [
                'index' => ['required', 'string'],
                'public' => ['required', 'boolean'],
                'value' => ['nullable'],
                'value_type' => ['required', Rule::enum(WorkbookValueType::class)],
            ],
            WorkbookValueType::dataTable->value => [
                'column_name' => ['required', 'string'],
                'public' => ['required', 'boolean'],
                'row_index' => ['required', 'string'],
                'table_index' => ['required', 'string'],
                'value' => ['nullable'],
                'value_type' => ['required', Rule::enum(WorkbookValueType::class)],
            ],
            WorkbookValueType::taskList->value => [
                'list_index' => ['required', 'string'],
                'locked' => ['required', 'boolean'],
                'public' => ['required', 'boolean'],
                'workbook_task_list_item' => ['nullable', 'array'],
                'value_type' => ['required', Rule::enum(WorkbookValueType::class)],
            ],
            WorkbookValueType::taskListItem->value => [
                'completed' => ['nullable', 'boolean'],
                'completed_by' => ['nullable', 'string'],
                'file_id' => ['nullable', 'numeric'],
                'list_index' => ['required', 'string'],
                'list_item' => ['required', 'string'],
                'order' => ['required', 'numeric'],
                'delete_item' => ['nullable', 'boolean'],
                'value_type' => ['required', Rule::enum(WorkbookValueType::class)],
            ],
            // TODO - Default throw exception
        };
    }
}
