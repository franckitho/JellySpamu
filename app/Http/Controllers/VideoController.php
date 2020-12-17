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
        //
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

        $video = new Video();

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('video');
            session(['path' => $path]);

            $spec = array_merge($video->getProperties($path), [
                'name' => basename($path),
                'size' => Storage::size($path),
            ]);
        }

        // traitement autre (url)

        return response()->json([
            'properties' => $spec
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }

    public function convert(Video $video)
    {
        $video->convert(session('path'), 1080, 1920, []);
    }
}
