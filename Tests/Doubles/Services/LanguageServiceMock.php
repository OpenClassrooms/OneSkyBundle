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
    public function getLanguages(array $project, array $locales)
    {
        self::$calledGetLanguages = true;
        foreach ($locales as $locale) {
            if(in_array($locale, $project["locales"]))
                self::$locales[$project["id"]][] = $locale;
        }
        if(isset(self::$languages[$project["id"]]) && isset(self::$locales[$project["id"]])) {
            $languages = array();
            foreach (self::$languages[$project["id"]] as $language)
                if(in_array($language->getLocale(), self::$locales[$project["id"]]))
                    $languages[] = $language;
            return $languages;
        }
        else
            return array();
    }
}
