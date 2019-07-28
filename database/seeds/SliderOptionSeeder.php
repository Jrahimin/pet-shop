<?php

use App\Model\SliderOption;
use Illuminate\Database\Seeder;

class SliderOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(SliderOption::count() == 0)
        {
            SliderOption::create([
               'title'=>"Default Option",
               'navigation'=>1,
                'pagination_type'=>1,
                'verticle'=>0, 'loop'=>1,
                'effect_type'=>1,
                'parallax'=>0,
                'height'=>300,
            ]);
        }
    }
}
