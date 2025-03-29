<?php

namespace App\Http\Controllers;

use App\Helpers\InstagramHelper;
use App\Models\Parceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParceiroController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::all();
        $user = Auth::user();

        // Verifica se o usuário é admin
        $isAdmin = $user->tipo === 'admin';

        return view('parceiros.index', compact('parceiros','isAdmin'));
    }

   public function show(Parceiro $parceiro)
{
    $parceiro->load('posts'); // Carregar os posts relacionados

    // Pegar posts do Instagram automaticamente (se ele tiver um username configurado)
    $instagramPosts = [];
    if ($parceiro->instagram) {
        $instagramPosts = InstagramHelper::getInstagramPosts($parceiro->instagram);
    }

    // Teste fixo para 'adotavaii'
    $instagramPosts = InstagramHelper::getInstagramPosts('adotavaii');

    return view('parceiros.show', compact('parceiro', 'instagramPosts'));
}


    public function create()
    {
        return view('parceiros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = Auth::user();

        //dd( $user->id);
        $parceiro = new Parceiro();
        $parceiro->user_id = Auth::id();
        $parceiro->nome = $request->nome;
        $parceiro->descricao = $request->descricao;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('parceiros', 'public');
            $parceiro->logo = $path;
        }

        $parceiro->save();

        return redirect()->route('parceiros.index')->with('success', 'Parceiro cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Encontrar o parceiro pelo ID
        $parceiro = Parceiro::findOrFail($id);

        // Atualizar os dados
        $parceiro->nome = $request->nome;
        $parceiro->descricao = $request->descricao;

        // Se uma nova logo for enviada, salvar e atualizar
        if ($request->hasFile('logo')) {
            // Excluir a logo antiga, se existir
            if ($parceiro->logo && Storage::exists('public/' . $parceiro->logo)) {
                Storage::delete('public/' . $parceiro->logo);
            }

            // Salvar a nova logo
            $parceiro->logo = $request->file('logo')->store('logos', 'public');
        }

        // Salvar as alterações
        $parceiro->save();

        // Redirecionar com mensagem de sucesso
        return redirect()->route('parceiros.show', $parceiro->id)->with('success', 'Perfil atualizado com sucesso!');
    }
}
