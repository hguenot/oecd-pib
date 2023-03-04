<?php declare(strict_types=1);

use data\oecd\pib\PibDb;
use PHPUnit\Framework\TestCase;

class PibDbTest extends TestCase {

	public function testGetCountries() {
		$expected = self::getJson(__DIR__ . '/countries.json');
		$this->assertEquals($expected, PibDb::getInstance()->getCountries());
	}

	public function testGetYears() {
		$expected = self::getJson(__DIR__ . '/years.json');
		$this->assertEquals($expected, PibDb::getInstance()->getYears());
	}

	public function testGetValuesForCountry() {
		$expected = self::getJson(__DIR__ . '/fra_values.json');
		$this->assertEquals($expected, iterator_to_array(PibDb::getInstance()->getValuesForCountry('FRA'), false));
	}

	public function testGetValuesForYear() {
		$expected = self::getJson(__DIR__ . '/2018_values.json');
		$this->assertEquals($expected, iterator_to_array(PibDb::getInstance()->getValuesForYear(2018), false));
	}

	public function testGetLatest() {
		$expected = self::getJson(__DIR__ . '/latest_values.json');
		$this->assertEquals($expected, iterator_to_array(PibDb::getInstance()->getLatestValues()));
	}

	private static function getJson($file) {
		$json = file_get_contents($file);
		return json_decode($json, true);
	}

}