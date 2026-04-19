<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;

class HomeController extends Controller
{
    protected $user;
    
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        
        return view('pages.other.privacy_policy', $data);
    }

    public function landing()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.landing', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {
        if (Qs::userIsTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        if (Qs::userIsStudent()) {
            return redirect()->route('student.dashboard');
        }

        if (Qs::userIsAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $d = ['user_type' => Qs::getUserType()];

        if (Qs::userIsTeamSA()) {
            $d['users'] = $this->user->getAll();
        }

        if (Qs::userIsParent()) {
            return view('pages.parent.dashboard', $d);
        }

        if (Qs::userIsTeamAccount()) {
            return view('pages.accountant.dashboard', $d);
        }

        return view('pages.support_team.dashboard', $d);
    }
}
