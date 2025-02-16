<?php

declare(strict_types=1);

namespace App\Tests\Feature\Insurance\Presentation\CLI;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;

class CreateInsuranceRequestCmdTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('insurance:map-input');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithValidInputPathSuccessfully(): void
    {
        $this->commandTester->execute([
            'input-path' => 'stub/input/valid_customer_input.json',
        ]);

        $this->commandTester->assertCommandIsSuccessful();

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('xml', $output);
    }

    public function testExecuteWithEmptyInputPathAndGetError(): void
    {
        $this->commandTester->execute([
            'input-path' => 'stub/input/empty_customer_input.json',
        ]);

        $this->assertEquals(Command::FAILURE, $this->commandTester->getStatusCode());

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('error', strtolower($output));
    }

    public function testExecuteWithInvalidInputPathAndGetError(): void
    {
        $this->commandTester->execute([
            'input-path' => 'stub/input/invalid_customer_input.json',
        ]);

        $this->assertEquals(Command::FAILURE, $this->commandTester->getStatusCode());

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('error', strtolower($output));
    }

    public function testExecuteWithNotExistsInputPathAndGetError(): void
    {
        $this->commandTester->execute([
            'input-path' => 'not_exists_file.json',
        ]);

        $this->assertEquals(Command::FAILURE, $this->commandTester->getStatusCode());

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('error', strtolower($output));
    }
}
