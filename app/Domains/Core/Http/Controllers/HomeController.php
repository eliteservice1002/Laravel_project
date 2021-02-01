<?php

namespace App\Domains\Core\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function home($locale = null): View | Factory | RedirectResponse
    {
        if (is_null($locale)) {
            return redirect()->to('/ar');
        }

        return view('welcome');
    }

    public function livewire(): View | Factory
    {
        return view('livewire');
    }

    public function fallback(Request $request, $fallback): RedirectResponse
    {
        // dd($fallback);
        return redirect()->to('/ar'.$fallback);
    }

    public function zoho()
    {
        $clientId = config('services.zoho.client_id');
        $redirectUri = route('admin.auth.zoho_callback');

        return redirect("https://accounts.zoho.com/oauth/v2/auth?scope=ZohoBooks.fullaccess.all&client_id={$clientId}&state=testing&response_type=code&redirect_uri={$redirectUri}");
    }

    public function zohoCallback(Request $request)
    {
        $clientId = config('services.zoho.client_id');
        $clientSecret = config('services.zoho.client_secret');
        $redirectUri = route('admin.auth.zoho_callback');

        $code = $request->get('code');
        dump($request->all());

        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
        ];
        dump($data);

        $response = Http::post('https://accounts.zoho.com/oauth/v2/token?'.http_build_query($data));

        dump($response->json());

        return redirect('/admin/auth/zoho_data?access_token='.$response->json('access_token'));
    }

    public function zohoData(Request $request)
    {
        $token = $request->get('access_token');

        $response = Http::withHeaders([
            'Authorization' => "Zoho-oauthtoken {$token}",
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
        ])
            ->get('https://books.zoho.com/api/v3/chartofaccounts', [
                'organization_id' => config('services.zoho.organization_id'),
            ]);

        $chartofaccounts = $response->json()['chartofaccounts'];
        dump($chartofaccounts);

        $response = Http::withHeaders([
            'Authorization' => "Zoho-oauthtoken {$token}",
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
        ])
            ->get('https://books.zoho.com/api/v3/settings/currencies', [
                'organization_id' => 731364901,
            ]);

        $currencies = $response->json()['currencies'];
        dump($currencies);
    }
}
