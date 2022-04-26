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

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function setUp(): void
    {
        $this->dataArray = new Data();
        $this->searcher = new Searcher();
    }

    /**
     * Empty search check
     * - Check the search returns an empty dataset on zero values
     */
    public function testSearchDoesReturnEmptyArray(): void
    {
        $results = $this->searcher->search(0, 0, 0);

        $this->assertCount(
            0,
            $results, "search function doesnt return an empty array on zero values"
        );
    }

    /**
     * Search is valid array
     * - Check the search results return an array, empty or with elements
     */
    public function testSearchDoesReturnValidArray(): void
    {
        $results = $this->searcher->search(2, 20, 30);

        $this->assertIsArray($results, 'Search must return type array');
    }

    /**
     * Room is available test
     * - Check the property has been excluded from the updatedRecords
     */
    public function testSearchAvailableRooms(): void
    {
        $data = $this->dataArray->properties;

        $this->invokeMethod($this->searcher, 'isRoomAvailable', array($data[0]));

        $updatedRecords = $this->dataArray->getProperties();
        $foundInResults = array_search($data[0], $updatedRecords); // False if not found

        $this->assertFalse($foundInResults, 'Data found in results array');
    }

    /**
     * Room is within budget
     * - Check the property has been excluded from the updatedRecords
     */
    public function testSearchRoomWithinBudget(): void
    {
        $data = $this->dataArray->properties;

        $criteria = [
            'rooms_required' => 1,
            'min' => 30,
            'max' => 50
        ];

        $this->invokeMethod($this->searcher, 'isRoomWithinBudget', array($data[0], $criteria));

        $updatedRecords = $this->dataArray->getProperties();
        $foundInResults = array_search($data[0], $updatedRecords); // False if not found

        $this->assertFalse($foundInResults, 'Data found in results array');
    }

    /**
     * Room is adjacent test
     * - Check the property has been excluded from the updatedRecords
     */
    public function testSearchIsRoomAdjacent(): void
    {
        $data = $this->dataArray->properties;

        $criteria = [
            'rooms_required' => 1,
            'min' => 30,
            'max' => 50
        ];

        $this->invokeMethod($this->searcher, 'isRoomAdjacent', array($data[0], $criteria));

        $updatedRecords = $this->dataArray->getProperties();
        $foundInResults = array_search($data[0], $updatedRecords); // False if not found

        $this->assertFalse($foundInResults, 'Data found in results array');
    }
}
