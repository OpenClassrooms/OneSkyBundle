<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Language;
use OpenClassrooms\Bundle\OneSkyBundle\Services\LanguageService;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageServiceMock implements LanguageService
{
    /**
     * @var bool
     */
    public static $calledGetLanguages;

    /**
     * @var Language[]
     */
    public static $languages;

    /**
     * @var string[]
     */
    public static $locales;

    public function __construct()
    {
        self::$calledGetLanguages = false;
        self::$languages = [];
        self::$locales = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguages($projectId, array $locales = [])
    {
        self::$calledGetLanguages = true;
        self::$locales[$projectId] = $locales;

        if(isset(self::$languages[$projectId]))
            return self::$languages[$projectId];
        else
            return array();
    }
}
