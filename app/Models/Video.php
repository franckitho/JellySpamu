<?php

namespace App\Models;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as SupportFFMpeg;

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

    /**
     * Convert a stored file
     *
     * @param  string $filename
     * @return void
     */
    public function convert(string $filename)
    {
        $ffmpeg = SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($filename);

        $dim = $ffmpeg->getVideoStream()->getDimensions();
        $width = $dim->getWidth();
        $height = $dim->getHeight();

        $ffmpeg
            //->addFilter(new \FFMpeg\Filters\Video\CropFilter(new \FFMpeg\Coordinate\Point(((int) ($width / 3)), 0), new \FFMpeg\Coordinate\Dimension(((int) ($width / 3)), $height)))
            ->addFilter(function (VideoFilters $filters) use (&$width, &$height) {
                $filters->crop(new \FFMpeg\Coordinate\Point(((int) ($width / 3)), 0), new \FFMpeg\Coordinate\Dimension(((int) ($width / 3)), $height));
                //$filters->resize(new \FFMpeg\Coordinate\Dimension(1080, 1920));
            })
            ->export()
            ->inFormat(new X264('libmp3lame', 'libx264'))
            ->save('test.mp4');
    }
}
