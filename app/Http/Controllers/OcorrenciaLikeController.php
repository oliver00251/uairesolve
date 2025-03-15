<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OcorrenciaLikeController extends Controller
{
    public function store(Ocorrencia $ocorrencia)
{
    $user = Auth::user();

    // Verifica se o usuário já curtiu a ocorrência
    if ($ocorrencia->isLikedByUser($user->id)) {
        return response()->json(['message' => 'Você já curtiu esta ocorrência.'], 400);
    }

    // Cria o like
    $ocorrencia->likes()->create([
        'user_id' => $user->id
    ]);

    return response()->json(['message' => 'Ocorrência curtida com sucesso!']);
}

public function destroy(Ocorrencia $ocorrencia)
{
    $user = Auth::user();

    // Verifica se o usuário não curtiu a ocorrência
    if (!$ocorrencia->isLikedByUser($user->id)) {
        return response()->json(['message' => 'Você ainda não curtiu esta ocorrência.'], 400);
    }

    // Remove o like
    $ocorrencia->likes()->where('user_id', $user->id)->delete();

    return response()->json(['message' => 'Curtir removido com sucesso!']);
}

}
