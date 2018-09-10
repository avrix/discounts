<?php

namespace discounts;

use Exception;

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

    /**
     * Customer constructor.
     *
     * @param int $customerId
     *
     * @throws Exception
     */
    public function __construct(int $customerId)
    {
        $customer = $this->getCustomerById($customerId);

        $this->id = $customer['id'];
        $this->name = $customer['name'];
        $this->since = $customer['since'];
        $this->revenue = $customer['revenue'];
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

    /**
     * Get customer by id.
     *
     * @param int $customerId
     *
     * @throws Exception
     *
     * @return array
     */
    private function getCustomerById(int $customerId): array
    {
        $customers = $this->getCustomers();

        $pos = array_search($customerId, array_column($customers, 'id'));

        if ($pos === false) {
            throw new Exception('Customer does not exist');
        }

        return $customers[$pos];
    }
}