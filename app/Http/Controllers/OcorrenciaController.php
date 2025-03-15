<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\OcorrenciaLog;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class OcorrenciaController extends Controller
{
    public function index($filtro = null) {
        $tiposValidos = ['O', 'S', 'I', 'D'];
        
        if ($filtro && !in_array($filtro, $tiposValidos)) {
            abort(404);
        }

        $ocorrencias = Ocorrencia::where('status', 'Aberta')
            ->when($filtro, function ($query) use ($filtro) {
                return $query->where('tipo', $filtro);
            })
            ->latest()
            ->get();

        return view('ocorrencias.index', compact('ocorrencias', 'filtro'));
    }

    public function create($tipo) {
        return view('ocorrencias.create', compact('tipo'));
    }

    public function store(Request $request) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'tipo' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // 1MB
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only(['titulo', 'descricao', 'localizacao', 'tipo']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('imagem')) {
            $image = $request->file('imagem');
            $filename = time() . '.webp';

            $manager = new ImageManager(['driver' => 'gd']);
            $imageResized = $manager->make($image->getRealPath())
                ->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp', 80);

            Storage::disk('public')->put("ocorrencias/{$filename}", $imageResized);
            $data['imagem'] = "ocorrencias/{$filename}";
        }

        $ocorrencia = Ocorrencia::create($data);

        if ($request->has('latitude') && $request->has('longitude')) {
            OcorrenciaLog::create([
                'user_id' => auth()->id(),
                'ocorrencia_id' => $ocorrencia->id,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]);
        }

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso!');
    }

    public function show(Ocorrencia $ocorrencia) {
        $ocorrencia->load('comentarios');

        $tituloOcorrencia = [
            'O' => 'Ocorrência',
            'I' => 'Infraestrutura',
            'S' => 'Sugestão',
            'D' => 'Denúncia',
        ][$ocorrencia->tipo] ?? 'Outro Tipo';

        return view('ocorrencias.show', compact('ocorrencia', 'tituloOcorrencia'));
    }

    public function update(Request $request, Ocorrencia $ocorrencia) {
        if ($ocorrencia->user_id !== auth()->id()) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para editar esta ocorrência.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'status' => 'required|in:Aberta,Em andamento,Resolvida,Excluir',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $data = $request->only(['titulo', 'descricao', 'localizacao', 'status']);
        $data['status'] = $request->input('status') === 'Excluir' ? 'Arquivada' : $request->input('status');

        if ($request->has('latitude') && $request->has('longitude')) {
            OcorrenciaLog::create([
                'user_id' => auth()->id(),
                'ocorrencia_id' => $ocorrencia->id,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]);
        }

        if ($request->hasFile('imagem')) {
            $image = $request->file('imagem');
            $filename = time() . '.webp';

            $manager = new ImageManager(['driver' => 'gd']);
            $imageResized = $manager->make($image->getRealPath())
                ->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp', 80);

            Storage::disk('public')->put("ocorrencias/{$filename}", $imageResized);
            $data['imagem'] = "ocorrencias/{$filename}";
        }

        $ocorrencia->update($data);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso!');
    }

    public function destroy(Ocorrencia $ocorrencia) {
        if ($ocorrencia->user_id !== auth()->id()) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para excluir esta ocorrência.');
        }

        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência removida com sucesso!');
    }
}
