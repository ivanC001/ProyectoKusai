<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class SupportContentRepository
{
    /**
     * @return array<string, string>
     */
    public function pages(): array
    {
        return [
            'soporte' => 'Portada de soporte',
            'centro-ayuda' => 'Centro de ayuda',
            'terminos-condiciones' => 'Terminos y condiciones',
            'politica-privacidad' => 'Politica de privacidad',
            'terminos-legales' => 'Terminos legales',
        ];
    }

    /**
     * @return array{
     *   slug: string,
     *   title: string,
     *   updated_at: string,
     *   summary: string,
     *   sections: array<int, array{
     *     title: string,
     *     paragraphs: array<int, string>,
     *     bullets: array<int, string>
     *   }>
     * }
     */
    public function load(string $slug): array
    {
        $path = $this->resolvePath($slug);
        if ($path === null) {
            abort(404);
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        if (! is_array($decoded)) {
            abort(500, 'No se pudo leer el contenido de soporte.');
        }

        return $decoded;
    }

    public function save(string $slug, array $payload): void
    {
        $directory = storage_path('app/support-pages');
        if (! is_dir($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $this->storagePath($slug);

        file_put_contents(
            $path,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function resolvePath(string $slug): ?string
    {
        $storagePath = $this->storagePath($slug);
        if (is_file($storagePath)) {
            return $storagePath;
        }

        $resourcePath = resource_path('data/support/'.$slug.'.json');
        if (is_file($resourcePath)) {
            return $resourcePath;
        }

        return null;
    }

    private function storagePath(string $slug): string
    {
        return storage_path('app/support-pages/'.$slug.'.json');
    }
}
