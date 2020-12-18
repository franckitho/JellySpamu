<?php

namespace App\Models;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe as FFMpegFFProbe;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as SupportFFMpeg;
use App\Services\YoutubeServices;
use App\Services\InstagramServices;
use App\Services\TiktokServices;

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
        'title',
        'data',
        'plateform',
        'vid_time',
        'categories',
    ];

    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
    public function tiktok($url){
        $api = new \Sovit\TikTok\Api();
        $result = $api->getNoWatermark($url);
        $path = '/video/'. uniqid() . '.mp4';
        Storage::disk('local')->put($path, file_get_contents($result->url));
        return $path;
    }
    public function insta($url){
        $client = new InstagramServices($url);
        $video = $client->getDownloadUrl();
        $path = '/video/'. uniqid() . '.mp4';
        Storage::disk('local')->put($path, file_get_contents($video));
        return $path;
    }

    public function youtube($url) {
        $handler = new YoutubeServices();
        $downloader = $handler->getDownloader($url);
        $downloader->setUrl($url);
        if($downloader->hasVideo()){
            $videoDownloadLink = $downloader->getVideoDownloadLink();
            $videoTitle = $videoDownloadLink[\sizeof($videoDownloadLink)-1]['title'];
            $videoQuality = $videoDownloadLink[\sizeof($videoDownloadLink)-1]['qualityLabel'];
            $videoFormat = $videoDownloadLink[\sizeof($videoDownloadLink)-1]['format'];
            $videoTime = (int)$videoDownloadLink[\sizeof($videoDownloadLink)-1]['approxDurationMs']/1000;
            $videoFileName = strtolower(str_replace(' ', '_', $videoTitle)).'.'.$videoFormat;
            $downloadURL = $videoDownloadLink[\sizeof($videoDownloadLink)-1]['url'];
            $fileName = preg_replace('/[^A-Za-z0-9.\_\-]/', '', basename($videoFileName));
            $path = 'video/'. uniqid() .'.mp4';
            if(!empty($downloadURL)){
                Storage::disk('local')->put($path, file_get_contents($downloadURL));
                $preview_path = 'preview/' . uniqid() . '.png';
                SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($path)->getFrameFromSeconds(1)->export()->save('public/' . $preview_path);
                return [
                    'data' => ['download_link' => $videoDownloadLink[\sizeof($videoDownloadLink)-1],  'path' => $path, 'preview' => $preview_path],
                    'title' => $videoTitle,
                    'path' => $path,
                    'vid_time' => $videoTime,
                    'platform' => 'youtube',
                ];
            }else{
                echo "The video is not found, please check YouTube URL.";
            }
        }else{
            echo "Please provide valid YouTube URL.";
        }
    }

    public function getProperties(string $filename, int $preview_moment = 1)
    {
        $ffmpeg = SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($filename);

        $dim = $ffmpeg->getVideoStream()->getDimensions();
        $in_width = $dim->getWidth();
        $in_height = $dim->getHeight();

        $ffprobe = FFMpegFFProbe::create()->streams(Storage::disk('local')->path($filename))->videos()->first();
        $framerate = $ffprobe->get('r_frame_rate');
        $bitrate = $ffprobe->get('bit_rate');
        $codec = $ffprobe->get('codec_name');
        $preview_path = 'preview/' . uniqid() . '.png';
        SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($filename)->getFrameFromSeconds($preview_moment)->export()->save('public/'.$preview_path);

        return [
            'orientation' => ($in_width > $in_height) ? 'Horizontal' : 'Vertical',
            'resolution' => $in_width . 'x' . $in_height,
            'duration' => $ffmpeg->getDurationInSeconds(),
            'framerate' => $framerate,
            'bitrate' => $bitrate,
            'codec' => $codec,
            'preview' => $preview_path
        ];
    }

    /**
     * convert
     *
     * @param  string $filename
     * @param  int    $width
     * @param  int    $height
     * @param  array  $metadatas
     * @return void
     */
    public function convert(string $filename, int $width, int $height, array $metadatas)
    {
        $ffmpeg = SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($filename);


        $output = 'public/converted/' . uniqid() . '.mp4';
        $output_f = 'public/converted/' . uniqid() . '.mp4';

        $dim = $ffmpeg->getVideoStream()->getDimensions();
        $in_width = $dim->getWidth();
        $in_height = $dim->getHeight();

        $format = new X264('libmp3lame', 'libx264');
        $format->on('progress', function($video, $format, $percentage) {
            echo $percentage . '%';
        });

        foreach ($metadatas as $key => $metadata) $ffmpeg->addFilter('-metadata', $key . '=' . $metadata);

        $ffmpeg
            ->addFilter(function (VideoFilters $filters) use (&$in_width, &$in_height) {
                $filters->crop(new \FFMpeg\Coordinate\Point(((int) ($in_width / 3)), 0), new \FFMpeg\Coordinate\Dimension(((int) ($in_width / 3)), $in_height));
            })
            ->export()->inFormat($format)->save($output);

        SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($output)
            ->addFilter(function (VideoFilters $filters) use (&$width, &$height) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension($width, $height));
            })
            ->export()->inFormat(new X264('libmp3lame', 'libx264'))
            ->save($output_f);

        Storage::delete($output);

        return [
            'output_path' => $output_f
        ];
    }
}
