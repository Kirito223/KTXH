<?php



namespace App\Http\Requests;



use App\Http\Requests\Request;



class AddDangkylichhopRequest extends Request

{

    /**

     * Determine if the user is authorized to make this request.

     *

     * @return bool

     */

    public function authorize()

    {

        return True;

    }



    /**

     * Get the validation rules that apply to the request.

     *

     * @return array

     */

    public function rules()

    {

       return [

            'txtTitle' => 'required',

            'sltCate' => 'required'

        ];

    }

    public function messages()

    {

        return [

            'txtTitle.required' => ' Hãy nhập ngày họp ',

            

            'sltCate.required' => 'Hãy chọn buổi họp'

            

        ];

    }

}

