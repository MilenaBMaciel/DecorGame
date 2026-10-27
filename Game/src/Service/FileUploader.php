<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Salva as imagens enviadas pelo admin em public/uploads/<pasta>/
 * e devolve só o nome do arquivo, que é o que fica guardado no banco.
 */
class FileUploader
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads')]
        private string $uploadsDirectory,
        private SluggerInterface $slugger,
        private Filesystem $filesystem,
    ) {
    }

    public function upload(UploadedFile $file, string $folder): string
    {
        // "Sofá Rosa.png" vira "sofa-rosa-6720f1c2a3b4d.png" (sem acentos/espaços e sem repetir nomes)
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalName)->lower().'-'.uniqid().'.'.$file->guessExtension();

        $file->move($this->uploadsDirectory.'/'.$folder, $fileName);

        return $fileName;
    }

    public function remove(string $folder, ?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = $this->uploadsDirectory.'/'.$folder.'/'.$fileName;

        if ($this->filesystem->exists($path)) {
            $this->filesystem->remove($path);
        }
    }
}
