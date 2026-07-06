<?php

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user || $user->role !== 'peserta') {
            return false;
        }

        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        return Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'report_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Judul Laporan',
            'description' => 'Deskripsi Laporan',
            'report_file' => 'Berkas Laporan',
        ];
    }
}
