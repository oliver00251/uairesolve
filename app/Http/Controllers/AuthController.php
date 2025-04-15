<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

  // Método para redirecionar para o Google
  public function redirectToGoogle()
  {
      return Socialite::driver('google')->redirect();
  }

  // Método para receber o callback do Google e autenticar o usuário
  public function handleGoogleCallback()
  {
      try {
          // Obtém os dados do usuário retornado pelo Google
          $googleUser = Socialite::driver('google')->user();
        

          // Verifica se o usuário já existe na base de dados
          $user = User::where('provider', 'google')
                      ->where('provider_id', $googleUser->getId())
                      ->first();

          // Se o usuário não existe, cria um novo usuário
          if (!$user) {
              $user = User::create([
                  'name' => $googleUser->getName(),
                  'email' => $googleUser->getEmail(),
                  'provider' => 'google',
                  'provider_id' => $googleUser->getId(),
              ]);
          }

          // Autentica o usuário
          Auth::login($user, true);

          // Redireciona para a página inicial ou qualquer outra
          return redirect()->route('home'); // Aqui, você pode definir a rota que o usuário será redirecionado após o login.
      } catch (\Exception $e) {
          // Caso ocorra algum erro, redireciona com uma mensagem de erro
          return redirect('/')->with('error', 'Erro ao tentar fazer login com o Google.');
      }
  }
    /**
     * Exibir a tela de login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Processar login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        throw ValidationException::withMessages([
            'email' => 'As credenciais fornecidas estão incorretas.',
        ]);
    }

    /**
     * Exibir a tela de registro.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Processar registro de usuário.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Fazer logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Você saiu da sua conta.');
    }

    //dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $usuariosAtivos = User::orderBy('name')->get(); 
        // Verifica se o usuário é admin
        $isAdmin = $user->tipo === 'admin';

        // Ocorrências para o usuário
        $minhasOcorrencias = Ocorrencia::where('user_id', $user->id)
            ->latest()
            ->whereIn('status', ['Aberta', 'Em andamento', 'Resolvida'])
            ->get();

        // Ocorrências onde o usuário comentou
        $ocorrenciasComentadas = Ocorrencia::whereHas('comentarios', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();

        // Para o administrador
        $ocorrencias = $isAdmin ? Ocorrencia::latest()->get() : collect(); // Lista todas as ocorrências para o admin

        // Métricas para o administrador
        $totalOcorrencias = null;
        $ocorrenciasAbertas = null;
        $ocorrenciasResolvidas = null;
        $ocorrenciasEmAndamento = null;

        if ($isAdmin) {
            $totalOcorrencias = Ocorrencia::count(); // Total de ocorrências
            $ocorrenciasAbertas = Ocorrencia::where('status', 'Aberta')->count(); // Ocorrências abertas
            $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvida')->count(); // Ocorrências resolvidas
            $ocorrenciasEmAndamento = Ocorrencia::where('status', 'Em andamento')->count(); // Ocorrências em andamento
        }

        // Passa as variáveis para a view
        return view('auth.dashboard', compact(
            'user',
            'minhasOcorrencias',
            'ocorrenciasComentadas',
            'ocorrencias',
            'totalOcorrencias',
            'usuariosAtivos',
            'ocorrenciasAbertas',
            'ocorrenciasResolvidas',
            'ocorrenciasEmAndamento',
            'isAdmin' // Passa se é admin ou não
        ));
    }
}
