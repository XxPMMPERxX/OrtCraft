<?php

namespace App\Http\Controllers;

use App\Enums\ServerMemberRole;
use App\Facades\Auth;
use App\Http\Requests\StoreServerRequest;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ServerResource::collection(
            Server::search(
                $request->all()
            )->get()
        );
    }

    /**
     * サーバ登録
     */
    public function store(StoreServerRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            if ($user->servers()->wherePivot('user_role', '=', ServerMemberRole::OWNER)->count() > 1) {
                return response()->json([
                    'message' => '登録可能なサーバ数が制限されています。',
                ], 409);
            }

            // サーバを作成
            $server = Server::register(
                $request->validated()
            );

            $server->makeVisible([
                'auth_code',
            ]);

            return new JsonResource($server);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        return new ServerResource($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreServerRequest $request, Server $server)
    {
        return DB::transaction(function () use ($request, $server) {
            $user = Auth::user();

            // サーバーのメンバーでない場合エラー
            if (!$server->isMember($user)) {
                return response()->json([], 403);
            }

            $server->update($request->validated());

            return new ServerResource($server);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        DB::transaction(function () use ($server) {
            $server->delete();
        });
    }
}
