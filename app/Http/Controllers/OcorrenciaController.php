<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    public function index() {
        $ocorrencias = Ocorrencia::where('status', 'Aberta')->latest()->get();
        return view('ocorrencias.index', compact('ocorrencias'));
    }

    public function create() {
        return view('ocorrencias.create');
    }

    public function store(Request $request) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'required|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        //dd(auth()->id());
    
        $data = $request->only(['titulo', 'descricao', 'localizacao']);
        $data['user_id'] = auth()->id(); // Adiciona o ID do usuário autenticado
        $data['categoria_id'] = auth()->id(); // Adiciona o ID do usuário autenticado
    
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('ocorrencias', 'public');
        }
    
        Ocorrencia::create($data);
    
        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso!');
    }
    

    public function show(Ocorrencia $ocorrencia) {
        $ocorrencia->load('comentarios');
    
        //dd($ocorrencia); // Verifica se os comentários estão sendo carregados
    
        return view('ocorrencias.show', compact('ocorrencia'));
    }
    

    public function edit(Ocorrencia $ocorrencia) {
        return view('ocorrencias.edit', compact('ocorrencia'));
    }

    public function update(Request $request, Ocorrencia $ocorrencia) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'required|string',
            'status' => 'required|in:Aberta,Em andamento,Resolvida',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['titulo', 'descricao', 'localizacao', 'status']);

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('ocorrencias', 'public');
        }

        $ocorrencia->update($data);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso!');
    }

    public function destroy(Ocorrencia $ocorrencia) {
        $ocorrencia->delete();
        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência removida com sucesso!');
    }
}
