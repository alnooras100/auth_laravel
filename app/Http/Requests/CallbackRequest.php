<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return Auth::check(); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title_message' => 'required',
            'message' => 'required',
            'uploadFile' => 'required|file|mimes:jpeg,bmp,png,gif,docx,xlsx,pdf|max:3072',
        ];
    }
    public function attributtes(){
        return [

        ];
    }
    public function messages(){
        return [
            'title_message.required' => 'Поле Тема сообщения является обязательным',
            'message.required' => 'Поле Текст сообщения является обязательным',
            'uploadFile.required' => 'Поле Загрузить файл является обязательным',
            'uploadFile.mimes' => 'Поле загружаемого файла должно быть файлом типа:  jpg, jpeg, png, gif, docx, xlsx, pdff',
            'uploadFile.max' => 'Поле загружаемого файла должно быть меньше 3 МБ',
        ];
    }
}
