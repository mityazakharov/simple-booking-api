<?php

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            "LightGray"        => "#d3d3d3",
            "NavajoWhite"      => "#ffdead",
            "BurlyWood"        => "#deb887",
            "Gold"             => "#ffd700",
            "LightSalmon"      => "#ffa07a",
            "LightPink"        => "#ffb6c1",
            "Plum"             => "#dda0dd",
            "Thistle"          => "#d8bfd8",
            "LightSteelBlue"   => "#b0c4de",
            "SkyBlue"          => "#87ceeb",
            "Aquamarine"       => "#7fffd4",
            "MediumAquamarine" => "#66cdaa",
            "LightGreen"       => "#90ee90",
            "DarkSeaGreen"     => "#8fbc8f",
            "DarkKhaki"        => "#bdb76b",
            "Khaki"            => "#f0e68c",
        ];

        foreach ($colors as $title => $hex) {
            $color = new Color();
            $color->fill([
                'title' => $title,
                'hex' => $hex,
            ]);
            $color->save();
        }
    }
}
