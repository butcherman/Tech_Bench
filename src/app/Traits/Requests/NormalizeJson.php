<?php

namespace App\Traits\Requests;

trait NormalizeJson
{
    protected function prepareForValidation()
    {
        foreach ($this->all() as $key => $value) {
            if (json_validate($value)) {
                $this->merge([$key => json_decode($value)]);
            }
        }
    }
}
