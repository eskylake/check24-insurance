<?php

declare(strict_types=1);

namespace App\Insurance\Presentation\CLI;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Shared\Domain\Prettier\PrettierInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Insurance\Domain\Command\CreateInsuranceRequestCommandInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'insurance:map-input',
    description: 'Map customer input to specific Insurance request',
    hidden: false
)]
class CreateInsuranceRequestCmd extends Command
{
    public function __construct(
        private LoggerInterface                        $logger,
        private ParameterBagInterface                  $params,
        private CreateInsuranceRequestCommandInterface $createInsuranceRequestCommand,
        private PrettierInterface                      $prettier,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('input-path', InputArgument::OPTIONAL, 'JSON input file path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputPath = $input->getArgument('input-path');

        try {
            $this->logger->info('Starting ACME request generation', ['inputPath' => $inputPath]);

            $xml = $this->createInsuranceRequestCommand->execute($inputPath ?: $this->params->get('app.acme_customer_input_file'));

            $output->writeln('<info>' . $this->prettier->pretty($xml) . '</info>');

            $this->logger->info('Successfully generated ACME request', ['output_file' => ""]);
            $io->success("XML request generated successfully: yeaah");
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->logger->error('Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $io->error("An unexpected error occurred: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }
}
