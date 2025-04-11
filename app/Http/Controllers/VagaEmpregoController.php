<?php

namespace App\Http\Controllers;

use App\Models\VagaEmprego;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Throwable;

class VagaEmpregoController extends Controller
{
    /**
     * Exibir uma lista das vagas de emprego.
     */
    public function index()
    {
        $vagas = VagaEmprego::orderBy('created_at', 'desc')->get();
        return view('vagas.index', compact('vagas'));
    }

    /**
     * Exibir o formulário para criar uma nova vaga de emprego.
     */
    public function create()
    {
        return view('vagas.create');
    }

    /**
     * Armazenar uma nova vaga de emprego no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'localizacao' => 'nullable|string',
            'modalidade' => 'nullable|string',
            'periodo' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'quantidade' => 'nullable|integer',
            'requisitos' => 'nullable|string',
            'beneficios' => 'nullable|string',
            'origem' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        // Criar a vaga no banco
        $vaga = VagaEmprego::create($request->all());

        // Retornar resposta em JSON
        return response()->json([
            'message' => 'Vaga criada com sucesso!',
            'vaga' => $vaga
        ], 201);  // 201 indica que a vaga foi criada com sucesso
    }

    /**
     * Exibir os detalhes de uma vaga de emprego específica.
     */
    public function show($id)
    {
        // Busca a vaga pelo ID
        $vaga = VagaEmprego::findOrFail($id);


        // Retorna a view de detalhes, passando a vaga
        return view('vagas.show', compact('vaga'));
    }


    /**
     * Exibir o formulário para editar uma vaga de emprego.
     */
    public function edit(VagaEmprego $vaga)
    {
        return view('vagas.edit', compact('vaga'));
    }

    /**
     * Atualizar os dados de uma vaga de emprego no banco de dados.
     */
    public function update(Request $request, VagaEmprego $vaga)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'localizacao' => 'nullable|string',
            'modalidade' => 'nullable|string',
            'periodo' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'quantidade' => 'nullable|integer',
            'requisitos' => 'nullable|string',
            'beneficios' => 'nullable|string',
            'origem' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        $vaga->update($request->all());

        return redirect()->route('vagas.index')->with('success', 'Vaga de emprego atualizada com sucesso!');
    }

    /**
     * Deletar uma vaga de emprego do banco de dados.
     */
    public function destroy(VagaEmprego $vaga)
    {
        $vaga->delete();

        return redirect()->route('vagas.index')->with('success', 'Vaga de emprego deletada com sucesso!');
    }

}