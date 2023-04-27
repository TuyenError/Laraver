<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "fullname"=>'required|max:20|string',
            "email"=>"required|email",
            "phone"=>"required|max:10|min:10|numeric",
            "pass"=>"required|min:8|string",
            "confirmpass"=>"required|min:8|same:pass"
        ];
    }

    public function messages(): array{
        return[
            'fullname.required' => 'Vui lòng nhập tên.',
            'fullname.max:20' => 'Tên của bạn quá dài.',
            'email.required' => 'Email không hợp lệ.',
            'phone.required' => 'Số điện thoại không hợp lệ.',
            'pass.required' => 'Mật khẩu quá ngắn.',
            'confirmpass.required' => 'Xác thực không chính xác.'
        ];
    }
}
