<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildTimezoneList;
use Tests\TestCase;

class BuildTimezoneListUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | build()
    |---------------------------------------------------------------------------
    */
    public function test_build(): void
    {
        $shouldBe = $this->getBaseTimezoneList();

        $testObj = new BuildTimezoneList;

        $this->assertEquals($shouldBe, $testObj->build());
    }

    protected function getBaseTimezoneList(): array
    {
        return [
            'General' => [
                [
                    'text' => '(GMT/UTC + 00:00) GMT',
                    'value' => 'GMT',
                ],
                [
                    'text' => '(GMT/UTC + 00:00) UTC',
                    'value' => 'UTC',
                ],
            ],
            'America' => [
                [
                    'text' => '(GMT/UTC - 10:00) Adak',
                    'value' => 'America/Adak',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Anchorage',
                    'value' => 'America/Anchorage',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Anguilla',
                    'value' => 'America/Anguilla',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Antigua',
                    'value' => 'America/Antigua',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Araguaina',
                    'value' => 'America/Araguaina',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Buenos Aires',
                    'value' => 'America/Argentina/Buenos_Aires',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Catamarca',
                    'value' => 'America/Argentina/Catamarca',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Cordoba',
                    'value' => 'America/Argentina/Cordoba',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Jujuy',
                    'value' => 'America/Argentina/Jujuy',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / La Rioja',
                    'value' => 'America/Argentina/La_Rioja',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Mendoza',
                    'value' => 'America/Argentina/Mendoza',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Rio Gallegos',
                    'value' => 'America/Argentina/Rio_Gallegos',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Salta',
                    'value' => 'America/Argentina/Salta',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / San Juan',
                    'value' => 'America/Argentina/San_Juan',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / San Luis',
                    'value' => 'America/Argentina/San_Luis',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Tucuman',
                    'value' => 'America/Argentina/Tucuman',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Argentina / Ushuaia',
                    'value' => 'America/Argentina/Ushuaia',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Aruba',
                    'value' => 'America/Aruba',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Asuncion',
                    'value' => 'America/Asuncion',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Atikokan',
                    'value' => 'America/Atikokan',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Bahia',
                    'value' => 'America/Bahia',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Bahia Banderas',
                    'value' => 'America/Bahia_Banderas',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Barbados',
                    'value' => 'America/Barbados',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Belem',
                    'value' => 'America/Belem',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Belize',
                    'value' => 'America/Belize',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Blanc-Sablon',
                    'value' => 'America/Blanc-Sablon',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Boa Vista',
                    'value' => 'America/Boa_Vista',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Bogota',
                    'value' => 'America/Bogota',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Boise',
                    'value' => 'America/Boise',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Cambridge Bay',
                    'value' => 'America/Cambridge_Bay',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Campo Grande',
                    'value' => 'America/Campo_Grande',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Cancun',
                    'value' => 'America/Cancun',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Caracas',
                    'value' => 'America/Caracas',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Cayenne',
                    'value' => 'America/Cayenne',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Cayman',
                    'value' => 'America/Cayman',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Chicago',
                    'value' => 'America/Chicago',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Chihuahua',
                    'value' => 'America/Chihuahua',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Ciudad Juarez',
                    'value' => 'America/Ciudad_Juarez',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Costa Rica',
                    'value' => 'America/Costa_Rica',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Creston',
                    'value' => 'America/Creston',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Cuiaba',
                    'value' => 'America/Cuiaba',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Curacao',
                    'value' => 'America/Curacao',
                ],
                [
                    'text' => '(GMT/UTC + 00:00) Danmarkshavn',
                    'value' => 'America/Danmarkshavn',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Dawson',
                    'value' => 'America/Dawson',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Dawson Creek',
                    'value' => 'America/Dawson_Creek',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Denver',
                    'value' => 'America/Denver',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Detroit',
                    'value' => 'America/Detroit',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Dominica',
                    'value' => 'America/Dominica',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Edmonton',
                    'value' => 'America/Edmonton',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Eirunepe',
                    'value' => 'America/Eirunepe',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) El Salvador',
                    'value' => 'America/El_Salvador',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Fort Nelson',
                    'value' => 'America/Fort_Nelson',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Fortaleza',
                    'value' => 'America/Fortaleza',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Glace Bay',
                    'value' => 'America/Glace_Bay',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Goose Bay',
                    'value' => 'America/Goose_Bay',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Grand Turk',
                    'value' => 'America/Grand_Turk',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Grenada',
                    'value' => 'America/Grenada',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Guadeloupe',
                    'value' => 'America/Guadeloupe',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Guatemala',
                    'value' => 'America/Guatemala',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Guayaquil',
                    'value' => 'America/Guayaquil',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Guyana',
                    'value' => 'America/Guyana',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Halifax',
                    'value' => 'America/Halifax',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Havana',
                    'value' => 'America/Havana',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Hermosillo',
                    'value' => 'America/Hermosillo',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Indianapolis',
                    'value' => 'America/Indiana/Indianapolis',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Indiana / Knox',
                    'value' => 'America/Indiana/Knox',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Marengo',
                    'value' => 'America/Indiana/Marengo',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Petersburg',
                    'value' => 'America/Indiana/Petersburg',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Indiana / Tell City',
                    'value' => 'America/Indiana/Tell_City',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Vevay',
                    'value' => 'America/Indiana/Vevay',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Vincennes',
                    'value' => 'America/Indiana/Vincennes',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Indiana / Winamac',
                    'value' => 'America/Indiana/Winamac',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Inuvik',
                    'value' => 'America/Inuvik',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Iqaluit',
                    'value' => 'America/Iqaluit',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Jamaica',
                    'value' => 'America/Jamaica',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Juneau',
                    'value' => 'America/Juneau',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Kentucky / Louisville',
                    'value' => 'America/Kentucky/Louisville',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Kentucky / Monticello',
                    'value' => 'America/Kentucky/Monticello',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Kralendijk',
                    'value' => 'America/Kralendijk',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) La Paz',
                    'value' => 'America/La_Paz',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Lima',
                    'value' => 'America/Lima',
                ],
                [
                    'text' => '(GMT/UTC - 08:00) Los Angeles',
                    'value' => 'America/Los_Angeles',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Lower Princes',
                    'value' => 'America/Lower_Princes',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Maceio',
                    'value' => 'America/Maceio',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Managua',
                    'value' => 'America/Managua',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Manaus',
                    'value' => 'America/Manaus',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Marigot',
                    'value' => 'America/Marigot',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Martinique',
                    'value' => 'America/Martinique',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Matamoros',
                    'value' => 'America/Matamoros',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Mazatlan',
                    'value' => 'America/Mazatlan',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Menominee',
                    'value' => 'America/Menominee',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Merida',
                    'value' => 'America/Merida',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Metlakatla',
                    'value' => 'America/Metlakatla',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Mexico City',
                    'value' => 'America/Mexico_City',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Miquelon',
                    'value' => 'America/Miquelon',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Moncton',
                    'value' => 'America/Moncton',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Monterrey',
                    'value' => 'America/Monterrey',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Montevideo',
                    'value' => 'America/Montevideo',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Montserrat',
                    'value' => 'America/Montserrat',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Nassau',
                    'value' => 'America/Nassau',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) New York',
                    'value' => 'America/New_York',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Nome',
                    'value' => 'America/Nome',
                ],
                [
                    'text' => '(GMT/UTC - 02:00) Noronha',
                    'value' => 'America/Noronha',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) North Dakota / Beulah',
                    'value' => 'America/North_Dakota/Beulah',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) North Dakota / Center',
                    'value' => 'America/North_Dakota/Center',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) North Dakota / New Salem',
                    'value' => 'America/North_Dakota/New_Salem',
                ],
                [
                    'text' => '(GMT/UTC - 02:00) Nuuk',
                    'value' => 'America/Nuuk',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Ojinaga',
                    'value' => 'America/Ojinaga',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Panama',
                    'value' => 'America/Panama',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Paramaribo',
                    'value' => 'America/Paramaribo',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Phoenix',
                    'value' => 'America/Phoenix',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Port-au-Prince',
                    'value' => 'America/Port-au-Prince',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Port of Spain',
                    'value' => 'America/Port_of_Spain',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Porto Velho',
                    'value' => 'America/Porto_Velho',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Puerto Rico',
                    'value' => 'America/Puerto_Rico',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Punta Arenas',
                    'value' => 'America/Punta_Arenas',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Rankin Inlet',
                    'value' => 'America/Rankin_Inlet',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Recife',
                    'value' => 'America/Recife',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Regina',
                    'value' => 'America/Regina',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Resolute',
                    'value' => 'America/Resolute',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Rio Branco',
                    'value' => 'America/Rio_Branco',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Santarem',
                    'value' => 'America/Santarem',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Santiago',
                    'value' => 'America/Santiago',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Santo Domingo',
                    'value' => 'America/Santo_Domingo',
                ],
                [
                    'text' => '(GMT/UTC - 03:00) Sao Paulo',
                    'value' => 'America/Sao_Paulo',
                ],
                [
                    'text' => '(GMT/UTC - 02:00) Scoresbysund',
                    'value' => 'America/Scoresbysund',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Sitka',
                    'value' => 'America/Sitka',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) St. Barthelemy',
                    'value' => 'America/St_Barthelemy',
                ],
                [
                    'text' => '(GMT/UTC - 03:30) St. Johns',
                    'value' => 'America/St_Johns',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) St. Kitts',
                    'value' => 'America/St_Kitts',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) St. Lucia',
                    'value' => 'America/St_Lucia',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) St. Thomas',
                    'value' => 'America/St_Thomas',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) St. Vincent',
                    'value' => 'America/St_Vincent',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Swift Current',
                    'value' => 'America/Swift_Current',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Tegucigalpa',
                    'value' => 'America/Tegucigalpa',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Thule',
                    'value' => 'America/Thule',
                ],
                [
                    'text' => '(GMT/UTC - 08:00) Tijuana',
                    'value' => 'America/Tijuana',
                ],
                [
                    'text' => '(GMT/UTC - 05:00) Toronto',
                    'value' => 'America/Toronto',
                ],
                [
                    'text' => '(GMT/UTC - 04:00) Tortola',
                    'value' => 'America/Tortola',
                ],
                [
                    'text' => '(GMT/UTC - 08:00) Vancouver',
                    'value' => 'America/Vancouver',
                ],
                [
                    'text' => '(GMT/UTC - 07:00) Whitehorse',
                    'value' => 'America/Whitehorse',
                ],
                [
                    'text' => '(GMT/UTC - 06:00) Winnipeg',
                    'value' => 'America/Winnipeg',
                ],
                [
                    'text' => '(GMT/UTC - 09:00) Yakutat',
                    'value' => 'America/Yakutat',
                ],
            ],

        ];
    }
}
