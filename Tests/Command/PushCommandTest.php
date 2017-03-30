<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Command;

use OpenClassrooms\Bundle\OneSkyBundle\Command\PushCommand;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services\TranslationServiceMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PushCommandTest extends \PHPUnit_Framework_TestCase
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
        $this->commandTester->execute(['command' => PushCommand::COMMAND_NAME]);
        $this->assertEquals([], TranslationServiceMock::$locales);
    }

    /**
     * @test
     */
    public function with_locales_execute()
    {
        $this->commandTester->execute(['command' => PushCommand::COMMAND_NAME, '--locale' => ['ja']]);
        $this->assertEquals(['ja'], TranslationServiceMock::$locales);
    }

    /**
     * @test
     */
    public function without_filePaths_execute()
    {
        $this->commandTester->execute(['command' => PushCommand::COMMAND_NAME]);
        $this->assertEquals([], TranslationServiceMock::$pushedFilePaths);
    }

    /**
     * @test
     */
    public function with_filePath_execute()
    {
        $this->commandTester->execute(['command' => PushCommand::COMMAND_NAME, '--projectId' => [1], '--filePath' => [self::$filePaths]]);
        $this->assertEquals([self::$filePaths], TranslationServiceMock::$pushedFilePaths);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $command = new PushCommand();
        $command->setContainer($this->getContainer());

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find($command->getName()));
    }
}
