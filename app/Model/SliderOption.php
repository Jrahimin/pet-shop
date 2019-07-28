<?php

namespace App\Model;

use App\Enumerations\SliderEffectType;
use Illuminate\Database\Eloquent\Model;

class SliderOption extends Model
{
    protected $table = "slider_options";
    public $timestamps=false;

    protected $fillable = ['title', 'navigation', 'pagination_type', 'verticle', 'slidesPerView',
        'spaceBetween', 'loop', 'effect_type', 'speed', 'parallax', 'autoplay', 'autoplay_delay',
        'autoplay_disable_on_interaction', 'height', 'parallax_position'];

    public function setSliderOption()
    {
        $sliderOption = SliderOption::first();

        $optionkeys = ['navigation', 'verticle', 'loop', 'parallax', 'autoplay'];
        $optionInputKeys = ['slidesPerView', 'speed', 'spaceBetween', 'parallax_position'];

        $sliderOptionObj = [];
        $sliderOptionObj['height'] = $sliderOption->height;
        foreach ($optionInputKeys as $optionInputKey)
        {
            $optionValue = $sliderOption->{$optionInputKey};
            if(is_numeric($optionValue))
                $optionValue = $optionValue + 0;
            
            if(!empty($optionValue))
            {
                $sliderOptionObj[$optionInputKey] = $optionValue;
            }
        }
        if(!empty($sliderOption->pagination_type)){
            $type = "progressbar";
            if($sliderOption->pagination_type == 2)
                $type = "fraction";

            $sliderOptionObj["pagination"] = array(
                "el"=>'.swiper-pagination',
                "type"=>$type,
                "clickable"=>true,
            );
        }
        if(!empty($sliderOption->effect_type)){
            $type = "";
            if($sliderOption->effect_type == SliderEffectType::$CUBE)
            {
                $type = "cube";
                $sliderOptionObj['cubeEffect'] = array(
                    "shadow"=> true,
                    "slideShadows"=> true,
                    "shadowOffset"=> 20,
                    "shadowScale"=> 0.94,
                );
                $sliderOptionObj['grabCursor'] = true;
            }
            elseif ($sliderOption->effect_type == SliderEffectType::$COVERFLOW)
            {
                $type = "coverflow";
                $sliderOptionObj['coverflowEffect'] = array(
                    "rotate"=> 50,
                    "stretch"=> 0,
                    "depth"=> 100,
                    "modifier"=> 1,
                    "slideShadows"=> true,
                );
            }
            elseif ($sliderOption->effect_type == SliderEffectType::$FLIP)
                $type = "flip";
            else
                $type = "fade";

            $sliderOptionObj["effect"] = $type;
        }
        foreach ($optionkeys as $optionKey)
        {
            if($sliderOption->{$optionKey} == 1)
            {
                switch ($optionKey){
                    case "navigation":
                        $sliderOptionObj["navigation"] = array(
                            "nextEl"=> '.swiper-button-next',
                            "prevEl"=> '.swiper-button-prev',
                        );
                        break;
                    case "verticle":
                        $sliderOptionObj["direction"] = 'vertical';
                        break;
                    case "loop":
                        $sliderOptionObj["loop"] = true;
                        break;
                    case "parallax":
                        $sliderOptionObj["parallax"] = true;
                        break;
                    case "autoplay":
                        $sliderOption->autoplay_disable_on_interaction == 1 ? $autoplayInteraction = true : $autoplayInteraction = false;
                        $sliderOptionObj["autoplay"] = array(
                            "delay"=>$sliderOption->autoplay_delay,
                            "disableOnInteraction"=>$autoplayInteraction,
                        );
                        break;
                }
            }
        }
        return $sliderOptionObj;
    }
}
