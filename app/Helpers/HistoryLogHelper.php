<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('createHistoryLog')) {
    function createHistoryLog($activityType, $description)
    {
        DB::table('history_logs')->insert([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'activity_type' => $activityType,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}