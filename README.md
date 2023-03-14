# OEICD PIB

This package is a PHP wrapper for csv coming from https://data.oecd.org/fr/gdp/produit-interieur-brut-pib.htm.


## Installation

Install using composer : 

> composer require hguenot/oecd-pib

## Usage

The package offers only one singleton class `\data\oecd\pib\PibDb`.

Use the `getInstance` method to get the instance. 

- `getCountries` list all available countries in the file

  It returns an array of `string`

- `getYears` list all available years in the file

  It returns an array of `int`

- `getAll` retrieves all PIB data
- `getValuesForCountry` list all values for a specific country, order by year
- `getValuesForYear` list all values for a specific year, order by country
- `getLatestValues` list last available values indexed by country

The last methods returns an iterable. Each value is an associative array : 

- `LOCATION`: Country code
- `INDICATOR`: Always "`GDP`"
- `SUBJECT`: always "`TOT`"
- `MEASURE`: 
  - "`USD_CAP`" (USD per Capita)
  - "`MLN_USD`" (Million USD)
- `FREQUENCY`: always "`A`"
- `TIME`: the year
- `Value`: The PIB value
- `Flag Codes`: ?
 
