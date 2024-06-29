<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\MockIdTokenVerify;


use \BigPino67\OAuth2\XBLive\Client\Provider\Profiles\ProfilesProvider;
use \BigPino67\OAuth2\XBLive\Client\Provider\XBLive;
use \BigPino67\OAuth2\XBLive\Client\Enum\XboxOneTitleEnum;
use \BigPino67\OAuth2\XBLive\Client\Token\AccessToken;
use Exception;

class MinecraftAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \Kreait\Firebase\Auth */
        $auth = app('firebase.auth');
        $id_token = $request->headers->get('Authorization');
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
            /**
             * 開発環境の場合はモックのidToken検証を行う
             */
            $verified_id_token = app()->isProduction()
                ? $auth->verifyIdToken($id_token) : MockIdTokenVerify::verifyIdToken($id_token);
        } catch (\Exception $e) {
            return response(status: 401);
        }

        $uid = $verified_id_token->claims()->get('sub');

        if (!$uid) {
            return response(status: 401);
        }

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
        $user = User::where('firebase_id', $uid)->firstOrFail();
        $user->minecraft_uid = $minecrafUid;
        $user->minecraft_gamertag = $gamertag;
        $user->save();

        return new JsonResource(
            $user->refresh()
        );
    }
}
