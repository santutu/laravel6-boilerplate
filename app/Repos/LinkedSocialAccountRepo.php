<?php


namespace App\Repositories;

use App\Models\LinkedSocialAccount;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Socialite\Contracts\User as ProviderUser;

class LinkedSocialAccountRepo
{

    public function findOrCreate(ProviderUser $providerUser, $providerName): User
    {
        $account = LinkedSocialAccount::where('provider_name', $providerName)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $user = User::where('email', $providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
            }

            $now = new Carbon();
            $expiredDate = $now->addSeconds($providerUser->expiresIn);

            $user->socialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $providerName,
                'avatar' => $providerUser->getAvatar(),
                'refresh_token' => $providerUser->refreshToken,
                'access_token' => $providerUser->token,
                'expired_at' => $expiredDate
            ]);

            return $user;

        }
    }

}
