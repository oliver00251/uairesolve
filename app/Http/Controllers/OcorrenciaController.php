<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cidade;
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

    public function index(Request $request)
    {
        $categoriaId = $request->query('categoria');
        $status = $request->query('status');

        $ocorrencias = Ocorrencia::where('status', '!=', 'Arquivada')
            ->when($categoriaId, function ($query) use ($categoriaId) {
                return $query->where('categoria_id', $categoriaId);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with('categoria', 'ultimoStatusLog')
            ->latest()
            ->get();

        $categorias = Categoria::whereIn('nome', ['Ajuda', 'Sugestão', 'Denúncias', 'Reclamação'])
            ->orderBy('nome', 'asc')
            ->get();

        $statusList = ['Aberta', 'Em andamento', 'Resolvida']; // ou busca direto do Enum/Model

        return view('ocorrencias.index', [
            'ocorrencias' => $ocorrencias,
            'categorias' => $categorias,
            'statusList' => $statusList,
            'categoriaId' => $categoriaId,
            'status' => $status,
            'filtro' => $categoriaId // pra não quebrar o que já tá usando $filtro na view
        ]);
    }


    public function create()
    {
        $categorias = Categoria::whereIn('nome', ['Ajuda','Sugestão','Denúncias', 'Reclamação'])
            ->orderBy('nome', 'asc')
            ->get();
        $cidades = Cidade::get();

        return view('ocorrencias.create', compact('categorias', 'cidades')); // Passa o tipo para a view
    }
    public function store(Request $request)
    {
       $teste = $request->all();
        // Validação de dados
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'localizacao' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'imagem' => 'nullable|image|mimes:jpeg,png,gif|max:20480', // 20MB
            'link' => 'nullable|url|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'cidade_id' => 'required|exists:cidades,id',
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Busca a categoria
        $categoria = Categoria::find($request->input('categoria_id'));

        // Cria a ocorrência
        $ocorrencia = new Ocorrencia();
        $ocorrencia->titulo = $request->input('titulo');
        $ocorrencia->descricao = $request->input('descricao');
        $ocorrencia->localizacao = $request->input('localizacao');
        $ocorrencia->user_id = auth()->id();
        $ocorrencia->categoria_id = $categoria->id;
        $ocorrencia->tipo = 1;
        $ocorrencia->cidade_id = $request->input('cidade_id');
        $ocorrencia->bairro_id = 1;

        // Se houver link
        if ($request->filled('link')) {
            $link = new Link();
            $link->url = $request->input('link');
            $link->categoria_id = $categoria->id;
            $link->descricao = $categoria->descricao;
            $link->save();

            $ocorrencia->link_id = $link->id;
        }

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

        // Salva a ocorrência
        $ocorrencia->save();

        // Registra localização, se enviada
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
        // Carregar os relacionamentos necessários (comentários e link)
        $ocorrencia->load('comentarios', 'link');

        // Definir o título baseado no tipo
        $tituloOcorrencia = [
            'O' => 'Ocorrência',
            'S' => 'Sugestão',
            'I' => 'Indicação',
            'D' => 'Denúncia',
            'outro' => 'Outro Tipo',
        ][$ocorrencia->tipo] ?? 'Outro Tipo';

        // Contar os likes
        $totalLikes = $ocorrencia->likes()->count();

        // Retornar a view com os dados necessários
        return view('ocorrencias.show', compact('ocorrencia', 'tituloOcorrencia', 'totalLikes'));
    }


    public function edit(Ocorrencia $ocorrencia)
    {
        $categorias = Categoria::whereIn('nome', ['Ajuda', 'Causa Animal', 'Sugestão', 'Eventos', 'Denúncias', 'Vagas de emprego', 'Reclamação'])
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
            'localizacao' => 'nullable|string',
            'status' => 'required|in:Aberta,Em andamento,Resolvida,Excluir',
            'imagem' => 'nullable|image|mimes:jpeg,png,gif|max:20480', // 20MB
            'link' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Atualiza os campos padrão
        $ocorrencia->titulo = $request->input('titulo');
        $ocorrencia->descricao = $request->input('descricao');
        $ocorrencia->localizacao = $request->input('localizacao');

        // Atualiza tipo e categoria
        $categoria = Categoria::find($request->input('tipo'));
        $ocorrencia->tipo = $request->input('tipo');
        $ocorrencia->categoria_id = $request->input('tipo');

        // Atualiza ou cria o link
        if ($request->filled('link')) {
            $link = Link::find($ocorrencia->link_id);

            if ($link) {
                $link->url = $request->input('link');
                $link->save();
            } else {
                $link = new Link();
                $link->url = $request->input('link');
                $link->categoria_id = $categoria->id;
                $link->descricao = $categoria->descricao;
                $link->save();

                $ocorrencia->link_id = $link->id;
            }
        }

        // Processa imagem
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

        // LOGICA DE STATUS: agora com log automático
        $novoStatus = $request->input('status') == 'Excluir' ? 'Arquivada' : $request->input('status');
        $ocorrencia->logStatusChange($novoStatus, 'Status alterado via painel', auth()->id());

        // Salva as demais alterações no banco
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

    public function gerarImagem($id = 1)
    {
        $ocorrencia = Ocorrencia::find($id);

        if (!$ocorrencia) {
            return response()->json(['error' => 'Ocorrência não encontrada!'], 404);
        }

        // Caminho da imagem base
        $imagemBase = public_path('images/img_modelo.png');

        if (!file_exists($imagemBase)) {
            return response()->json(['error' => 'Imagem base não encontrada!'], 404);
        }

        $imagem = imagecreatefrompng($imagemBase);

        // Definir cores do texto
        $corAzul = imagecolorallocate($imagem, 0, 91, 187);
        $corPreto = imagecolorallocate($imagem, 50, 50, 50);

        // Definir a fonte
        $fonte = public_path('fonts/ARIAL.TTF');

        if (!file_exists($fonte)) {
            return response()->json(['error' => 'Fonte não encontrada!'], 404);
        }

        // Textos dinâmicos
        $categoria = mb_strtoupper($ocorrencia->categoria->nome ?? 'Outros', 'UTF-8');
        $titulo = mb_strtoupper($ocorrencia->titulo, 'UTF-8');
        $descricao = strip_tags($ocorrencia->descricao);
        $descricao = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $descricao); // Remove emojis e caracteres não suportados        
        $dataPublicacao = "\nPublicado em: \n" . $ocorrencia->created_at->format('d/m/Y');
        $site = "Veja a publicação completa em:\n" . "uairesolve.com.br\n" . $dataPublicacao;

        // Limitar descrição a no máximo 50 palavras
        $palavras = explode(' ', $descricao);
        if (count($palavras) > 29) {
            $descricao = implode(' ', array_slice($palavras, 0, 45)) . '...';
        }

        // Quebrar o título e a descrição corretamente
        $tituloQuebrado = wordwrap($titulo, 30, "\n", true);
        $descricaoQuebrada = wordwrap($descricao, 35, "\n", true);

        // Posições iniciais
        imagettftext($imagem, 32, 0, 350, 140, $corAzul, $fonte, $categoria);

        // Renderizar o título quebrado
        $posY = 170;
        foreach (explode("\n", $tituloQuebrado) as $linha) {
            imagettftext($imagem, 17, 0, 350, $posY, $corAzul, $fonte, $linha);
            $posY += 25; // Ajuste de espaçamento
        }

        // Renderizar a descrição com quebra de linha
        $posY += 30; // Ajuste para dar espaço entre o título e a descrição
        foreach (explode("\n", $descricaoQuebrada) as $linha) {
            imagettftext($imagem, 20, 0, 350, $posY, $corPreto, $fonte, $linha);
            $posY += 30; // Ajuste para a próxima linha
        }

        // Adicionar o site
        imagettftext($imagem, 16, 0, 350, $posY + 10, $corPreto, $fonte, $site);

        // Criar a pasta 'geradas' se não existir
        $caminhoPasta = public_path('geradas');
        if (!file_exists($caminhoPasta)) {
            mkdir($caminhoPasta, 0777, true);
        }

        // Caminho para salvar a imagem final
        $caminhoSaida = $caminhoPasta . '/imagem_final.png';
        imagepng($imagem, $caminhoSaida);
        imagedestroy($imagem);

        // Retornar a URL da imagem gerada
        return response()->download($caminhoSaida, 'ocorrencia_' . $id . '.png');
    }
}
