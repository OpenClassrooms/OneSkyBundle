<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Model\LanguageFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageFactoryImpl implements LanguageFactory
{
    /**
     * {@inheritdoc}
     */
    public function createFromCollection(array $data, $projectId)
    {
        $languages = [];
        foreach ($data as $item) {
            $languages[] = new LanguageImpl($item, $projectId);
        }

        return $languages;
    }
}
