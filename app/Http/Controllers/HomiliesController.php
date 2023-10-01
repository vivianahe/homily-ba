<?php

namespace App\Http\Controllers;

use App\Models\Homilie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HomiliesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Homilie::orderBy('homilies.date','DESC')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $audio = $request->file('audio');
        $img = $request->file('img');
        $fileAudio= $audio->getClientOriginalName();
        $fileImg= $img->getClientOriginalName();
        $name_audio = date('_H_i_s').'_'.$fileAudio;
        $name_img = date('_H_i_s').'_'.$fileImg;
        Storage::disk('AudioHomily')->put($name_audio, file_get_contents($audio->getRealPath()));
        Storage::disk('ImgHomily')->put($name_img, file_get_contents($img->getRealPath()));
       
        $hom = Homilie::Create([
            'date' => $request->date,
            'citation' => $request->citation,
            'title' => $request->title,
            'reading' => $request->reading,
            'gospel' => $request->gospel,
            'img' => $name_img,
            'audio' => $name_audio,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'data' => $hom,
            'message'=>"Homilía guardada exitosamente!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Homilie::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return Homilie::orderBy('date', 'desc')->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
