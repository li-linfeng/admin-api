<?php

namespace  App\Services;

use App\Models\Image;
use App\Models\User;
use App\Models\UserSocialAccount;
use Carbon\Carbon;

class UserService
{

    public function  findOrCreateUserForSocialAccount($social, UserSocialAccount $socialUser)
    {
        if ($socialUser->user_id) {
            $user = $socialUser->user;
        } else {
            $user = User::create([
                'nickname' => $socialUser->nickname,
                'name'     => $socialUser->name,
                'reg_type' => $social,
                'reg_time' => Carbon::now()->toDateTimeString(),
                'email'    => $socialUser->email,
                'password' => bcrypt($socialUser->uid)
            ]);
            $this->bindSocialUserAvatarToUser($socialUser, $user);
            $this->generateShareCodeToUser($user);
        }
        return $user;
    }

    public function findOrCreateUserForMobileAccount($mobile)
    {
        $user =   User::firstOrCreate(['mobile' => $mobile], [
            'nickname'   => $mobile,
            'name'       => $mobile,
            'reg_type'   => 'mobile',
            'reg_time'   => Carbon::now()->toDateTimeString(),
            'email'      => '',
            'password'   => bcrypt($mobile),
        ]);
        $user = $this->generateShareCodeToUser($user);
        return $user;
    }

    public function binShareCodeToUser($share_code)
    {
        $parent = User::where('share_code', $share_code)->first();
        $user = auth('api')->user();
        $user->parent_id = $parent->id;
        $user->save();
        app(UserMuseAccountService::class)->inviteUser($parent->id);
        return $user;
    }

    protected function generateShareCodeToUser(User $user)
    {
        $code = generateShareCode($user->id);
        $share_code = str_pad($code, 5, "0", STR_PAD_LEFT);
        $user->share_code = $share_code;
        $user->Save();
        return $user;
    }

    protected  function  bindSocialUserAvatarToUser(UserSocialAccount  $socialAccount, User  $user)
    {
        if ($socialAccount->avatar) {
            $image = Image::create([
                'source_id' => $user->id,
                'source_type' => 'user',
                'path' => '',
                'url'  => $socialAccount->avatar
            ]);
            $user->update(['avatar_id' => $image->id]);
        }
    }
}
