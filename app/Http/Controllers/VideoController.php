<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\VideoServices;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video = new VideoServices();
        $video->getMetaVideo();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vid = new Video();
        $vid->getVideoByUri('https://www.youtube.com/watch?v=Y5HSR_gUS6E&ab_channel=SEB');
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
            'video' => 'file|sometimes',
            'url' => 'string|sometimes'
        ]);

        $format = explode("x", $request->get('export'));

        $video = new Video([
            'title' => $request->file('video')->getClientOriginalName(),
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('video');

            $spec = array_merge($video->getProperties($path), [
                'name' => $request->file('video')->getClientOriginalName(),
                'size' => Storage::size($path),
                'file_path' => $path,
            ]);
        }
        // traitement autre (url)

        $video->data = $spec;
        $video->vid_time = $spec['duration'];
        $video->save();

        return response()->json([
            'properties' => $spec,
            'resource_id' => $video->id
        ]);
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
    public function convert(Video $video)
    {
        $video->convert($video->data['file_path'], 1080, 1920, []);
    }

    public function download(Video $video)
    {
        return Storage::disk('local')->download($video->data['file_path']);
    }
}
