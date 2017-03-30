<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 30/03/17
 * Time: 10:31
 */

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model;


class ProjectsStub
{
    public static $projects = [
        1 => [
            "id" => 1,
            "file_format" => "yml",
            "source_locale" => "en",
            "locales" => ["en","ja"],
            "keep_all_strings" => 0,
            "file_paths" => [ __DIR__.'/../../Fixtures/Resources/translations/']
        ],
        2 => [
            "id" => 2,
            "file_format" => "yml",
            "source_locale" => "ja",
            "locales" => ["ja"],
            "keep_all_strings" => 0,
            "file_paths" => [ __DIR__.'/../../Fixtures/Resources/translations/subDirectory' ]
        ]
    ];

}