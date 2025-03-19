<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Link;
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
            abort(404, 'Filtro de tipo inválido');
        }

        $ocorrencias = Ocorrencia::where('status', 'Aberta')
            ->when($filtro, function ($query) use ($filtro) {
                return $query->where('tipo', $filtro);
            })
            ->with('categoria')
            ->latest()
            ->get();

        return view('ocorrencias.index', compact('ocorrencias', 'filtro'));
    }

    public function create()
    {
       
        $categorias = Categoria::whereIn('nome', ['Ajuda', 'Causa Animal', 'Sugestão', 'Eventos', 'Denúncias', 'Vagas de emprego'])
            ->orderBy('nome', 'asc')
            ->get();

        return view('ocorrencias.create', compact( 'categorias')); // Passa o tipo para a view
    }
    public function store(Request $request)
    {
        $test = $request->all();
        // Validação de dados
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'imagem' => 'nullable|image|mimes:jpeg,png,gif|max:20480', // Limite de 20MB
            'link' => 'nullable', // Validar se o link é válido, se fornecido
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        // Criação de uma nova instância de Ocorrencia
        $ocorrencia = new Ocorrencia();
    
        // Preenchendo os dados diretamente no modelo
        $ocorrencia->titulo = $request->input('titulo');
        $ocorrencia->descricao = $request->input('descricao');
        $ocorrencia->localizacao = $request->input('localizacao');
        $ocorrencia->user_id = auth()->id();
    
        // Garantir que tipo e categoria_id sejam iguais (tipo é o nome da categoria)
        $categoria = Categoria::find($request->input('tipo'));
        $ocorrencia->tipo = $request->input('tipo'); // Usando o nome da categoria como tipo
        $ocorrencia->categoria_id = $request->input('tipo');
    
        // Salvar o link, se houver
        if ($request->filled('link')) {
            $link = new Link(); // Assumindo que a tabela `links` tenha um modelo Link
            $link->url = $request->input('link');
            $link->categoria_id = $categoria->id;
            $link->descricao = $categoria->descricao;
            $link->save();
    
            $ocorrencia->link_id = $link->id; // Salvando o id_link na tabela ocorrencias
        }
    
        // Processa a imagem, se houver
        if ($request->hasFile('imagem')) {
            try {
                // Processa e salva a imagem
                $ocorrencia->imagem = $this->imageService->processAndSave($request->file('imagem'));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar a imagem: ' . $e->getMessage()
                ]);
            }
        }
    
        // Salva a ocorrência no banco de dados
        $ocorrencia->save();
    
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
            'S' => 'Sugestão',
            'I' => 'Indicação',
            'D' => 'Denúncia',
            'outro' => 'Outro Tipo',
        ][$ocorrencia->tipo] ?? 'Outro Tipo';

        $totalLikes = $ocorrencia->likes()->count();

        return view('ocorrencias.show', compact('ocorrencia', 'tituloOcorrencia', 'totalLikes'));
    }

    public function edit(Ocorrencia $ocorrencia)
    {
        $categorias = Categoria::whereIn('nome', ['Ajuda', 'Causa Animal', 'Sugestão', 'Eventos', 'Denúncias', 'Vagas de emprego'])
            ->orderBy('nome', 'asc')
            ->get();
    
        // Chama o relacionamento 'link' para carregar o link associado à ocorrência
        $link = $ocorrencia->link;  // Acessando o relacionamento 'link'
    
        if (!$this->hasPermission($ocorrencia)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar esta ocorrência.'
            ]);
        }
    
        return view('ocorrencias.edit', compact('ocorrencia', 'categorias', 'link'));
    }
    

    public function update(Request $request, Ocorrencia $ocorrencia)
    {
        // Verificação de permissão
        if (!$this->hasPermission($ocorrencia)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar esta ocorrência.'
            ]);
        }
    
        // Validação de dados
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',  // Garantir que o campo localizacao seja válido
            'status' => 'required|in:Aberta,Em andamento,Resolvida,Excluir',
            'imagem' => 'nullable|image|mimes:jpeg,png,gif|max:20480', // Limite de 20MB
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        // Preenchendo os dados diretamente no modelo
        $ocorrencia->titulo = $request->input('titulo');
        $ocorrencia->descricao = $request->input('descricao');
        $ocorrencia->localizacao = $request->input('localizacao');  // Salva o valor da localização
        $ocorrencia->status = $request->input('status') == 'Excluir' ? 'Arquivada' : $request->input('status');
    
        // Garantir que tipo e categoria_id sejam iguais
        $categoria = Categoria::find($request->input('tipo'));
        $ocorrencia->tipo = $request->input('tipo'); // Usando o nome da categoria como tipo
        $ocorrencia->categoria_id = $request->input('tipo'); // Atualiza o ID da categoria
    
        // Processa a imagem, se houver
        if ($request->hasFile('imagem')) {
            try {
                $ocorrencia->imagem = $this->imageService->processAndSave($request->file('imagem'));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar a imagem: ' . $e->getMessage()
                ]);
            }
        }
    
        // Salva a ocorrência atualizada no banco de dados
        $ocorrencia->save();
    
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

    // Função para verificar se o usuário tem permissão
    private function hasPermission(Ocorrencia $ocorrencia)
    {
        return $ocorrencia->user_id === auth()->id() || auth()->user()->is_admin;
    }
}
