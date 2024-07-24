<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServerRequest;
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
        return new ResourceCollection(Server::search($request->all()));
    }

    /**
     * サーバ登録
     */
    public function store(StoreServerRequest $request)
    {
        DB::transaction(function () use ($request) {
            // サーバを作成
            $server = Server::register(
                $request->onlyFillable()
            );

            return new JsonResource($server);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        return new JsonResource($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        //
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
