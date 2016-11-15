<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Language;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageImpl extends Language
{
    public function __construct(array $data, $projectId)
    {
        $this->locale = $data['locale'];
        $this->translationProgress = $data['translation_progress'];
        $this->projectId = $projectId;
    }
}
