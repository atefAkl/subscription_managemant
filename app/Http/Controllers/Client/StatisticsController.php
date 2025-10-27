<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $statistics = [
            'usage' => [
                'data_consumed' => '45.2',
                'data_limit' => '100',
                'percentage' => 45.2,
                'unit' => 'جيجابايت'
            ],
            'devices' => [
                'active' => 3,
                'total' => 5,
                'percentage' => 60
            ],
            'uptime' => [
                'current_month' => 99.8,
                'last_month' => 99.5,
                'average' => 99.2
            ],
            'speed' => [
                'download' => '85.4',
                'upload' => '12.8',
                'ping' => '15',
                'unit' => 'ميجابايت/ثانية'
            ]
        ];

        $monthlyData = [
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            'data_usage' => [25.5, 42.3, 38.7, 45.2, 51.8, 48.9],
            'uptime' => [99.1, 99.5, 98.8, 99.2, 99.8, 99.4]
        ];

        return view('client.statistics.index', compact('statistics', 'monthlyData'));
    }
}
