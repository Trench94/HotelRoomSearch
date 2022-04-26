<?php

namespace Tests\Search;

use MercuryHolidays\Search\Data;
use MercuryHolidays\Search\Searcher;
use PHPUnit\Framework\TestCase;

class SearcherTest extends TestCase
{
    /**
     * @var Searcher
     */
    private $searcher;
    /**
     * @var Data
     */
    private $dataArray;

    protected function setUp(): void
    {
        $this->dataArray = new Data();
        $this->searcher = new Searcher();
    }

    /**
     * @throws \Exception
     */
    public function testSearchDoesReturnEmptyArray(): void
    {
        $results = $this->searcher->search(0, 0, 0);

        $this->assertCount(
            0,
            $results, "search function doesnt return an empty array on zero values"
        );
    }

    public function testSearchDoesReturnValidResults(): void
    {
        $results = $this->searcher->search(2, 20, 30);

        $this->assertCount(
            0,
            $results, "search function doesnt return an empty array on zero values"
        );
    }
}
