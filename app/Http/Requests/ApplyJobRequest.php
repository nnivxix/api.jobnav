<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'uuid'         => 'required',
            // 'user_id'      => 'required',
            // 'job_id'       => 'required',
            'cover_letter' => 'required',
            'attachment'   => 'required|file|max:2000|mimes:pdf',
            // 'status'       => 'required'
        ];
    }
}
