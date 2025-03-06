<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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

    // Ocorrências criadas pelo usuário
    $minhasOcorrencias = Ocorrencia::where('user_id', $user->id)->latest()->get();

    // Ocorrências onde o usuário comentou
    $ocorrenciasComentadas = Ocorrencia::whereHas('comentarios', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->latest()->get();

    return view('auth.dashboard', compact('user', 'minhasOcorrencias', 'ocorrenciasComentadas'));
}
}
