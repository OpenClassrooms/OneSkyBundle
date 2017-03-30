<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Command;

use OpenClassrooms\Bundle\OneSkyBundle\Command\CheckTranslationProgressCommand;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub2;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub3;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ProjectsStub;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services\LanguageServiceMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CheckTranslationProgressCommandTest extends \PHPUnit_Framework_TestCase
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
        LanguageServiceMock::$languages = [1 => [new LanguageStub1(), new LanguageStub2()], 2 => [new LanguageStub3()]];
        $this->commandTester->execute(['command' => CheckTranslationProgressCommand::COMMAND_NAME]);
        $wantedLocales = array();
        foreach (ProjectsStub::$projects as $projectId => $project)
            $wantedLocales[$projectId] = $project["locales"];
        $this->assertEquals($wantedLocales, LanguageServiceMock::$locales);
        $this->assertTrue(LanguageServiceMock::$calledGetLanguages);
    }

    /**
     * @test
     */
    public function with_locales_execute()
    {
        LanguageServiceMock::$languages = [1 => [new LanguageStub1(), new LanguageStub2()], 2 => [new LanguageStub3()]];
        $exitCode = $this->commandTester->execute(
            ['command' => CheckTranslationProgressCommand::COMMAND_NAME, '--locale' => [LanguageStub2::LOCALE]]
        );
        $this->assertEquals([ 1 => [LanguageStub2::LOCALE],  2 => [LanguageStub3::LOCALE]], LanguageServiceMock::$locales);
        $this->assertTrue(LanguageServiceMock::$calledGetLanguages);
        $this->assertEquals(1, $exitCode);
    }

    /**
     * @test
     */
    public function WithFullProgression()
    {
        LanguageServiceMock::$languages = [ 1 => [new LanguageStub1()], 2 => [new LanguageStub3()]];
        $exitCode = $this->commandTester->execute(
            ['command' => CheckTranslationProgressCommand::COMMAND_NAME, '--locale' => [LanguageStub1::LOCALE]]
        );
        $this->assertEquals([ 1 => [LanguageStub1::LOCALE]], LanguageServiceMock::$locales);
        $this->assertTrue(LanguageServiceMock::$calledGetLanguages);
        $this->assertEquals(0, $exitCode);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $command = new CheckTranslationProgressCommand();
        $command->setContainer($this->getContainer());

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find($command->getName()));
    }
}
