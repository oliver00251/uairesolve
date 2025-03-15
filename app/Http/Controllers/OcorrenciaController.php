<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\OcorrenciaLog; // Importando o modelo de log
use App\Services\ImageService;
use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    public function index($filtro = null)
    {
        // Verifica se o filtro recebido é válido
        $tiposValidos = ['O', 'S', 'I', 'D']; // Ocorrência, Sugestão, Indicação, Denúncia

        if ($filtro && !in_array($filtro, $tiposValidos)) {
            abort(404); // Retorna erro 404 se o tipo for inválido
        }

        // Busca apenas ocorrências abertas, filtrando por tipo se necessário
        $ocorrencias = Ocorrencia::where('status', 'Aberta')
            ->when($filtro, function ($query) use ($filtro) {
                return $query->where('tipo', $filtro);
            })
            ->latest()
            ->get();

        return view('ocorrencias.index', compact('ocorrencias', 'filtro'));
    }

    public function create($tipo)
    {
        $registro = $tipo; // Recebe o tipo da URL
        return view('ocorrencias.create', compact('registro')); // Passa o tipo para a view
    }

    private $imageService; // <-- ADICIONE ESTA LINHA

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'tipo' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only(['titulo', 'descricao', 'localizacao', 'tipo', 'categoria_id']);
        $data['user_id'] = auth()->id();

        // Processar a imagem antes de salvar
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $this->imageService->processAndSave($request->file('imagem'));
        }

        // Cria a ocorrência
        $ocorrencia = Ocorrencia::create($data);

        // Registra a localização se houver latitude e longitude
        if ($request->filled(['latitude', 'longitude'])) {
            OcorrenciaLog::create([
                'user_id' => auth()->id(),
                'ocorrencia_id' => $ocorrencia->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
        }

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso!');
    }


    public function show(Ocorrencia $ocorrencia)
    {
        // Carregar os comentários relacionados à ocorrência
        $ocorrencia->load('comentarios');

        // Definir o título da ocorrência conforme o tipo
        $tituloOcorrencia = [
            'O' => 'Ocorrência',
            'I' => 'Infraestrutura',
            'S' => 'Sugestão',
            'D' => 'Denúncia',
            'outro' => 'Outro Tipo',
        ][$ocorrencia->tipo] ?? 'Outro Tipo';

        return view('ocorrencias.show', compact('ocorrencia', 'tituloOcorrencia'));
    }

    public function edit(Ocorrencia $ocorrencia)
    {
        // Verifica se o usuário autenticado é o autor da ocorrência
        if ($ocorrencia->user_id !== auth()->id()) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para editar esta ocorrência.');
        }

        return view('ocorrencias.edit', compact('ocorrencia'));
    }

    public function update(Request $request, Ocorrencia $ocorrencia)
    {
        // Verifica se o usuário autenticado é o autor da ocorrência
        if ($ocorrencia->user_id !== auth()->id()) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para editar esta ocorrência.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'status' => 'required|in:Aberta,Em andamento,Resolvida,Excluir',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [];
        $data['titulo'] = $request->input('titulo');
        $data['descricao'] = $request->input('descricao');
        $data['localizacao'] = $request->input('localizacao');
        $data['status'] = $request->input('status') == 'Excluir' ? 'Arquivada' : $request->input('status');
        // Verifica se a latitude e longitude foram passadas e registra no log
        if ($request->has('latitude') && $request->has('longitude')) {
            OcorrenciaLog::create([
                'user_id' => auth()->id(),
                'ocorrencia_id' => $ocorrencia->id,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]);
        }
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('ocorrencias', 'public');
        }

        $ocorrencia->update($data);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso!');
    }

    public function destroy(Ocorrencia $ocorrencia)
    {
        // Verifica se o usuário autenticado é o autor da ocorrência
        if ($ocorrencia->user_id !== auth()->id()) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para excluir esta ocorrência.');
        }

        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência removida com sucesso!');
    }
}
