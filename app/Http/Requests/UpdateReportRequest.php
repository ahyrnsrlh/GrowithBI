<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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

        $report = $this->route('report');
        if (is_string($report)) {
            $report = Report::find($report);
        }

        return $report && $report->user_id === $user->id && in_array($report->status, ['draft', 'submitted', 'revision']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
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
