<?php

namespace MercuryHolidays\Search;
use MercuryHolidays\Search\Data;

class Searcher
{

    private \MercuryHolidays\Search\Data $data;

    /**
     * @var array
     */
    private array $properties;

    /**
     * Searcher constructor.
     */
    public function __construct()
    {
        // Get the stored property data
        $data = new Data();
        $this->data = $data;
        $this->properties = $data->getProperties();
    }


    /**
     * @throws \Exception
     */
    public function search(int $roomsRequired, $minimum, $maximum): array
    {
        $criteria = [
            'rooms_required' => $roomsRequired,
            'min' => $minimum,
            'max' => $maximum
        ];

        $results = $this->searchProcess(
            $this->properties,
            $criteria
        );

        if(isset($results)) {
            return $results;
        } else {
            return [];
        }
    }

    private function searchProcess($properties, $criteria): array
    {
        foreach($properties as $property) {
            $this->isRoomAvailable($property);
            $this->isRoomWithinBudget($property, $criteria);
            $this->isRoomAdjacent($this->data->getProperties(), $criteria);
        }

        return $this->data->getProperties();
    }

    /**
     * Check the available property
     * @param array $property
     * @return bool
     */
    private function isRoomAvailable(array $property): bool
    {
        $available = $property['available'];

        if(isset($available) && $available == true){
            return true;
        } else {
            $this->data->remove($property);
            return false;
        }
    }

    /**
     * Check if a room is within budget
     * @param array $property
     * @param array $criteria
     * @return bool
     */
    private function isRoomWithinBudget(array $property, array $criteria): bool
    {
        $min = $criteria['min'];
        $max = $criteria['max'];

            if ($property['room_price'] <= $max && $property['room_price'] >= $min) {
                return true;
            } else {
                // If its out of budget then its no longer suitable
                $this->data->remove($property);
                return false;
            }
    }

    /**
     * Check if a room is on the same floor as others and next to others
     * We rely on usort to do the comparison
     * @param array $properties
     * @param array $criteria
     * @return bool
     */
    private function isRoomAdjacent(array $properties, array $criteria): bool
    {
        if($criteria['rooms_required'] > 1) {

            $sortArray = $properties;

            // Sort the array to check we are on the same floor
            usort($sortArray, function ($item1, $item2) {
                if ($item1['floor'] == $item2['floor']) {
                    // its on the same floor
                    return 1;
                } else {
                    // dont return its not on the same floor
                    return 0;
                }
            });

            // Check the array for adjacent rooms
            usort($sortArray, function ($item1, $item2) {
                $comparisonDiff = $item1['room_number'] - $item2['room_number'];

                if ($item1['room_number'] == $item2['room_number'])
                    return 0;

                if ($comparisonDiff > 1 || $comparisonDiff < -1) {
                    // Not next to each other
                    return 0;
                } else {
                    // Next to each other
                    return 1;
                }
            });

            foreach($sortArray as $suitableRoom) {
                foreach($this->data->getProperties() as $possibleRoom) {
                    if($possibleRoom !== $suitableRoom){
                        $this->data->remove($possibleRoom);
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }
}
