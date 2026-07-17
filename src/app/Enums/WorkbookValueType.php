<?php

namespace App\Enums;

/*
|-------------------------------------------------------------------------------
| Workbook Value Types must be one of the below
|-------------------------------------------------------------------------------
*/

enum WorkbookValueType: string
{
    case input = 'input';
    case dataTable = 'data-table';
    case taskList = 'task-list';
}
