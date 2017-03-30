<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface LanguageFactory
{
    /**
     * @return Language[]
     */
    public function createFromCollection(array $data, $projectId);
}
