<?php

namespace Tests\Unit;

use App\Support\PeruUbigeoCatalog;
use Tests\TestCase;

class PeruUbigeoCatalogTest extends TestCase
{
    private string $csvPath;

    protected function setUp(): void
    {
        parent::setUp();

        $directory = storage_path('framework/testing');
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $this->csvPath = $directory.DIRECTORY_SEPARATOR.'peru-ubigeos-test.csv';

        file_put_contents($this->csvPath, implode("\n", [
            '"cod_dep_inei","desc_dep_inei","cod_prov_inei","desc_prov_inei","cod_ubigeo_inei","desc_ubigeo_inei"',
            '"01","AMAZONAS","0101","CHACHAPOYAS","010101","CHACHAPOYAS"',
            '"01","AMAZONAS","0101","CHACHAPOYAS","010102","ASUNCION"',
            '"01","AMAZONAS","0102","BAGUA","010201","ARAMANGO"',
            '"02","ANCASH","0201","HUARAZ","020101","HUARAZ"',
        ]));

        config()->set('ubicaciones.peru_ubigeos_csv', $this->csvPath);
    }

    protected function tearDown(): void
    {
        if (is_file($this->csvPath)) {
            @unlink($this->csvPath);
        }

        parent::tearDown();
    }

    public function test_it_loads_departamentos_provincias_and_distritos(): void
    {
        $catalog = new PeruUbigeoCatalog();

        $this->assertSame(['AMAZONAS', 'ANCASH'], $catalog->departamentos());
        $this->assertSame(['CHACHAPOYAS', 'BAGUA'], $catalog->provincias('AMAZONAS'));
        $this->assertSame(['CHACHAPOYAS', 'ASUNCION'], $catalog->distritos('AMAZONAS', 'CHACHAPOYAS'));
    }

    public function test_it_normalizes_input_values_before_validation(): void
    {
        $catalog = new PeruUbigeoCatalog();

        $this->assertSame('AMAZONAS', $catalog->resolveDepartamento(' amazonas '));
        $this->assertSame('CHACHAPOYAS', $catalog->resolveProvincia('AMAZONAS', '  chachapoyas   '));
        $this->assertSame('ASUNCION', $catalog->resolveDistrito('AMAZONAS', 'CHACHAPOYAS', '   asuncion  '));
    }

    public function test_it_rejects_invalid_combinations(): void
    {
        $catalog = new PeruUbigeoCatalog();

        $this->assertNull($catalog->resolveDepartamento('PASCO'));
        $this->assertNull($catalog->resolveProvincia('AMAZONAS', 'HUARAZ'));
        $this->assertNull($catalog->resolveDistrito('AMAZONAS', 'BAGUA', 'CHACHAPOYAS'));
    }

    public function test_it_converts_non_utf8_csv_values_for_frontend_json(): void
    {
        $latinPath = storage_path('framework/testing/peru-ubigeos-latin1-test.csv');
        $latinProvincia = mb_convert_encoding('PROVINCÍA', 'ISO-8859-1', 'UTF-8');
        $latinDistrito = mb_convert_encoding('DISTRITÓ', 'ISO-8859-1', 'UTF-8');

        file_put_contents($latinPath, implode("\n", [
            '"cod_dep_inei","desc_dep_inei","cod_prov_inei","desc_prov_inei","cod_ubigeo_inei","desc_ubigeo_inei"',
            "\"99\",\"DEP TEST\",\"9901\",\"{$latinProvincia}\",\"990101\",\"{$latinDistrito}\"",
        ]));

        config()->set('ubicaciones.peru_ubigeos_csv', $latinPath);

        $catalog = new PeruUbigeoCatalog();

        try {
            $this->assertSame('PROVINCÍA', $catalog->resolveProvincia('DEP TEST', 'provincia'));
            $this->assertNotFalse(json_encode($catalog->hierarchy(), JSON_UNESCAPED_UNICODE));
        } finally {
            if (is_file($latinPath)) {
                @unlink($latinPath);
            }
        }
    }
}
