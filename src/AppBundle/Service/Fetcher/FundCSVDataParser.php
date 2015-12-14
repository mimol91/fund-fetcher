<?php

namespace AppBundle\Service\Fetcher;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Model\FundData;
use DateTimeImmutable;
use NumberFormatter;

class FundCSVDataParser
{
    const DELIMITER = ';';

    /** @var NumberFormatter */
    private $numberFormatter;

    public function __construct()
    {
        $this->numberFormatter = new NumberFormatter('pl_PL', NumberFormatter::DECIMAL);
    }

    /**
     * @param string $csvData
     *
     * @return FundDataCollection
     */
    public function getFundData($csvData)
    {
        $result = new FundDataCollection();
        $csv = $this->mapToArray($csvData);

        foreach ($csv as $csvRow) {
            $result->add($this->mapRowToFundData($csvRow));
        }

        return $result;
    }

    /**
     * @param string $csvData
     *
     * @return array
     */
    private function mapToArray($csvData)
    {
        $csv = array_map(function ($row) {
            return str_getcsv($row, self::DELIMITER);
        }, array_filter(explode(PHP_EOL, $csvData)));

        return $this->removeRows($csv);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function removeRows(array $data)
    {
        return array_slice($data, 1);
    }

    /**
     * @param array $row
     *
     * @return FundData
     */
    private function mapRowToFundData(array $row)
    {
        list($date, $value) = $row;

        return new FundData(
            new DateTimeImmutable($date),
            $this->numberFormatter->parse($value)
        );
    }
}
