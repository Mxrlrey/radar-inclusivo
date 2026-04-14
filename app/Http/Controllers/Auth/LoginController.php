<?php

namespace App\Http\Controllers\Auth;

use App\Enums\BarrierStatus;
use App\Enums\WaitlistStatus;
use App\Http\Controllers\Controller;
use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\Barrier;
use App\Models\Loan;
use App\Models\Professional;
use App\Models\Student;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_admin || $user->professional_id || $user->teacher_id) {
                return redirect()->route('dashboard')
                    ->with('success', 'Login realizado com sucesso.');
            }


            Auth::logout();
            return back()->with('error', 'Usuário sem permissão de acesso.');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.'
        ]);
    }

    public function index()
    {
        $totalStudents = Student::count();
        $totalProfessionals = Professional::count();
        $totalAt = AssistiveTechnology::active('1')->count();
        $totalAem = AccessibleEducationalMaterial::active('1')->count();
        $totalLoans = Loan::count();

        $totalWaitingAndNotified = Waitlist::whereIn('status', [
            WaitlistStatus::WAITING->value,
            WaitlistStatus::NOTIFIED->value
        ])->count();

        $totalBarriers = Barrier::count();

        $barrierStatusCounts = collect(BarrierStatus::cases())->map(function ($status) {
            return [
                'label' => $status->label(),
                'color' => $status->color(),
                'count' => Barrier::status($status->value)->count(),
            ];
        })->filter(fn($item) => $item['count'] > 0)->values();

        $mapBarriers = Barrier::with(['category', 'location', 'institution', 'inspections'])
            ->get()
            ->map(function ($barrier) {
                $currentStatus = $barrier->latestStatus();

                if (!$currentStatus) return null;

                return [
                    'id' => $barrier->id,
                    'name' => $barrier->name,
                    'lat' => (float) $barrier->latitude,
                    'lng' => (float) $barrier->longitude,
                    'status' => $currentStatus->value,
                    'status_label' => $currentStatus->label(),
                    'blocks_map' => (bool) ($barrier->category?->blocks_map ?? false),
                    'category_name' => $barrier->category?->name ?? 'Sem Categoria',
                    'color' => $currentStatus->color(),
                    'url' => route('barriers.show', $barrier)
                ];
            })
            ->filter()
            ->values();

        return view('pages.dashboard', [
            'totalStudents' => $totalStudents,
            'totalProfessionals' => $totalProfessionals,
            'totalAt' => $totalAt,
            'totalAem' => $totalAem,
            'totalLoans' => $totalLoans,
            'totalBarriers' => $totalBarriers,
            'barrierStatusCounts' => $barrierStatusCounts,
            'mapBarriers' => $mapBarriers,
            'totalWaiting' => $totalWaitingAndNotified,
        ]);
    }

    public function logout(Request $request)
    {
        session()->forget('impersonator_id');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

