<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\LanguageImpl;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageStub2 extends LanguageImpl
{
    const LOCALE = 'ja';

    protected $locale = self::LOCALE;

    protected $translationProgress = '98%';

    public function __construct()
    {
        $this->projectId = ProjectsStub::$projects[1]["id"];
    }
}
