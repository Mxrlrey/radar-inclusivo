<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use App\Models\AssistiveTechnology;
use Illuminate\Contracts\View\View;

class AssistiveTechnologyLogController extends Controller
{
    public function index(AssistiveTechnology $assistiveTechnology): View
    {
        $logs = $this->fetchLogs($assistiveTechnology, paginate: true);

        return view(
            'pages.assistive-technologies.logs.logs',
            compact('assistiveTechnology', 'logs')
        );
    }

    private function fetchLogs(AssistiveTechnology $assistiveTechnology, bool $paginate): mixed
    {
        $query = $assistiveTechnology->logs()
            ->with('user')
            ->latest();

        return $paginate ? $query->paginate(20) : $query->get();
    }
}
