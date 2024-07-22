<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use \BigPino67\OAuth2\XBLive\Client\Provider\Profiles\ProfilesProvider;
use \BigPino67\OAuth2\XBLive\Client\Provider\XBLive;
use Exception;
use Illuminate\Support\Facades\Auth;

class MinecraftAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $code = $request->code ?? null;
        if (!$code) {
            return response(status: 400);
        }

        $provider = new XBLive([
            'clientId'          => env('AZURE_CLINET_ID'),
            'clientSecret'      => env('AZURE_CLIENT_SECRET'),
            'redirectUri'       => env('AZURE_REDIRECT_URI'),
            'logoutRedirectUri' => '',
        ]);

        try {
            $msaToken = $provider->GetAccessToken('authorization_code', [
                'scope' => $provider->scope,
                'code' => $code,
            ]);
            $xasuToken = $provider->getXasuToken($msaToken);
            $xstsToken = $provider->getXstsToken($xasuToken);

            $profilesProvider = new ProfilesProvider($xstsToken);
            $profile = $profilesProvider->getLoggedUserProfile();
        } catch (Exception $e) {
            return response(status: 400);
        }

        $minecrafUid = $profile->getId();
        $gamertag = $profile->getSettings()->getGamertag();

        /** @var User */
        $user = Auth::user();
        $user->minecraft_uid = $minecrafUid;
        $user->minecraft_gamertag = $gamertag;
        $user->save();

        return new JsonResource(
            $user
        );
    }
}
