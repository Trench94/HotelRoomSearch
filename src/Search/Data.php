<?php


namespace MercuryHolidays\Search;


class Data
{
    private array $properties = [];

    private array $data = [
        ['Hotel A', false, 1, 1, 25.80],
        ['Hotel A', false, 1, 2, 25.80],
        ['Hotel A', true, 1, 3, 25.80],
        ['Hotel A', true, 1, 4, 25.80],
        ['Hotel A', false, 1, 5, 25.80],
        ['Hotel A', false, 2, 6, 30.10],
        ['Hotel A', true, 2, 7, 35.00],
        ['Hotel B', true, 1, 1, 45.80],
        ['Hotel B', false, 1, 2, 45.80],
        ['Hotel B', true, 1, 3, 45.80],
        ['Hotel B', true, 1, 4, 45.80],
        ['Hotel B', false, 1, 5, 45.80],
        ['Hotel B', false, 2, 6, 49.00],
        ['Hotel B', false, 2, 7, 49.00]
    ];

    public function __construct()
    {
        // Clear the cache for new initialization
        $this->clear();

        // Run setup for each property in our data
        foreach($this->data as $property){
            $this->prepare($property);
        }
    }

    /**
     * Prepare the property array
     * @param $data
     */
    private function prepare($data)
    {
        $property = [
            'name' => $data[0],
            'available' => $data[1],
            'floor' => $data[2],
            'room_number' => $data[3],
            'room_price' => $data[4]
        ];

        $this->add($property);
    }

    /**
     * Add property to the array and save it to the data store
     * @param array $property
     * @return void
     */
    public function add(array $property): void
    {
        array_push($this->properties, $property);
        $this->save();
    }

    /**
     * Remove the property from our properties array and save in data store
     * @param $property
     */
    public function remove($property): void
    {
        $found = array_search($property, $this->properties);

        if($found !== false) {
            array_splice( $this->properties, $found, 1);
            $this->save();
        }
    }

    /**
     * Save the property to the data store using APCU
     */
    private function save(): void
    {
        apcu_store('properties', $this->properties);
    }

    /**
     * Clear the data store using APCU
     */
    private function clear(): void
    {
        apcu_clear_cache();
    }

    /**
     * Get the stored properties
     * @return array
     */
    public function getProperties(): array
    {
        return apcu_fetch('properties');
    }
}