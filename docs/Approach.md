# Field Mapping Configuration Contract

## Overview

This documentation describes the field mapping configuration system that translates data between customer input and ACME's system. The configuration acts as a translation layer, ensuring proper data transformation and validation between the two systems.

## Configuration Structure

The configuration is defined in YAML format with a root element `TarificacionThirdPartyRequest` and contains detailed field definitions for data transformation.

## Field Types

The system supports four distinct types of fields, each serving different purposes in the data transformation process:

| Field Type | Description | Example | Input → Output |
|------------|-------------|---------|----------------|
| Regular Fields | Standard fields where values pass through with minimal transformation | `prevInsurance_years` | `5` → `5` |
| Value Conversion Fields | Fields that transform input values to different output values | `prevInsurance_exists` | `"SI"` → `"N"` |
| Static Fields | Fields that always output the same value regardless of input | `current_date` | Any → `"ALWAYS_THIS_VALUE"` |
| Computed Fields | Fields automatically calculated by the system | `additionalDriversCount` | System calculated |

## XML Path Structure

The `xml_path` property defines the location where data will be stored in ACME's system. It follows a hierarchical structure:

```
Datos/
├── DatosGenerales/
│   └── [Field Value]
└── DatosAseguradora/
└── [Field Value]
```

Some fields may be stored in multiple locations, represented by multiple paths in the configuration.

## Field Definition Properties

Each field in the configuration includes the following properties:

| Property | Description | Required |
|----------|-------------|-----------|
| `field` | Internal field name | Yes |
| `maps_to` | Corresponding field name in ACME's system | Yes |
| `description` | Purpose and usage of the field | Yes |
| `required` | Whether the field must have a value | Yes |
| `values` | Value conversion mapping (if applicable) | No |
| `validation` | Data validation rules | Yes |
| `xml_path` | Storage location(s) in ACME's system | Yes |

## Validation Rules

The validation property can include:

- Data type (`string`, `integer`, `date`)
- Allowed values for enumerated fields
- Minimum and maximum values for numeric fields
- Date format specifications

## Data Types and Validation Rules

The system (currently but can be added in the future) supports three primary data types with specific validation rules and formats:

### 1. String Type
String fields accept text values and can have specific validation rules:

```yaml
validation:
  type: "string"
  allowed_values: ["SI", "NO"]  # Restricts input to specific values
```
Examples:
- Valid: "SI", "NO"
- Invalid: "Yes", "S", "true"

### 2. Integer Type
Integer fields accept whole numbers and can have range restrictions:
```yaml
validation:
  type: "integer"
  min: 0
  max: 99
```
Examples:
- Valid: 5, 42, 0, 99
- Invalid: -1, 100

### 3. Date Type
Date fields support various formats and can be configured for specific input and output formatting:
```yaml
validation:
  type: "date"
  format: "Y-m-d"          # Input format
  output_format: "Y-m-d\\T00:00:00"  # Output format
```

Date Format Patterns:
```
Y: Four-digit year (2024)
m: Month with leading zeros (01-12)
d: Day with leading zeros (01-31)
```

## Contract
You can find and modify the contract in `app/config/mappings/insurance/acme_mapping.yml`:

