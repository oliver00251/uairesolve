<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Parceiro;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Parceiro $parceiro)
    {
        return view('posts.create', compact('parceiro'));
    }

    public function store(Request $request, Parceiro $parceiro)
    {
        // Validação dos dados
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'imagem' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Criação da postagem
        $post = new Post();
        $post->titulo = $validated['titulo'];
        $post->conteudo = $validated['conteudo'];
        $post->parceiro_id = $parceiro->id;

        // Se houver imagem, salva no diretório correto
        if ($request->hasFile('imagem')) {
            $imagePath = $request->file('imagem')->store('postagens', 'public');
            $post->imagem = $imagePath;
        }

        $post->save();

        // Redireciona para o perfil do parceiro com uma mensagem de sucesso
        return redirect()->route('parceiros.show', $parceiro->id)
            ->with('success', 'Postagem criada com sucesso!');
    }

    public function destroy($id)
    {
        // Encontre a postagem pelo ID
        $post = Post::findOrFail($id);

        // Apagar a postagem
        $post->delete();

        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Postagem excluída com sucesso!');
    }

    public function edit($id)
{
    // Encontre a postagem pelo ID
    $postagem = Post::findOrFail($id);

    // Retorne a view de edição passando a postagem
    return view('posts.edit', compact('postagem'));
}

public function update(Request $request, $id)
    {
        // Validação
        $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'imagem' => 'nullable|image|max:2048',
        ]);

        // Encontrar a postagem
        $postagem = Post::findOrFail($id);

        // Atualizar os campos da postagem
        $postagem->titulo = $request->titulo;
        $postagem->conteudo = $request->conteudo;

        // Se houver imagem, processar o upload
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('posts', 'public');
            $postagem->imagem = $path;
        }

        // Salvar a postagem
        $postagem->save();

        // Redirecionar de volta
        return redirect()->route('parceiros.show', $postagem->parceiro_id)
                         ->with('success', 'Postagem atualizada com sucesso!');
    }

}
