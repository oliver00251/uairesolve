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

        $categorias = Categoria::whereIn('nome', ['Ajuda', 'Causa Animal', 'Sugestão', 'Eventos', 'Denúncias', 'Vagas de emprego', 'Reclamação'])
            ->orderBy('nome', 'asc')
            ->get();

        return view('ocorrencias.create', compact('categorias')); // Passa o tipo para a view
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
            'imagem' => 'nullable|image|mimes:jpeg,png,gif|max:20480', // Limite de 20MB
            'link' => 'nullable', // Validar o link, se fornecido
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
        $ocorrencia->localizacao = $request->input('localizacao');
        $ocorrencia->status = $request->input('status') == 'Excluir' ? 'Arquivada' : $request->input('status');

        // Garantir que tipo e categoria_id sejam iguais
        $categoria = Categoria::find($request->input('tipo'));
        $ocorrencia->tipo = $request->input('tipo');
        $ocorrencia->categoria_id = $request->input('tipo'); // Atualiza o ID da categoria

        // Atualizar o link, se fornecido
        if ($request->filled('link')) {
            // Verifica se já existe um link associado
            $link = Link::find($ocorrencia->link_id);
            if ($link) {
                // Se o link existir, atualiza a URL
                $link->url = $request->input('link');
                $link->save();
            } else {
                // Se não existir, cria um novo link
                $link = new Link();
                $link->url = $request->input('link');
                $link->categoria_id = $categoria->id;
                $link->descricao = $categoria->descricao;
                $link->save();

                // Associa o link criado à ocorrência
                $ocorrencia->link_id = $link->id;
            }
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

    public function gerarImagem($id=1)
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
        $descricao = strip_tags($ocorrencia->descricao); // Remove HTML para evitar problemas
        $dataPublicacao = "\nPublicado em: \n" . $ocorrencia->created_at->format('d/m/Y');
        $site = "Veja a publicação completa em:\n" . "uairesolve.com.br\n". $dataPublicacao;
        
        
        
        // Limitar descrição a no máximo 50 palavras
        $palavras = explode(' ', $descricao);
        if (count($palavras) > 29) {
            $descricao = implode(' ', array_slice($palavras, 0, 45)) . '...';
        }

        // Quebrar a descrição em linhas de até 40 caracteres
        $descricaoQuebrada = wordwrap($descricao, 35, "\n");
        // Quebrar o titulo em linhas de até 3 caracteres
        $titulo = wordwrap($titulo, 20, "\n");

        // Posicionar os textos na imagem
        imagettftext($imagem, 32, 0, 350, 140, $corAzul, $fonte, $categoria);
        imagettftext($imagem, 19, 0, 350, 170, $corAzul, $fonte, $titulo);

        // Desenhar a descrição quebrada
        $posY = 240;
        foreach (explode("\n", $descricaoQuebrada) as $linha) {
            imagettftext($imagem, 20, 0, 350, $posY, $corPreto, $fonte, $linha);
            $posY += 30; // Ajuste para a próxima linha
        }

        // Adicionar site
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
        return response()->download($caminhoSaida, 'ocorrencia_'.$id.'.png');

    }


}
