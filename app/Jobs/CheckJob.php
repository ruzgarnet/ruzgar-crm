<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;

trait CheckJob
{
    /**
     * Insert job to database
     *
     * @param string $job
     * @return boolean
     */
    public function check($job)
    {
        $count = DB::table('jobs')
            ->where('job', $job)
            ->whereRaw('DATEFORMAT(created_date, "%Y-%m-%d") = DATEFORMAT(NOW(), "%Y-%m-%d")')
            ->count();

        return $count > 0 ? false : true;
    }

    /**
     * Insert job to database
     *
     * @param string $job
     * @return boolean
     */
    public function insertJob($job)
    {
        if ($this->check($job)) {
            return DB::table('job')->insert(['job' => $job]);
        }
        return false;
    }
}
