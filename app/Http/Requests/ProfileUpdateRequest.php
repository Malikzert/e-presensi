<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            // Tambahkan validasi Gender
            'gender' => [
                'required', 
                Rule::in(['Laki-laki', 'Perempuan'])
            ],
            // Validasi foto
            'foto' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg', 
                'max:2048' // Maksimal 2MB
            ],
        ];
    }

    /**
     * Custom pesan error (Opsional, agar lebih ramah user)
     */
    public function messages(): array
    {
        return [
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan hanya JPEG, PNG, dan JPG.',
            'foto.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ];
    }
}