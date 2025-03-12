<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    /** @return array<string> */
    public function rules(): array
    {
        return [
            'id' => 'required|uuid',
            'name' => 'required_without_all:email,password|string|max:255',
            'email' => 'required_without_all:name,password|email|max:255|unique:users,email,' . $this->route('id') . ',uuid',
            'current_password' => 'required_with:password|string|min:8',
            'password' => 'required_without_all:name,email|string|min:8|confirmed',
        ];
    }

    /** @return array<string> */
    public function validationData(): array
    {
        return array_merge($this->all(), ['id' => $this->route('id')]);
    }
}
