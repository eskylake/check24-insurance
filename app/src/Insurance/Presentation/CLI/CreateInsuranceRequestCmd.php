<?php

declare(strict_types=1);

namespace App\Insurance\Presentation\CLI;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use App\Insurance\Domain\Command\CreateInsuranceRequestCommandInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'insurance:map-input',
    description: 'Map customer input to specific Insurance request',
    aliases: ['ins:map-i'],
    hidden: false
)]
class CreateInsuranceRequestCmd extends Command
{
    public function __construct(
        private LoggerInterface $logger,
        private ParameterBagInterface $params,
        private CreateInsuranceRequestCommandInterface $createInsuranceRequestCommand,
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

            $result = $this->createInsuranceRequestCommand->execute($inputPath ?: $this->params->get('app.acme_customer_input_file'));

            dump($result);

            $this->logger->info('Successfully generated ACME request', ['output_file' => ""]);
            $io->success("XML request generated successfully: yeaah");
            return Command::SUCCESS;

        } catch (\JsonException $e) {
            $this->logger->error('Invalid JSON format', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $io->error("Invalid JSON format: {$e->getMessage()}");
            return Command::FAILURE;
        } catch (\Throwable $e) {
            $this->logger->error('Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $io->error("An unexpected error occurred: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}


//
//private function mapping(array $data): void
//{
//    $mappingFile = $this->params->get('app.acme_mapping_file');
//    $mappingConfig = Yaml::parseFile($mappingFile);
//
//    $definitions = $mappingConfig['field_definitions'];
//
//    $violations = [];
//
//    foreach ($definitions as $fieldName => $fieldConfig) {
//        $customerField = $fieldConfig['field'];
//
//        // Check if field is required and missing
//        if ($fieldConfig['required'] ?? false) {
//            if (!isset($data[$customerField]) || $data[$customerField] === '') {
//                $violations[] = sprintf(
//                    "Field '%s' is required but missing or empty",
//                    $customerField
//                );
//                continue;
//            }
//        }
//
//        // If field is present, validate its value
//        if (isset($data[$customerField])) {
//            $validationRules = $fieldConfig['validation'] ?? [];
//            $fieldViolations = $this->validateField(
//                $data[$customerField],
//                $validationRules,
//                $customerField
//            );
//            $violations = array_merge($violations, $fieldViolations);
//
////                dd($customerField, $data[$customerField], $fieldConfig['values'][$data[$customerField]]);
//        }
//    }
//
//    if (!empty($violations)) {
//        throw new Exception(
//            "Validation failed: " . implode(", ", $violations)
//        );
//    }
//}
//
//private function validateField(mixed $value, array $rules, string $fieldName): array
//{
//    $violations = [];
//    $type = $rules['type'] ?? null;
//
//    switch ($type) {
//        case 'string':
//            if (isset($rules['allowed_values'])) {
//                if (!in_array($value, $rules['allowed_values'])) {
//                    $violations[] = sprintf(
//                        "Field '%s' must be one of: %s",
//                        $fieldName,
//                        implode(', ', $rules['allowed_values'])
//                    );
//                }
//            }
//            break;
//
//        case 'integer':
//            if (!is_numeric($value)) {
//                $violations[] = sprintf(
//                    "Field '%s' must be a number",
//                    $fieldName
//                );
//            } else {
//                $intValue = (int)$value;
//                if (isset($rules['min']) && $intValue < $rules['min']) {
//                    $violations[] = sprintf(
//                        "Field '%s' must be at least %d",
//                        $fieldName,
//                        $rules['min']
//                    );
//                }
//                if (isset($rules['max']) && $intValue > $rules['max']) {
//                    $violations[] = sprintf(
//                        "Field '%s' must not exceed %d",
//                        $fieldName,
//                        $rules['max']
//                    );
//                }
//            }
//            break;
//
//        case 'date':
//            try {
//                $date = new \DateTime($value);
//                if (isset($rules['min_age'])) {
//                    $minDate = (new \DateTime())->modify("-{$rules['min_age']} years");
//                    if ($date > $minDate) {
//                        $violations[] = sprintf(
//                            "Driver must be at least %d years old",
//                            $rules['min_age']
//                        );
//                    }
//                }
//                if (isset($rules['max_age'])) {
//                    $maxDate = (new \DateTime())->modify("-{$rules['max_age']} years");
//                    if ($date < $maxDate) {
//                        $violations[] = sprintf(
//                            "Driver must not be older than %d years",
//                            $rules['max_age']
//                        );
//                    }
//                }
//            } catch (\Exception $e) {
//                $violations[] = sprintf(
//                    "Field '%s' must be a valid date in format %s",
//                    $fieldName,
//                    $rules['format'] ?? 'Y-m-d'
//                );
//            }
//            break;
//    }
//
//    return $violations;
//}
