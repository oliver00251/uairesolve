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


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Verifica se o usuário já existe no banco de dados
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Se o usuário existe, loga ele
                auth()->login($existingUser, true);
            } else {
                // Cria um novo usuário
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    // Outros campos conforme necessário
                ]);

                // Loga o novo usuário
                auth()->login($newUser, true);
            }

            return redirect('/dashboard');  // Redireciona para o dashboard após o login

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao tentar fazer login com o Google.');
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
            'ocorrenciasAbertas', 
            'ocorrenciasResolvidas',
            'ocorrenciasEmAndamento',
            'isAdmin' // Passa se é admin ou não
        ));
    }
    
    
    
}
