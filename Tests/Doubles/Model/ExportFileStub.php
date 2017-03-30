<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\ExportFileImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ExportFileStub extends ExportFileImpl
{
    const PROJECT =
    [
        "id" => 1,
        "file_format" => "yml",
        "source_locale" => "en",
        "locales" => ["en","fr","ja"],
        "keep_all_strings" => 0,
        "file_paths" => [ "__DIR__.'/../'Tests/Fixtures/Resources/translations/"]
    ];
    const PROJECT_DIRECTORY = __DIR__.'/../../../';
}
