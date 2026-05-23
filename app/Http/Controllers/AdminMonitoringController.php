<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mapel;
use Illuminate\Http\Request;

class AdminMonitoringController extends Controller
{
    public function index()
    {
        $gurus = User::where('role', 'guru')
            ->with(['mapels.babs', 'mapels.ujians', 'mapels.materis'])
            ->get();

        foreach ($gurus as $guru) {
            foreach ($guru->mapels as $mapel) {
                // Calculate "Activity Score" or "Completeness"
                // For example: 
                // - Has Babs? (30%)
                // - Has Materials in each Bab? (40%)
                // - Has Quizzes? (30%)
                
                $babCount = $mapel->babs->count();
                $materiCount = $mapel->materis->count();
                $kuisCount = $mapel->ujians->where('tipe', 'kuis')->count();

                $completeness = 0;
                if ($babCount > 0) $completeness += 30;
                if ($materiCount > 0) $completeness += 40;
                if ($kuisCount > 0) $completeness += 30;

                $mapel->completeness = $completeness;
                $mapel->bab_count = $babCount;
                $mapel->materi_count = $materiCount;
                $mapel->kuis_count = $kuisCount;
            }
        }

        return view('admin.monitoring.guru', compact('gurus'));
    }
}
