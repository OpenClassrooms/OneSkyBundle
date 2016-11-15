<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Command;

use OpenClassrooms\Bundle\OneSkyBundle\Command\PullCommand;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services\TranslationServiceMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PullCommandTest extends \PHPUnit_Framework_TestCase
{
    use CommandTestCase;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @test
     */
    public function without_locales_execute()
    {
        $this->commandTester->execute(['command' => PullCommand::COMMAND_NAME]);
        $this->assertEquals([], TranslationServiceMock::$locales);
    }

    /**
     * @test
     */
    public function with_locales_execute()
    {
        $this->commandTester->execute(['command' => PullCommand::COMMAND_NAME, '--locale' => ['es']]);
        $this->assertEquals(['es'], TranslationServiceMock::$locales);
    }

    /**
     * @test
     */
    public function without_filePaths_execute()
    {
        $this->commandTester->execute(['command' => PullCommand::COMMAND_NAME]);
        $this->assertEquals([], TranslationServiceMock::$pulledFilePaths);
    }

    /**
     * @test
     */
    public function with_filePath_execute()
    {
        $this->commandTester->execute(['command' => PullCommand::COMMAND_NAME, '--projectId' => [self::$projectId], '--filePath' => [self::$filePaths]]);
        $this->assertEquals([self::$filePaths], TranslationServiceMock::$pulledFilePaths);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $command = new PullCommand();
        $command->setContainer($this->getContainer());

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find($command->getName()));
    }
}
