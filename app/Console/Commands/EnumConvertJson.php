<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\ServerMemberRole;
use App\Enums\ServerPlatformType;
use Illuminate\Support\Str;

class EnumConvertJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enum-convert-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $enums = [
        ServerMemberRole::class,
        ServerPlatformType::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsContents = [];
        $exports = [];
        foreach ($this->enums as $enum) {
            $enumName = Str::upper(Str::snake(class_basename($enum)));
            $exports[] = $enumName;
            $properties = [];
            foreach ($enum::toArray() as $caseName => $enumItem) {
                $property = $caseName . ':' . '{
                    name: \'' . $enumItem['name'] . '\',
                    value: \'' . $enumItem['value'] . '\',
                    label: \'' . $enumItem['label'] . '\',
                }';
                $properties[] = $property;
            }
            $properties = implode(",\n", $properties);
            $jsContent = "
            const {$enumName} = {
              {$properties}
            };
            ";
            $jsContents[] = $jsContent;
        }
        $exports = implode(",\n", $exports);
        $jsContents[] = "
        export {
            {$exports}
        };";
        $jsContents = implode("\n", $jsContents);
        file_put_contents(resource_path('/js/enums.ts'), $jsContents);
    }
}