```yaml
description: |
  Field Mapping Configuration Guide
  --------------------------------

  This file helps translate information between our system and ACME's system, similar to 
  a translation dictionary between two languages. Each field in this file describes how 
  to convert data from our format to ACME's format.

  Understanding Different Types of Fields:
  
  1. Regular Fields (Most Common)
     These are normal fields where customer provides a value.
     Example: prevInsurance_years
     - Customer enters: 5
     - System sends to ACME as: 5
  
  2. Fields with Value Conversion
     These fields convert one set of values to another.
     Example: prevInsurance_exists
     - Customer enters: "SI" (meaning Yes)
     - System converts and sends to ACME as: "N"
     - Customer enters: "NO" (meaning No)
     - System converts and sends to ACME as: "S"

  3. Static Fields
     These fields always send the same value to ACME, regardless of input.
     Example: my_static_field:
       static: "ALWAYS_THIS_VALUE"
     - System will always send: "ALWAYS_THIS_VALUE" to ACME
  
  4. Computed Fields
     These fields are calculated automatically by the system.
     You don't need to worry about these - the system handles them.
     Example: additionalDriversCount (automatically calculated based on other inputs)

  Understanding XML Path:
  
  The xml_path shows where in ACME's system the information will be stored, like a filing system:
  Example:
    xml_path:
      - "Datos/DatosGenerales"
      - "Datos/DatosAseguradora"
  
  Think of it like a filing cabinet:
  - Cabinet (Datos)
    └── Folder (DatosGenerales)
        └── Your information goes here
  
  Some fields might need to be stored in multiple locations, that's why some 
  fields have multiple paths. You don't need to modify these paths - they're 
  set up to match ACME's system structure.

  Each Field Description Includes:

  - field: The name of the input field in our system
  - maps_to: The corresponding field name in ACME's system
  - description: Explains what the field is used for
  - required: Indicates if the field must have a value (true/false)
    - true => Yes
    - false => No
  - values: Shows how values are converted to ACME's values
  - validation: Rules to ensure data is correct, including:
      * what kind of data is allowed (text, numbers, dates)
      * what values are acceptable
      * for numbers: minimum and maximum allowed values
  - xml_path: Where the data will be stored in ACME's system

  Real Examples:

  1. Simple Number Field (prevInsurance_years):
     - What it is: Number of years with previous insurance
     - What you can enter: Any number between 0 and 99
     - Required: Yes
     - No value conversion needed
     - Stored in: Datos/DatosGenerales

  2. Yes/No Field (prevInsurance_exists):
     - What it is: Whether there is a previous insurance still valid
     - What you can enter: Only "SI" (Yes) or "NO" (No)
     - Required: Yes
     - Values are converted:
       * When you enter "SI", ACME receives "N"
       * When you enter "NO", ACME receives "S"
     - Stored in two places:
       * Datos/DatosGenerales
       * Datos/DatosAseguradora

  3. Static Field (current_date):
     - What it is: Today's date
     - You don't enter anything - system automatically uses current date
     - System handles the date format
     - Stored in: Datos/DatosGenerales

  4. Computed Field (additionalDriversCount):
     - What it is: Number of additional drivers
     - You don't enter anything - system calculates this automatically
     - System determines the value based on other information
     - Stored in: Datos/DatosGenerales

  Remember:
  - Only modify fields that don't have 'static' or 'computed' markers
  - Always use the exact values listed in 'allowed_values' when specified
  - Pay attention to 'required: true' fields - these must have values

root: "TarificacionThirdPartyRequest"

field_definitions:
  holder:
    field: "holder"
    maps_to: "CondPpalEsTomador"
    xml_path:
      - "Datos/DatosGenerales"
    required: true
    description: "Indicates if the main driver is also the policy holder"
    values:
      CONDUCTOR_PRINCIPAL: "S"
      TOMADOR: "N"
    validation:
      type: "string"
      allowed_values: ["CONDUCTOR_PRINCIPAL", "TOMADOR"]

  occasionalDriver:
    field: "occasionalDriver"
    maps_to: "ConductorUnico"
    xml_path:
      - "Datos/DatosGenerales"
    required: true
    description: "Indicates if there is only one driver"
    values:
      NO: "S"
      SI: "N"
    validation:
      type: "string"
      allowed_values: ["SI", "NO"]

  prevInsurance_years:
    field: "prevInsurance_years"
    maps_to: "AnosSegAnte"
    xml_path:
      - "Datos/DatosGenerales"
    required: true
    description: "Number of years with previous insurance"
    validation:
      type: "integer"
      min: 0
      max: 99

  prevInsurance_exists:
    field: "prevInsurance_exists"
    maps_to: "SeguroEnVigor"
    xml_path:
      - "Datos/DatosGenerales"
      - "Datos/DatosAseguradora"
    required: true
    description: "Indicate if there is a previous insurance, and the previous insurance is still valid"
    values:
      NO: "S"
      SI: "N"
    validation:
      type: "string"
      allowed_values: ["SI", "NO"]

  current_date:
    field: ""
    static: "now"
    maps_to: "FecCot"
    xml_path:
      - "Datos/DatosGenerales"
    description: "Current date as ISO"
    validation:
      type: "date"
      format: "Y-m-d"
      output_format: "Y-m-d\\T00:00:00"

  additionalDriversCount:
    field: "occasionalDriver"
    maps_to: "NroCondOca"
    xml_path:
      - "Datos/DatosGenerales"
    computed: true
    description: "Number of additional drivers"
    values:
      NO: "S"
      SI: "N"
    validation:
      type: "string"
      allowed_values: ["SI", "NO"]

```