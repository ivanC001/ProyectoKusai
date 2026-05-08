<?php

namespace App\Http\Controllers;

use App\Models\PortalVisita;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Visita;
use App\Support\SupportContentRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTipoPropiedadController extends Controller
{
    public function __construct(
        private readonly SupportContentRepository $supportContentRepository
    ) {
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $tiposPropiedad = TipoPropiedad::query()
            ->withCount('propiedades')
            ->orderBy('nombre')
            ->get();

        $metricas = [
            'tipos_total' => $tiposPropiedad->count(),
            'propiedades_asociadas' => (int) $tiposPropiedad->sum('propiedades_count'),
            'visitas_portal_total' => PortalVisita::query()->count(),
            'clics_propiedades_total' => Visita::query()->count(),
        ];

        $topPublicaciones = Propiedad::query()
            ->with(['usuario:id,name,apellidos'])
            ->withCount(['visitas', 'favoritos'])
            ->orderByDesc('visitas_count')
            ->orderByDesc('favoritos_count')
            ->latest()
            ->limit(4)
            ->get();

        return view('admin.PanelAdministrativo.index', [
            'tiposPropiedad' => $tiposPropiedad,
            'metricas' => $metricas,
            'topPublicaciones' => $topPublicaciones,
        ]);
    }

    public function support(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.PanelAdministrativo.support', $this->buildSupportEditorData($request));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120', 'unique:tipos_propiedad,nombre'],
        ]);

        TipoPropiedad::create([
            'nombre' => trim($validated['nombre']),
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno registrado correctamente.');
    }

    public function update(Request $request, TipoPropiedad $tipoPropiedad): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120', 'unique:tipos_propiedad,nombre,'.$tipoPropiedad->id],
        ]);

        $tipoPropiedad->update([
            'nombre' => trim($validated['nombre']),
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno actualizado correctamente.');
    }

    public function destroy(Request $request, TipoPropiedad $tipoPropiedad): RedirectResponse
    {
        $this->ensureAdmin($request);

        if ($tipoPropiedad->propiedades()->exists()) {
            return redirect()
                ->route('admin.PanelAdministrativo')
                ->with('error', 'No puedes eliminar este tipo porque tiene publicaciones asociadas.');
        }

        $tipoPropiedad->delete();

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno eliminado correctamente.');
    }

    public function updateSupport(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $pages = array_keys($this->supportContentRepository->pages());

        $validated = $request->validate([
            'support_slug' => ['required', Rule::in($pages)],
            'title' => ['required', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:500'],
            'updated_at' => ['required', 'date'],
            'sections_text' => ['required', 'string', 'max:50000'],
            'return_to' => ['nullable', Rule::in(['index', 'support'])],
        ]);

        $slug = $validated['support_slug'];
        $sections = $this->parseSectionsText($validated['sections_text']);
        $targetRoute = ($validated['return_to'] ?? 'support') === 'index'
            ? 'admin.PanelAdministrativo'
            : 'admin.PanelAdministrativo.soporte';

        if (count($sections) === 0) {
            return redirect()
                ->route($targetRoute, ['support_page' => $slug])
                ->with('error', 'Debes ingresar al menos una seccion usando el formato: ## Titulo de seccion.');
        }

        $this->supportContentRepository->save($slug, [
            'slug' => $slug,
            'title' => trim($validated['title']),
            'updated_at' => date('Y-m-d', strtotime($validated['updated_at'])),
            'summary' => trim($validated['summary']),
            'sections' => $sections,
        ]);

        return redirect()
            ->route($targetRoute, ['support_page' => $slug])
            ->with('success', 'Contenido de soporte actualizado correctamente.');
    }

    /**
     * @return array{
     *   supportPages: array<string, string>,
     *   selectedSupportPage: string,
     *   supportPayload: array<string, mixed>,
     *   supportSectionsText: string
     * }
     */
    private function buildSupportEditorData(Request $request): array
    {
        $supportPages = $this->supportContentRepository->pages();
        $selectedSupportPage = (string) $request->query('support_page', 'terminos-condiciones');
        if (! array_key_exists($selectedSupportPage, $supportPages)) {
            $selectedSupportPage = 'terminos-condiciones';
        }

        $supportPayload = $this->supportContentRepository->load($selectedSupportPage);

        return [
            'supportPages' => $supportPages,
            'selectedSupportPage' => $selectedSupportPage,
            'supportPayload' => $supportPayload,
            'supportSectionsText' => $this->buildSectionsText($supportPayload['sections'] ?? []),
        ];
    }

    /**
     * @param  array<int, array{title?: mixed, paragraphs?: mixed, bullets?: mixed}>  $sections
     */
    private function buildSectionsText(array $sections): string
    {
        $chunks = [];

        foreach ($sections as $section) {
            $title = trim((string) ($section['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $lines = ['## '.$title];

            $paragraphs = is_array($section['paragraphs'] ?? null) ? $section['paragraphs'] : [];
            foreach ($paragraphs as $paragraph) {
                $paragraphText = trim((string) $paragraph);
                if ($paragraphText !== '') {
                    $lines[] = $paragraphText;
                }
            }

            $bullets = is_array($section['bullets'] ?? null) ? $section['bullets'] : [];
            foreach ($bullets as $bullet) {
                $bulletText = trim((string) $bullet);
                if ($bulletText !== '') {
                    $lines[] = '- '.$bulletText;
                }
            }

            $chunks[] = implode("\n", $lines);
        }

        return implode("\n\n", $chunks);
    }

    /**
     * @return array<int, array{title: string, paragraphs: array<int, string>, bullets: array<int, string>}>
     */
    private function parseSectionsText(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $sections = [];
        $current = null;

        foreach ($lines as $line) {
            $trimmed = trim((string) $line);

            if ($trimmed === '') {
                continue;
            }

            if (str_starts_with($trimmed, '##')) {
                if ($current !== null && $current['title'] !== '') {
                    $sections[] = $current;
                }

                $title = trim((string) preg_replace('/^##\s*/', '', $trimmed));
                $current = [
                    'title' => $title,
                    'paragraphs' => [],
                    'bullets' => [],
                ];

                continue;
            }

            if ($current === null) {
                $current = [
                    'title' => 'General',
                    'paragraphs' => [],
                    'bullets' => [],
                ];
            }

            if (str_starts_with($trimmed, '- ')) {
                $bullet = trim(substr($trimmed, 2));
                if ($bullet !== '') {
                    $current['bullets'][] = $bullet;
                }
            } else {
                $current['paragraphs'][] = $trimmed;
            }
        }

        if ($current !== null && $current['title'] !== '') {
            $sections[] = $current;
        }

        return $sections;
    }

    private function ensureAdmin(Request $request): void
    {
        $user = $request->user();

        if ($user === null || ! $user->esAdmin()) {
            abort(403);
        }
    }
}
