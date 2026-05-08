<?php

namespace Database\Seeders;

use App\Support\SupportContentRepository;
use Illuminate\Database\Seeder;

class SupportTermsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** @var SupportContentRepository $repository */
        $repository = app(SupportContentRepository::class);

        $repository->save('terminos-condiciones', [
            'slug' => 'terminos-condiciones',
            'title' => 'Terminos y condiciones del portal',
            'updated_at' => now()->toDateString(),
            'summary' => 'Condiciones de uso para publicar, buscar y contactar en Kusay.pe.',
            'sections' => [
                [
                    'title' => 'Uso de la plataforma',
                    'paragraphs' => [
                        'Kusay.pe permite publicar y consultar propiedades en venta, alquiler y proyectos inmobiliarios.',
                        'El usuario se compromete a brindar informacion veraz, completa y actualizada en sus publicaciones.',
                    ],
                    'bullets' => [
                        'No publicar informacion falsa o enganosa.',
                        'No suplantar identidad de terceros.',
                        'No usar el portal para actividades ilegales.',
                    ],
                ],
                [
                    'title' => 'Responsabilidad del usuario',
                    'paragraphs' => [
                        'Cada usuario es responsable de validar la identidad de la contraparte y la legalidad de la propiedad antes de concretar una operacion.',
                        'Kusay.pe no participa como intermediario contractual ni custodio de dinero.',
                    ],
                    'bullets' => [
                        'Verificar documentos de propiedad y titularidad.',
                        'Evitar adelantos sin validacion previa.',
                        'Buscar asesoria legal antes de firmar contratos.',
                    ],
                ],
                [
                    'title' => 'Contenido y moderacion',
                    'paragraphs' => [
                        'Kusay.pe puede editar, pausar o eliminar publicaciones que incumplan las politicas del portal o la normativa vigente.',
                    ],
                    'bullets' => [
                        'Publicaciones con contenido ofensivo o fraudulento.',
                        'Uso indebido del sistema de contacto.',
                        'Reincidencia en incumplimientos reportados.',
                    ],
                ],
                [
                    'title' => 'Proteccion y privacidad',
                    'paragraphs' => [
                        'Los datos personales son tratados segun la politica de privacidad del portal y la normativa aplicable.',
                        'El usuario autoriza el uso de su informacion para funciones operativas, de seguridad y mejora del servicio.',
                    ],
                    'bullets' => [
                        'El portal protege la informacion con medidas razonables de seguridad.',
                        'El usuario puede solicitar actualizacion o correccion de datos.',
                    ],
                ],
            ],
        ]);
    }
}
