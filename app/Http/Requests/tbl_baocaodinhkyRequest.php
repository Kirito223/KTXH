<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class tbl_baocaodinhkyRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [];
    }
}
