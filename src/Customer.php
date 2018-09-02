<?php

namespace discounts;

/**
 * Class Customer
 *
 * @package discounts
 */
class Customer
{

    private $id;
    private $name;
    private $since;
    private $revenue;

    public function __construct(int $customerId)
    {
        $customers = $this->getCustomers();

        $pos = array_search($customerId, array_column($customers, 'id'));

        $this->id = $customers[$pos]['id'];
        $this->name = $customers[$pos]['name'];
        $this->since = $customers[$pos]['since'];
        $this->revenue = $customers[$pos]['revenue'];
    }

    /**
     * Customer id getter.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Customer revenue getter.
     *
     * @return float
     */
    public function getRevenue(): float
    {
        return $this->revenue;
    }

    /**
     * Retrieve customer data.
     *
     * @return array
     */
    private function getCustomers(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../data/customers.json'), true);
    }
}