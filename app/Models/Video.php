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
        $connect = file_get_contents("$url");
            preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
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
                    'data' => ['download_link' => $videoDownloadLink[\sizeof($videoDownloadLink)-1],  'path' => $path, 'preview' => $preview_path, 'tags' => $tags],
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
    public function convert(string $filename, int $width, int $height, array $metadatas, array $output_rules, int $crop_x = -1, int $crop_y = -1)
    {
        $ffmpeg = SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($filename);

        $output = 'public/converted/' . uniqid() . '.mp4';
        $output_f = 'public/converted/' . uniqid() . '.mp4';

        $dim = $ffmpeg->getVideoStream()->getDimensions();
        $in_width = $dim->getWidth();
        $in_height = $dim->getHeight();

        $res_final = $output_rules[0];

        $buffer_cw = $in_width - $output_rules[0][0];
        if ($buffer_cw < 0) $buffer_cw += $buffer_cw * 2;

        $buffer_ch = $in_height - $output_rules[0][1];
        if ($buffer_ch < 0) $buffer_ch += $buffer_ch * 2;

        foreach ($output_rules as $rule) {
            if (($in_width - $rule[0]) < $buffer_cw && ($in_height - $rule[1]) < $buffer_ch) {
                $res_final = $rule;

                $buffer_cw = $in_width - $output_rules[0][0];
                if ($buffer_cw < 0) $buffer_cw += $buffer_cw * 2;

                $buffer_ch = $in_height - $output_rules[0][1];
                if ($buffer_ch < 0) $buffer_ch += $buffer_ch * 2;
            }
        }

        $format = new X264('libmp3lame', 'libx264');
        $format->on('progress', function($video, $format, $percentage) {
            echo $percentage . '%';
        });

        foreach ($metadatas as $key => $metadata) $ffmpeg->addFilter('-metadata', $key . '=' . $metadata);

        $crop_x = ($crop_x == -1) ? ((int) ($in_width / 3)) : $crop_x;
        $crop_y = ($crop_y == -1) ? 0 : $crop_y;

        $ffmpeg
            ->addFilter(function (VideoFilters $filters) use (&$in_width, &$in_height, &$crop_x, &$crop_y, &$res_final) {
                $filters->crop(new \FFMpeg\Coordinate\Point($crop_x, $crop_y), new \FFMpeg\Coordinate\Dimension($res_final[0], $res_final[1]));
            })
            ->export()->inFormat($format)->save($output);

        SupportFFMpeg::fromFilesystem(Storage::disk('local'))->open($output)
            ->addFilter(function (VideoFilters $filters) use (&$width, &$height, &$res_final) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension($res_final[0], $res_final[1]));
            })
            ->export()->inFormat(new X264('libmp3lame', 'libx264'))
            ->save($output_f);

        Storage::delete($output);

        return [
            'output_path' => $output_f
        ];
    }
}
