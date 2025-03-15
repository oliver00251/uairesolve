<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Inicializa o ImageManager com GD
        $this->manager = new ImageManager(new Driver());
    }

    public function processAndSave(UploadedFile $file, $folder = 'ocorrencias', $width = 800, $height = 600)
    {
        // Lê a imagem original
        $image = $this->manager->read($file->getRealPath());

        // Redimensiona mantendo proporção
        $image->cover($width, $height);

        // Define o nome do arquivo
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        // Caminho no storage
        $path = "$folder/$filename";

        // Salva a imagem processada no storage
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }
}
