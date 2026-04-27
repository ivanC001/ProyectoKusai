<?php

namespace App\Support;

use Illuminate\Support\Str;
use RuntimeException;

class PeruUbigeoCatalog
{
    /**
     * @var array<string, array{name: string, provincias: array<string, array{name: string, distritos: array<string, string>}>}>|null
     */
    private ?array $catalog = null;

    /**
     * @return array<int, string>
     */
    public function departamentos(): array
    {
        return array_values(array_map(
            static fn (array $departamento): string => $departamento['name'],
            $this->catalog()
        ));
    }

    /**
     * @return array<int, string>
     */
    public function provincias(string $departamento): array
    {
        $departamentoData = $this->catalog()[$this->normalize($departamento)] ?? null;

        if ($departamentoData === null) {
            return [];
        }

        return array_values(array_map(
            static fn (array $provincia): string => $provincia['name'],
            $departamentoData['provincias']
        ));
    }

    /**
     * @return array<int, string>
     */
    public function distritos(string $departamento, string $provincia): array
    {
        $provinciaData = $this->findProvincia($departamento, $provincia);

        if ($provinciaData === null) {
            return [];
        }

        return array_values($provinciaData['distritos']);
    }

    public function resolveDepartamento(string $departamento): ?string
    {
        $departamentoData = $this->catalog()[$this->normalize($departamento)] ?? null;

        return $departamentoData['name'] ?? null;
    }

    public function resolveProvincia(string $departamento, string $provincia): ?string
    {
        $provinciaData = $this->findProvincia($departamento, $provincia);

        return $provinciaData['name'] ?? null;
    }

    public function resolveDistrito(string $departamento, string $provincia, string $distrito): ?string
    {
        $provinciaData = $this->findProvincia($departamento, $provincia);

        if ($provinciaData === null) {
            return null;
        }

        $distritoKey = $this->normalize($distrito);

        return $provinciaData['distritos'][$distritoKey] ?? null;
    }

    /**
     * @return array<int, array{departamento: string, provincias: array<int, array{provincia: string, distritos: array<int, string>}>}>
     */
    public function hierarchy(): array
    {
        $hierarchy = [];

        foreach ($this->catalog() as $departamentoData) {
            $provincias = [];

            foreach ($departamentoData['provincias'] as $provinciaData) {
                $provincias[] = [
                    'provincia' => $provinciaData['name'],
                    'distritos' => array_values($provinciaData['distritos']),
                ];
            }

            $hierarchy[] = [
                'departamento' => $departamentoData['name'],
                'provincias' => $provincias,
            ];
        }

        return $hierarchy;
    }

    /**
     * @return array{name: string, distritos: array<string, string>}|null
     */
    private function findProvincia(string $departamento, string $provincia): ?array
    {
        $departamentoData = $this->catalog()[$this->normalize($departamento)] ?? null;

        if ($departamentoData === null) {
            return null;
        }

        $provinciaKey = $this->normalize($provincia);

        return $departamentoData['provincias'][$provinciaKey] ?? null;
    }

    /**
     * @return array<string, array{name: string, provincias: array<string, array{name: string, distritos: array<string, string>}>}>
     */
    private function catalog(): array
    {
        if ($this->catalog !== null) {
            return $this->catalog;
        }

        $path = config('ubicaciones.peru_ubigeos_csv', database_path('data/equivalencia-ubigeos-oti-concytec.csv'));

        if (! is_string($path) || $path === '' || ! is_file($path)) {
            throw new RuntimeException('No se encontro el archivo CSV de ubigeos de Peru.');
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('No se pudo abrir el archivo CSV de ubigeos de Peru.');
        }

        $catalog = [];

        try {
            $header = fgetcsv($handle);

            if ($header === false) {
                return $this->catalog = [];
            }

            $header = array_map(fn (string $value): string => $this->cleanHeader($value), $header);

            $departamentoIndex = array_search('desc_dep_inei', $header, true);
            $provinciaIndex = array_search('desc_prov_inei', $header, true);
            $distritoIndex = array_search('desc_ubigeo_inei', $header, true);

            if (! is_int($departamentoIndex) || ! is_int($provinciaIndex) || ! is_int($distritoIndex)) {
                throw new RuntimeException('El CSV de ubigeos no tiene las columnas esperadas.');
            }

            while (($row = fgetcsv($handle)) !== false) {
                $departamentoName = $this->cleanValue($row[$departamentoIndex] ?? null);
                $provinciaName = $this->cleanValue($row[$provinciaIndex] ?? null);
                $distritoName = $this->cleanValue($row[$distritoIndex] ?? null);

                if ($departamentoName === '' || $provinciaName === '' || $distritoName === '') {
                    continue;
                }

                $departamentoKey = $this->normalize($departamentoName);
                $provinciaKey = $this->normalize($provinciaName);
                $distritoKey = $this->normalize($distritoName);

                $catalog[$departamentoKey] ??= [
                    'name' => $departamentoName,
                    'provincias' => [],
                ];

                $catalog[$departamentoKey]['provincias'][$provinciaKey] ??= [
                    'name' => $provinciaName,
                    'distritos' => [],
                ];

                $catalog[$departamentoKey]['provincias'][$provinciaKey]['distritos'][$distritoKey] = $distritoName;
            }
        } finally {
            fclose($handle);
        }

        return $this->catalog = $catalog;
    }

    private function cleanHeader(string $value): string
    {
        return trim(str_replace("\xEF\xBB\xBF", '', $value));
    }

    private function cleanValue(?string $value): string
    {
        if (! is_string($value)) {
            return '';
        }

        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (function_exists('mb_check_encoding') && ! mb_check_encoding($value, 'UTF-8')) {
            $value = $this->convertToUtf8($value);
        }

        if (function_exists('iconv')) {
            $cleaned = iconv('UTF-8', 'UTF-8//IGNORE', $value);
            if (is_string($cleaned)) {
                $value = $cleaned;
            }
        }

        return trim($value);
    }

    private function convertToUtf8(string $value): string
    {
        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($value, 'UTF-8', 'UTF-8,Windows-1252,ISO-8859-1');
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('Windows-1252', 'UTF-8//IGNORE', $value);
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        return $value;
    }

    private function normalize(string $value): string
    {
        $normalized = Str::of($value)
            ->trim()
            ->replaceMatches('/\s+/u', ' ')
            ->ascii()
            ->upper()
            ->toString();

        return $normalized;
    }
}
