<?php

namespace App\Http\Controllers;

use App\Support\SupportContentRepository;
use Illuminate\View\View;

class SupportPageController extends Controller
{
    public function __construct(
        private readonly SupportContentRepository $supportContentRepository
    ) {
    }

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
        $payload = $this->supportContentRepository->load($slug);

        return view('portal.support', [
            'payload' => $payload,
            'title' => $payload['title'] ?? $fallbackTitle,
        ]);
    }
}
