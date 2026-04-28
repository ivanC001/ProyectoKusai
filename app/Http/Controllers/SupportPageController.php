<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SupportPageController extends Controller
{
    public function index(): View
    {
        return $this->renderPage('soporte', 'Soporte');
    }

    public function helpCenter(): View
    {
        return $this->renderPage('centro-ayuda', 'Centro de ayuda');
    }

    public function terms(): View
    {
        return $this->renderPage('terminos-condiciones', 'Terminos y condiciones');
    }

    public function privacy(): View
    {
        return $this->renderPage('politica-privacidad', 'Politica de privacidad');
    }

    public function legal(): View
    {
        return $this->renderPage('terminos-legales', 'Terminos legales');
    }

    private function renderPage(string $slug, string $fallbackTitle): View
    {
        $payload = $this->loadPayload($slug);

        return view('portal.support', [
            'payload' => $payload,
            'title' => $payload['title'] ?? $fallbackTitle,
        ]);
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
    private function loadPayload(string $slug): array
    {
        $path = resource_path('data/support/'.$slug.'.json');
        if (! is_file($path)) {
            abort(404);
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        if (! is_array($decoded)) {
            abort(500, 'No se pudo leer el contenido de soporte.');
        }

        return $decoded;
    }
}

