<?php

namespace App\Domains\Tenants\Http;

use App\Domains\Core\Http\Controllers\Controller;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\InvalidStateException;

class NovaAuthController extends Controller
{
    public function redirectToGoogle(SocialiteManager $socialite): RedirectResponse
    {
        return $socialite->driver('google')
            ->with(['hd' => 'johrh.com'])
            ->redirect();
    }

    public function processGoogleCallback(Request $request, SocialiteManager $socialite, AuthManager $auth): RedirectResponse
    {
        try {
            $socialUser = $socialite->driver('google')->user();
        } catch (InvalidStateException $exception) {
            return redirect()->route('nova.login')
                ->withErrors([
                    'email' => [
                        __('Google Login failed, please try again.'),
                    ],
                ]);
        }

        // Very Important! Stops anyone with any google accessing Nova!
        if ( ! Str::endsWith($socialUser->getEmail(), 'johrh.com')) {
            return redirect()->route('nova.login')
                ->withErrors([
                    'email' => [
                        __('Only johrh.com email addresses are accepted.'),
                    ],
                ]);
        }

        /** @var TenantUser $user */
        $user = TenantUser::query()->firstOrNew(['email' => $socialUser->getEmail()], [
            'name' => $socialUser->getName(),
        ]);
        $user->password = bcrypt(Str::random(64));
        $user->save();

        $auth->guard('backoffice')->login($user);

        return redirect()->intended(Nova::path());
    }
}
