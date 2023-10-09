<?php

namespace App\Http\Controllers;

use App\Models\Homilie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ContactFormNotification;
use Illuminate\Support\Facades\Notification;

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
        $fileAudio= $audio->getClientOriginalExtension();
        $fileImg= $img->getClientOriginalExtension();
        $name_audio = $request->date.'_audio.'.$fileAudio;
        $name_img = $request->date.'_img.'.$fileImg;
        Storage::disk('audioHomily')->put($name_audio, file_get_contents($audio->getRealPath()));
        Storage::disk('imgHomily')->put($name_img, file_get_contents($img->getRealPath()));
       
        $hom = Homilie::Create([
            'date' => $request->date,
            'citation' => $request->citation,
            'title' => $request->title,
            'reading' => $request->reading,
            'gospel' => $request->gospel,
            'img' => $name_img,
            'audio' => $name_audio,
            'user_id' => $request->user_id,
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

    public function getDescHomily()
    {
        $homily = Homilie::orderBy('date', 'desc')->first();

        if ($homily) {
            return $homily;
        } else {
            return response()->json(['message' => 'No se encontraron homilías.'], 404);
        }
    }

    public function postFrmContact(Request $request)
    {
        $data = $request->all(); // Los datos del formulario
        Notification::route('mail', 'vivianaherrerahe@gmail.com')->notify(new ContactFormNotification($data));
    }
}
