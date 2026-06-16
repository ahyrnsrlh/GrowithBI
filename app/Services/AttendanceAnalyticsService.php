<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * AttendanceAnalyticsService
 *
 * Single Responsibility: compute attendance aggregations for analytics.
 *
 * Architecture decisions:
 * ─────────────────────
 * 1. Decoupled from controllers — injected via constructor DI.
 *
 * 2. attendanceByDivisionToday():
 *    - Counts distinct attendances WHERE check_in IS NOT NULL AND date = today.
 *    - Resolves each user's division via TWO paths (priority order):
 *        a) Their most-recent ACCEPTED application's division_id  (interns/peserta)
 *        b) users.division_id directly                            (admin/pembimbing)
 *    - Returns ALL active divisions (with 0 if no attendance yet) so the
 *      chart always shows a complete X-axis even early in the day.
 *
 * 3. Caching: 60-second server-side cache per date key — protects the DB on
 *    high traffic while still being near-real-time. WebSocket events update
 *    the in-memory chart state instantly, so cache staleness is invisible.
 *
 * 4. clearTodayCache() can be called from event listeners to ensure fresh
 *    data on the next full-page load.
 */
class AttendanceAnalyticsService
{
    private const CACHE_TTL = 60;

    /**
     * Return attendance counts per division for today only.
     *
     * Result shape:
     *   [
     *     ['division_id' => 1, 'division_name' => 'Business Analyst', 'total_present' => 12],
     *     ['division_id' => 2, 'division_name' => 'Data Analyst',     'total_present' => 0],
     *   ]
     *
     * @return Collection<int, array{division_id: int, division_name: string, total_present: int}>
     */
    public function attendanceByDivisionToday(): Collection
    {
        $cacheKey = 'attendance_by_division_' . now()->toDateString();

        return Cache::remember($cacheKey, self::CACHE_TTL, fn () => $this->buildTodayQuery());
    }

    /**
     * Invalidate the today cache.
     * Call this when a new attendance is created/updated so the next
     * full-page load returns fresh data.
     */
    public function clearTodayCache(): void
    {
        Cache::forget('attendance_by_division_' . now()->toDateString());
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    /**
     * Build and execute the aggregation query.
     *
     * Strategy:
     * 1. Run a single raw query that resolves the division per attendance
     *    using COALESCE(accepted_app.division_id, users.division_id).
     * 2. Fetch all active divisions.
     * 3. Left-merge counts into divisions (default 0 for empty divisions).
     *
     * The subquery approach avoids the "column not found in WHERE clause"
     * issue that occurs when Laravel tries to quote a COALESCE expression
     * passed to whereNotNull(). By using a subquery we filter NULL at the
     * SQL level inside the FROM clause instead.
     */
    private function buildTodayQuery(): Collection
    {
        $today = now()->toDateString();

        // Step 1: get counts keyed by resolved division_id.
        // We do the NULL check inside the HAVING clause on the alias,
        // which MySQL handles correctly.
        $rows = DB::select("
            SELECT
                COALESCE(apps.division_id, u.division_id) AS resolved_division_id,
                COUNT(a.id)                                AS cnt
            FROM attendances a
            INNER JOIN users u
                ON u.id = a.user_id
            LEFT JOIN (
                SELECT a2.user_id, a2.division_id
                FROM applications a2
                INNER JOIN (
                    SELECT user_id, MAX(id) AS max_id
                    FROM applications
                    WHERE `status` IN ('diterima', 'accepted', 'letter_ready')
                    GROUP BY user_id
                ) latest ON a2.user_id = latest.user_id AND a2.id = latest.max_id
            ) apps ON apps.user_id = u.id
            WHERE
                a.date = ?
                AND a.check_in IS NOT NULL
                AND COALESCE(apps.division_id, u.division_id) IS NOT NULL
            GROUP BY COALESCE(apps.division_id, u.division_id)
        ", [$today]);

        // Convert to a keyed map: division_id => count
        $countMap = collect($rows)->keyBy('resolved_division_id')
            ->map(fn ($row) => (int) $row->cnt);

        // Step 2: fetch all active divisions and merge counts (default 0).
        return Division::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Division $division) => [
                'division_id'   => $division->id,
                'division_name' => $division->name,
                'total_present' => $countMap->get($division->id, 0),
            ]);
    }
}
