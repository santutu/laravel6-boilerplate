<?php


namespace App\Http\Controllers;


use App\Repositories\LinkedSocialAccountRepo;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{

    protected $linkedSocialAccountRepo;

    public function __construct(
        LinkedSocialAccountRepo $linkedSocialAccountRepo
    )
    {
        $this->linkedSocialAccountRepo = $linkedSocialAccountRepo;
    }

    public function redirectToProvider($providerName)
    {
        return Socialite::driver($providerName)->redirect();
    }

    public function handleProviderCallback(string $providerName)
    {
        try {
            $providerUser = \Socialite::driver($providerName)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $authUser = $this->linkedSocialAccountRepo->findOrCreate(
            $providerUser,
            $providerName
        );

        auth()->login($authUser, true);

        return redirect()->to('');
    }
}
