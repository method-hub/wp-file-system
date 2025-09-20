<?php

namespace Metapraxis\WPFileSystem\Tests\Integration;

use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;
use WP_UnitTestCase;

abstract class TestCase extends WP_UnitTestCase
{
    protected string $textDocument;
    protected string $jsonDocument;
    protected string $xmlDocument;
    protected string $htmlDocument;

    public static function setUpBeforeClass(): void
    {
        add_filter('mime_types', function ($mime_types) {
            $mime_types['html'] = 'text/html';
            $mime_types['json'] = 'application/json';

            return $mime_types;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        $outputDir = __DIR__ . '/output/mock-data';
        $inputDir = __DIR__ . '/input/mock-data';

        file_put_contents($outputDir . '.txt', file_get_contents($inputDir . '.txt'));
        file_put_contents($outputDir . '.json', file_get_contents($inputDir . '.json'));
        file_put_contents($outputDir . '.xml', file_get_contents($inputDir . '.xml'));
        file_put_contents($outputDir . '.html', file_get_contents($inputDir . '.html'));

        $this->textDocument = file_get_contents($outputDir . '.txt');
        $this->jsonDocument = file_get_contents($outputDir . '.json');
        $this->xmlDocument = file_get_contents($outputDir . '.xml');
        $this->htmlDocument = file_get_contents($outputDir . '.html');

        FSGuardiansProvider::setStatus(false);
        FSHooksProvider::setStatus(false);
    }

    protected function tearDown(): void
    {
        $outputDir = __DIR__ . '/output/mock-data';

        file_put_contents($outputDir . '.txt', '');
        file_put_contents($outputDir . '.json', '');
        file_put_contents($outputDir . '.xml', '');
        file_put_contents($outputDir . '.html', '');
    }
}
