<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\VideoServices;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    private const INSTAGRAM_DOMAINE = "www.instagram.com";
    private const YOUTUBE_DOMAINE = "www.youtube.com";
    private const TIKTOK_DOMAINE = "www.tiktok.com";
    private const FACEBOOK_DOMAINE = "www.facebook.com";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vid = new Video();
        $vid = $vid->youtube('https://www.youtube.com/watch?v=JWFZtElbYwk');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'sometimes',
            'url' => 'string|sometimes'
        ]);

        $format = explode("x", $request->get('export'));

        if ($request->hasFile('video') && $request->file('video')) {
            $video = new Video([
                'title' => $request->file('video')->getClientOriginalName(),
            ]);

            $path = $request->file('video')->store('video');

            $spec = array_merge($video->getProperties($path), [
                'name' => $request->file('video')->getClientOriginalName(),
                'size' => Storage::size($path),
                'file_path' => $path,
            ]);
            $video->data = $spec;
            $video->vid_time = $spec['duration'];
            $video->save();
        }
        else if ($request->has('url') && $request->get('url')) {
            $host = parse_url($request->get('url'), PHP_URL_HOST);

            if(self::INSTAGRAM_DOMAINE == $host){
                $video = new Video();
                $path = $video->insta($request->get('url'));
                $spec = array_merge($video->getProperties($path), [
                    'name' => basename(Storage::url($path)),
                    'size' => Storage::size($path),
                    'file_path' => $path,
                ]);
                $video->data = $spec;
                $video->title = $spec['name'];
                $video->vid_time = $spec['duration'];
                $video->plateform = 'Instagram';
                $video->save();

            }elseif(self::YOUTUBE_DOMAINE == $host){
                $video = new Video();
                $data = $video->youtube($request->get('url'));
                $spec = [
                    "name" => $data['data']['download_link']['title'],
                    "size" => 935994,
                    "codec" => "h264",
                    "bitrate" => "389608",
                    "tags" => $data['data']['tags'],
                    "preview" => $data['data']['preview'],
                    "duration" => $data['vid_time'],
                    "file_path" => $data['data']['path'],
                    "framerate" => $data['data']['download_link']['fps'],
                    "resolution" => "640x360",
                    "orientation" => "Horizontal"
                ];
                $video->data = $spec;
                $video->title = $data['title'];
                $video->vid_time = $data['vid_time'];
                $video->plateform = $data['platform'];
                $video->save();
            }elseif(self::FACEBOOK_DOMAINE == $host){

            }elseif(self::TIKTOK_DOMAINE == $host){
                $video = new Video();
                $path = $video->tiktok($request->get('url'));
                $spec = array_merge($video->getProperties($path), [
                    'name' => basename(Storage::url($path)),
                    'size' => Storage::size($path),
                    'file_path' => $path,
                ]);
                $video->data = $spec;
                $video->title = $spec['name'];
                $video->vid_time = $spec['duration'];
                $video->plateform = 'TikTok';
                $video->save();
            }
        }

        if (isset($video)) {
            return response()->json([
                'status' => 'success',
                'properties' => $spec,
                'resource_id' => $video->id
            ]);
        }
        else return response()->json(['status' => 'failed']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return Inertia::render('app/views/show', [
            'video' => $video
        ]);
    }

    /**
     * convert
     *
     * @param  mixed $video
     * @return void
     */
    public function convert(Video $video, Request $request)
    {
        $request->validate([
            'platform' => 'string|required'
        ]);

        $x = $request->has('x_pos') ? $request->get('x_pos') : -1;
        $y = $request->has('y_pos') ? $request->get('y_pos') : -1;
        
        $data = $video->data;
        $data['file_path'] = $video->convert($video->data['file_path'], 1080, 1920, [], config('format.' . $request->get('platform')), $x, $y)['output_path'];
        $video->data = $data;
        $video->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function download(Video $video)
    {
        return Storage::disk('local')->download($video->data['file_path'], $video->title);
    }
}
