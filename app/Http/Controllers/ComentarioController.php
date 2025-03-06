<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Ocorrencia;

class ComentarioController extends Controller {
    public function store(Request $request, Ocorrencia $ocorrencia) {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);
        //dd(auth()->id());
        $ocorrencia->comentarios()->create([
            'user_id' => auth()->id(),
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Comentário enviado!');
    }
}
