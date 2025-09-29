<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Peserta',
            'Email',
            'Divisi',
            'Check-in',
            'Check-out',
            'Status',
            'Durasi Kerja (menit)',
            'Lokasi',
            'Koordinat',
            'Catatan'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d/m/Y'),
            $attendance->user->name,
            $attendance->user->email,
            $attendance->user->division ? $attendance->user->division->name : '-',
            $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-',
            $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-',
            $attendance->status,
            $attendance->getWorkingDuration() ?? 0,
            $attendance->location_address ?? '-',
            $attendance->latitude && $attendance->longitude ? "{$attendance->latitude}, {$attendance->longitude}" : '-',
            $attendance->notes ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
