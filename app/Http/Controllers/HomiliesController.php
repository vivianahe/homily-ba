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
        return Homilie::orderBy('homilies.date', 'DESC')->get();
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
        $homExist = Homilie::where('date', $request->date)->exists();
        if (!$homExist) {
            $audio = $request->file('audio');
            $img = $request->file('img');
            $fileAudio = $audio->getClientOriginalExtension();
            $fileImg = $img->getClientOriginalExtension();
            $name_audio = $request->date . '_audio.' . $fileAudio;
            $name_img = $request->date . '_img.' . $fileImg;
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
                'message' => "Homilía guardada exitosamente!"
            ]);
        } else {
            return response()->json([
                'data' => false,
                'message' => "Ya existe una homilía con fecha "
            ]);
        }
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
    public function updateHomilia(Request $request)
    {
        $hom = Homilie::where([['date', $request->date], ['id', '!=', $request->id]])->exists();
        if (!$hom) {
            $imgHom = Homilie::where('img', $request->img)->exists();
            $dataHom = Homilie::where('id', $request->id)->first();
            if ($imgHom) {
                $name_img = $request->img;
            } else {
                unlink(public_path('support/imgHomily/') . $dataHom->img);
                $img = $request->file('img');
                $fileImg = $img->getClientOriginalExtension();
                $name_img = $request->date . '_img.' . $fileImg;
                Storage::disk('imgHomily')->put($name_img, file_get_contents($img->getRealPath()));
            }
            $audHom = Homilie::where('audio', $request->audio)->exists();
            if ($audHom) {
                $name_audio = $request->audio;
            } else {
                unlink(public_path('support/audioHomily/') . $dataHom->audio);
                $audio = $request->file('audio');
                $fileAudio = $audio->getClientOriginalExtension();
                $name_audio = $request->date . '_audio.' . $fileAudio;
                Storage::disk('audioHomily')->put($name_audio, file_get_contents($audio->getRealPath()));
            }
            Homilie::where('id', $request->id)->update([
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
                'message' => "Homilía actualizada exitosamente!"
            ]);
        } else {
            return response()->json([
                'data' => $hom,
                'message' => "Ya existe una homilía con esa fecha."
            ]);
        }
    }
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $homilia = Homilie::find($id);
        if ($homilia) {
            unlink(public_path('support/imgHomily/') . $homilia->img);
            unlink(public_path('support/audioHomily/') . $homilia->audio);
            $homilia->delete();
            return response()->json([
                'data' => "ok",
                'message' => "Homilía eliminada exitosamente!"
            ]);
        } else {
            return response()->json([
                'data' => null,
                'message' => "Homilía no encontrada."
            ]);
        }
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
    public function getHomeliasId(string $id)
    {
        return Homilie::find($id);
    }
}
