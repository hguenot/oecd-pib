<?php

namespace data\oecd\pib;

use League\Csv\Exception;
use League\Csv\Reader;
use phpstream\collectors\MapCollector;
use phpstream\Stream;

class PibDb {

	private static ?self $_instance = null;

	private ?Reader $reader = null;

	private function __construct() {
		$this->reader = Reader::createFromPath(defined('PI_DB_FILE') ? PI_DB_FILE : __DIR__ . '/../resources/pib.csv');
		$this->reader->setHeaderOffset(0);
	}

	public static function getInstance(): self {
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getAll(): \Iterator {
		return $this->reader->getIterator();
	}

	/**
	 * @return string[]
	 * @throws Exception
	 */
	public function getCountries(): array {
		return Stream::of($this->reader->getIterator())
				->map(fn($a) => $a['LOCATION'])
				->distinct()
				->sort()
				->toArray();
	}

	/**
	 * @return int[]
	 * @throws Exception
	 */
	public function getYears(): array {
		return Stream::of($this->reader->getIterator())
				->map(fn($a) => intval($a['TIME']))
				->distinct()
				->sort()
				->toArray();
	}

	public function getValuesForCountry(string $country, string $measure = 'USD_CAP'): iterable {
		return Stream::of($this->reader->getIterator())
				->filter(fn($a1) => $a1['MEASURE'] == $measure)
				->filter(fn($a) => strtolower($a['LOCATION']) === strtolower($country))
				->sort(fn($a1, $a2) => $a2['TIME'] <=> $a1['TIME'])
				->toIterable();
	}

	public function getValuesForYear(int $year, string $measure = 'USD_CAP'): iterable {
		return Stream::of($this->reader->getIterator())
				->filter(fn($a) => intval($a['TIME']) === $year)
				->filter(fn($a1) => $a1['MEASURE'] == $measure)
				->sort(fn($a1, $a2) => $a1['LOCATION'] <=> $a2['LOCATION'])
				->toIterable();
	}

	public function getLatestValues(string $measure = 'USD_CAP'): iterable {
		$latest = Stream::of($this->reader->getIterator())
				->filter(fn($a1) => $a1['MEASURE'] == $measure)
				->sort(fn($a1, $a2) => $a1['LOCATION'] == $a2['LOCATION']
				 ? intval($a1['TIME']) <=> intval($a2['TIME'])
				 : $a1['LOCATION'] <=> $a2['LOCATION'])
				->collect(new MapCollector(fn($i, $a) => $a['LOCATION']));

		foreach ($latest as $key => $value) {
			yield $key => $value;
		}
	}

}