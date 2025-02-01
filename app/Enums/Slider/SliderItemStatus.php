<?php
namespace App\Enums\Slider;
enum SliderItemStatus:int{
    case ACTIVE=1;
    case INACTIVE=0;
    public function values()
    {
        return array_column(self::cases(),'value');
    }
}
?>
