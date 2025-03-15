<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\OcorrenciaLog;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OcorrenciaController extends Controller
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index($filtro = null)
    {
        $tiposValidos = ['O', 'S', 'I', 'D']; // Ocorrência, Sugestão, Indicação, Denúncia

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

    public function create($tipo)
    {
        $registro = $tipo; // Recebe o tipo da URL
        return view('ocorrencias.create', compact('registro')); // Passa o tipo para a view
    }

    public function store(Request $request)
    {
        // Validação de dados
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'tipo' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'imagem' => 'nullable|image|max:20480', // Limite de 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $request->only(['titulo', 'descricao', 'localizacao', 'tipo', 'categoria_id']);
        $data['user_id'] = auth()->id();

        // Processa a imagem, se houver
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

        return response()->json([
            'success' => true,
            'message' => 'Ocorrência registrada com sucesso!',
            'redirect' => route('ocorrencias.index')
        ]);
    }

    public function show(Ocorrencia $ocorrencia)
    {
        $ocorrencia->load('comentarios');
        $tituloOcorrencia = [
            'O' => 'Ocorrência',
            'I' => 'Infraestrutura',
            'S' => 'Sugestão',
            'D' => 'Denúncia',
            'outro' => 'Outro Tipo',
        ][$ocorrencia->tipo] ?? 'Outro Tipo';
        $totalLikes = $ocorrencia->likes()->count();

        return view('ocorrencias.show', compact('ocorrencia', 'tituloOcorrencia','totalLikes'));
    }

    public function edit(Ocorrencia $ocorrencia)
    {
        if ($ocorrencia->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar esta ocorrência.'
            ]);
        }

        return view('ocorrencias.edit', compact('ocorrencia'));
    }

    public function update(Request $request, Ocorrencia $ocorrencia)
    {
        // Verificação de permissão
        if ($ocorrencia->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar esta ocorrência.'
            ]);
        }

        // Validação de dados
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'status' => 'required|in:Aberta,Em andamento,Resolvida,Excluir',
            'imagem' => 'nullable|image|max:20480', // Limite de 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = [];
        $data['titulo'] = $request->input('titulo');
        $data['descricao'] = $request->input('descricao');
        $data['localizacao'] = $request->input('localizacao');
        $data['status'] = $request->input('status') == 'Excluir' ? 'Arquivada' : $request->input('status');

        // Processa a imagem, se houver
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $this->imageService->processAndSave($request->file('imagem'));
        }

        // Atualiza a ocorrência
        $ocorrencia->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Ocorrência atualizada com sucesso!',
            'redirect' => route('ocorrencias.index')
        ]);
    }

    public function destroy(Ocorrencia $ocorrencia)
    {
        if ($ocorrencia->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para excluir esta ocorrência.'
            ]);
        }

        $ocorrencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ocorrência removida com sucesso!',
            'redirect' => route('ocorrencias.index')
        ]);
    }
}
