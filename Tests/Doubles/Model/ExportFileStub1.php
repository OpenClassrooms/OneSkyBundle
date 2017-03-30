<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ExportFileStub1 extends ExportFileStub
{
    const SOURCE_FILE_PATH = __DIR__.'/../../Fixtures/Resources/translations/messages.en.yml';

    const REQUESTED_LOCALE = 'fr';

    public function __construct()
    {
        parent::__construct(self::PROJECT, self::SOURCE_FILE_PATH, self::PROJECT_DIRECTORY, self::REQUESTED_LOCALE);
    }
}
