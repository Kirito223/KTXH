<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class tbl_bieumauRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}