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

    public function testSearchDoesReturnEmptyArray(): void
    {
        $results = $this->searcher->search(0, 0, 0);

        $this->assertCount(
            0,
            $results, "search function doesnt return an empty array on zero values"
        );
    }

    public function testSearchDoesReturnValidArray(): void
    {
        $results = $this->searcher->search(2, 20, 30);

        $this->assertIsArray($results, 'Search must return type array');
    }

    public function testSearchAvailableRooms(): void
    {
        $data = $this->dataArray->properties;

        $results = $this->invokeMethod($this->searcher, 'isRoomAvailable', array($data[0]));

        $this->assertIsBool($results, 'Search must return type boolean');
    }

    public function testSearchRoomWithinBudget(): void
    {
        $data = $this->dataArray->properties;

        $criteria = [
            'rooms_required' => 1,
            'min' => 30,
            'max' => 50
        ];

        $results = $this->invokeMethod($this->searcher, 'isRoomWithinBudget', array($data[0], $criteria));

        $this->assertIsBool($results, 'Search must return type boolean');
    }

    public function testSearchIsRoomAdjacent(): void
    {
        $data = $this->dataArray->properties;

        $criteria = [
            'rooms_required' => 1,
            'min' => 30,
            'max' => 50
        ];

        $results = $this->invokeMethod($this->searcher, 'isRoomAdjacent', array($data[0], $criteria));

        $this->assertIsBool($results, 'Search must return type boolean');
    }
}
