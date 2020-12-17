<?php

namespace  App\Services;

use Essence\Essence;

class VideoServices 
{
    public function getMetaVideo(){
        $essence = new Essence();
        $media = $essence->extract('http://www.youtube.com/watch?v=JZcTHl0Mdpk');
        dd($media);
    }
}