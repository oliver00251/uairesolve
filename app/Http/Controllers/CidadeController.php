<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function bairros($cidade_id)
    {
        $cidade = Cidade::with('bairros')->findOrFail($cidade_id);
        return response()->json($cidade->bairros);
    }
}
