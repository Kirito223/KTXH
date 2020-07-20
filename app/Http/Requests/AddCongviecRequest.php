<?php



namespace App\Http\Requests;



use App\Http\Requests\Request;



class AddCongviecRequest extends Request

{

    /**

     * Determine if the user is authorized to make this request.

     *

     * @return bool

     */

    public function authorize()

    {

        return true;

    }



    /**

     * Get the validation rules that apply to the request.

     *

     * @return array

     */

    public function rules()

    {

        return [

            'txtCateName' => 'required|unique:congviec,id'

        ];

    }

    public function messages()

    {

        return [

            'txtCateName.required' => ' Hãy nhập trích yếu',

            'txtCateName.unique' => 'Trích yếu này đã tồn tại '

            

        ];

    }

}

