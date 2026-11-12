<?php

namespace App\Http\Controllers;


use App\Helpers\Qs;
use App\Http\Requests\UserChangePass;
use App\Http\Requests\UserUpdate;
use App\Models\StudentFaceProfile;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MyAccountController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function edit_profile()
    {
        $d['my'] = Auth::user();
        return view('pages.support_team.my_account', $d);
    }

    public function update_profile(UserUpdate $req)
    {
        $user = Auth::user();

        $d = $user->username ? $req->only(['email', 'phone', 'address']) : $req->only(['email', 'phone', 'address', 'username']);

        if(!$user->username && !$req->username && !$req->email){
            return back()->with('pop_error', __('msg.user_invalid'));
        }


        if($req->hasFile('photo')) {
            $d['photo'] = $this->storeUserPhoto($user, $req->file('photo'));
        }

        $this->user->update($user->id, $d);
        return back()->with('flash_success', __('msg.update_ok'));
    }


    public function update_photo(Request $req)
    {
        $req->validate([
            'reference_photo_path' => 'prohibited',
            'reference_photo' => 'prohibited',
            'photo' => 'required|image|mimes:jpeg,gif,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $photoUrl = $this->storeUserPhoto($user, $req->file('photo'));

        $this->user->update($user->id, ['photo' => $photoUrl]);

        return back()->with('flash_success', 'Profile photo uploaded successfully.');
    }


    private function storeUserPhoto($user, $photo): string
    {
        $f = Qs::getFileMetaData($photo);
        $f['name'] = 'photo.' . $f['ext'];
        $f['path'] = $photo->storeAs(Qs::getUploadPath($user->user_type).$user->code, $f['name']);
        $photoUrl = asset('storage/' . $f['path']);

        if ($user->user_type === 'student') {
            StudentFaceProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'face_token' => Str::random(40),
                    'face_image_path' => $photoUrl,
                    'is_active' => true,
                ]
            );
        }

        return $photoUrl;
    }

    public function change_pass(UserChangePass $req)
    {
        $user_id = Auth::user()->id;
        $my_pass = Auth::user()->password;
        $old_pass = $req->current_password;
        $new_pass = $req->password;

        if(password_verify($old_pass, $my_pass)){
            $data['password'] = Hash::make($new_pass);
            $this->user->update($user_id, $data);
            return back()->with('flash_success', __('msg.p_reset'));
        }

        return back()->with('flash_danger', __('msg.p_reset_fail'));
    }

}
