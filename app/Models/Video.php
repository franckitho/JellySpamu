<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use FFMpeg\{
    FFMpeg,
    Coordinate\Dimension,
    Format\Video\X264,
};

class Video extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "video_log";

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'data'
    ];

    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function convert(string $filename)
    {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($filename);

        $video->filters()->resize(new Dimension(1080, 1920))->synchronize();
        $video->save(new X264(), 'test.mp4');
    }
}
