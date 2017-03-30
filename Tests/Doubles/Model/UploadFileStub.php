<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\UploadFileImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class UploadFileStub extends UploadFileImpl
{

    const PROJECT_DIRECTORY = __DIR__.'/../../../';

    const PROJECT =
    [
        "id" => 1,
        "file_format" => "yml",
        "source_locale" => "en",
        "locales" => ["en","fr","ja"],
        "keep_all_strings" => 0,
        "file_paths" => [ "__DIR__.'/../'Tests/Fixtures/Resources/translations/"]
    ];

    /**
     * @return string
     */
    public function getFormattedFilePath()
    {
        return sys_get_temp_dir().'/'.$this->getEncodedSourceFileName();
    }
}
