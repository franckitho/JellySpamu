<?php

namespace  App\Services;

use Essence\Essence;

class VideoServices 
{
    public function getMetaVideo(){
        $essence = new Essence();
        $media = $essence->extract('https://www.youtube.com/watch?v=fYdQpWocBZs&ab_channel=Galax');
        dd($media);
    }
}