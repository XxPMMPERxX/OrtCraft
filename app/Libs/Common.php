<?php

namespace App\Libs;

use xPaw\MinecraftPing;
use xPaw\MinecraftQuery;

class Common
{
    public static function pingJE(string $host, int $port, &$diffTime = null)
    {
        $startTime = hrtime(true);
        $ping = new MinecraftPing($host, $port);
        $ping->Connect($host, $port);
        $data = $ping->Query();
        $endTime = hrtime(true);

        $diffTime = ($endTime - $startTime) / 1_000_000;

        return [
            'max_players' => $data['players']['max'],
            'online_players' => $data['players']['online'],
            'motd' => trim(
                implode(
                    '',
                    array_column(
                        $data['description']['extra'],
                        'text',
                    )
                ),
            ),
        ];
    }


    public static function pingBE(string $host, int $port, &$diffTime = null)
    {
        $startTime = hrtime(true);
        $query = new MinecraftQuery();
        $query->ConnectBedrock($host, $port);
        $data = $query->GetInfo();
        $endTime = hrtime(true);

        $diffTime = ($endTime - $startTime) / 1_000_000;

        return [
            'max_players' => $data['MaxPlayers'],
            'online_players' => $data['Players'],
            'motd' => trim($data['HostName']),
        ];
    }
}
