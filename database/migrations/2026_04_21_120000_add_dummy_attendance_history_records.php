<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];

        $targetUser = DB::table('users')
            ->where('role', 'peserta')
            ->whereExists(function ($query) use ($acceptedStatuses) {
                $query->select(DB::raw(1))
                    ->from('applications')
                    ->whereColumn('applications.user_id', 'users.id')
                    ->whereIn('applications.status', $acceptedStatuses);
            })
            ->orderByDesc('id')
            ->first();

        if (!$targetUser) {
            $targetUser = DB::table('users')
                ->where('role', 'peserta')
                ->orderByDesc('id')
                ->first();
        }

        if (!$targetUser) {
            return;
        }

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->copy()->startOfDay();

        $officeLatitude = -5.397140;
        $officeLongitude = 105.266792;

        $records = [];
        $generated = 0;
        $cursor = 1;

        while ($generated < 15) {
            $date = $today->copy()->subDays($cursor);
            $cursor++;

            if ($date->isWeekend()) {
                continue;
            }

            $status = 'On-Time';
            $checkIn = '07:45:00';
            $checkOut = '16:30:00';

            if ($generated % 5 === 0) {
                $status = 'Late';
                $checkIn = '08:20:00';
                $checkOut = '16:45:00';
            } elseif ($generated % 7 === 0) {
                $status = 'Not-Checked-Out';
                $checkIn = '07:55:00';
                $checkOut = null;
            }

            $records[] = [
                'user_id' => $targetUser->id,
                'date' => $date->toDateString(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'latitude' => $officeLatitude,
                'longitude' => $officeLongitude,
                'location_address' => 'Bank Indonesia KPw Lampung',
                'notes' => 'Dummy attendance migration 2026-04-21 #' . ($generated + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $generated++;
        }

        foreach ($records as $record) {
            DB::table('attendances')->updateOrInsert(
                [
                    'user_id' => $record['user_id'],
                    'date' => $record['date'],
                ],
                $record
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('attendances')
            ->where('notes', 'like', 'Dummy attendance migration 2026-04-21%')
            ->delete();
    }
};
