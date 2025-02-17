<div align="center">
  <h1>
  	Check24 Challenge
  </h1>
  <p>
  	Wrapper service for mapping customer request inputs to API calls for a specific ACME insurance
provider.
  </p>
</div>


### Directory Structure

```sh
.
├── app
│   ├── config
│   │   ├── mappings
│   │   │   └── insurance
│   │   │       └── acme_mapping.yml # Mapping contract between non-technical people and the system
│   ├── input
│   │   └── sample_customer_input.json # Dummy input for test
│   ├── src
│   │   ├── FieldMapping # Responsible to map input data with mapping contracts
│   │   │   ├── Application
│   │   │   │   └── Service
│   │   │   │       ├── FieldMapperService.php
│   │   │   │       ├── FieldMappingService.php
│   │   │   │       ├── FieldStaticService.php
│   │   │   │       ├── FieldValidatorService.php
│   │   │   │       └── XmlStructureBuilderService.php
│   │   │   └── Domain
│   │   │       ├── DataObject
│   │   │       │   ├── MappedData.php
│   │   │       │   └── ProcessedField.php
│   │   │       ├── Exception
│   │   │       │   ├── FieldMapperException.php
│   │   │       │   └── FieldValidationException.php
│   │   │       ├── Service
│   │   │       │   ├── FieldMapperServiceInterface.php
│   │   │       │   ├── FieldMappingServiceInterface.php
│   │   │       │   ├── FieldStaticServiceInterface.php
│   │   │       │   ├── FieldValidatorServiceInterface.php
│   │   │       │   └── XmlStructureBuilderServiceInterface.php
│   │   │       └── ValueObject
│   │   │           ├── FieldDefinition.php
│   │   │           ├── ValidationRule.php
│   │   │           └── XmlPath.php
│   │   ├── Insurance # Responsible to handle all insurance related logics
│   │   │   ├── Application
│   │   │   │   ├── Command
│   │   │   │   │   └── CreateInsuranceRequestCommand.php
│   │   │   │   ├── Repository
│   │   │   │   ├── Service
│   │   │   │   │   ├── ComputedFieldService.php
│   │   │   │   │   └── RequestBuilderService.php
│   │   │   │   └── UseCase
│   │   │   │       └── MapInputToXMLRequestUseCase.php
│   │   │   ├── Domain
│   │   │   │   ├── Command
│   │   │   │   │   └── CreateInsuranceRequestCommandInterface.php
│   │   │   │   ├── Exception
│   │   │   │   │   └── FieldDefinitionException.php
│   │   │   │   ├── Repository
│   │   │   │   ├── Service
│   │   │   │   │   ├── ComputedFieldServiceInterface.php
│   │   │   │   │   └── RequestBuilderServiceInterface.php
│   │   │   │   ├── UseCase
│   │   │   │   │   └── MapInputToXMLRequestUseCaseInterface.php
│   │   │   │   └── ValueObject
│   │   │   │       ├── MappedData.php
│   │   │   │       └── Mapping.php
│   │   │   ├── Infrastructure
│   │   │   └── Presentation
│   │   │       └── CLI
│   │   │           └── CreateInsuranceRequestCmd.php #  --- Command line starts from here ---
│   │   └── Shared # Shared services between domains
│   │       ├── Application
│   │       │   └── Service
│   │       │       ├── Prettier
│   │       │       │   └── XmlPrettier.php
│   │       │       ├── Serializer
│   │       │       │   └── XmlSerializer.php
│   │       │       ├── StaticHandler
│   │       │       │   ├── StaticHandlerFactory.php
│   │       │       │   └── StaticNowHandler.php
│   │       │       └── Validator
│   │       │           ├── DateValidator.php
│   │       │           ├── IntegerValidator.php
│   │       │           ├── StringValidator.php
│   │       │           └── ValidatorFactory.php
│   │       ├── Domain
│   │       │   ├── DataObject
│   │       │   │   └── ValidationResult.php
│   │       │   ├── Exception
│   │       │   │   ├── EmptyInputFileException.php
│   │       │   │   ├── FailedToReadFileException.php
│   │       │   │   └── InputFileNotFoundException.php
│   │       │   ├── InputParser
│   │       │   │   └── InputParserInterface.php
│   │       │   ├── MappingProvider
│   │       │   │   └── MappingProviderInterface.php
│   │       │   ├── Prettier
│   │       │   │   └── PrettierInterface.php
│   │       │   ├── Serializer
│   │       │   │   └── XmlSerializerInterface.php
│   │       │   ├── StaticHandler
│   │       │   │   └── StaticHandlerInterface.php
│   │       │   └── Validator
│   │       │       └── ValidatorInterface.php
│   │       └── Infrastructure
│   │           ├── InputParser
│   │           │   └── JsonInputParser.php
│   │           └── MappingProvider
│   │               └── YamlMappingProvider.php
└── docs # All the documentation
```