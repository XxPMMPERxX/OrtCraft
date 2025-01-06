<?php

namespace App\Http\Controllers;

use App\Enums\ServerPlatformType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function cancelAuth(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $platform = ServerPlatformType::tryFrom($request->platform);

            switch ($platform) {
                case ServerPlatformType::JAVA:
                    $user->minecraft_java_uid = null;
                    $user->minecraft_java_gamertag = null;
                    break;
                case ServerPlatformType::BE:
                    $user->minecraft_be_uid = null;
                    $user->minecraft_be_gamertag = null;
                    break;
            }
            $user->save();

            return new JsonResource(
                $user
            );
        });
    }
}
