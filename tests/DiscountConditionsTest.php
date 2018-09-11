<?php
declare(strict_types=1);

namespace discounts\tests;

use discounts\Customer;
use discounts\DiscountConditions\CustomerRevenue;
use discounts\DiscountConditions\Decorators\MinimumIdenticalItems;
use discounts\DiscountConditions\Decorators\MinimumItems;
use discounts\Items\ItemList;
use discounts\Items\Switches as SwitchesItems;
use discounts\Items\Tools as ToolsItems;
use discounts\DiscountConditions\Switches;
use discounts\DiscountConditions\Tools;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountConditionsTest.
 */
class DiscountConditionsTest extends TestCase
{
    public function testOrderHasSwitches()
    {
        // Mock ItemList.
        $itemList = $this->createMock(ItemList::class);
        $switchItem = $this->createMock(SwitchesItems::class);
        $toolsItem = $this->createMock(ToolsItems::class);

        // Configure the stub.
        $itemList->method('getItems')
            ->willReturn([$switchItem, $toolsItem]);

        $switches = new Switches($itemList);
        $this->assertTrue($switches->isFulfilled());
    }

    public function testOrderDoesntHaveSwitches()
    {
        // Mock ItemList.
        $itemList = $this->createMock(ItemList::class);
        $toolsItem = $this->createMock(ToolsItems::class);

        // Configure the stub.
        $itemList->method('getItems')
            ->willReturn([$toolsItem]);

        $switches = new Switches($itemList);
        $this->assertFalse($switches->isFulfilled());
    }

    public function testOrderHasTools()
    {
        // Mock ItemList.
        $itemList = $this->createMock(ItemList::class);
        $switchItem = $this->createMock(SwitchesItems::class);
        $toolsItem = $this->createMock(ToolsItems::class);

        // Configure the stub.
        $itemList->method('getItems')
            ->willReturn([$switchItem, $toolsItem]);

        $tools = new Tools($itemList);
        $this->assertTrue($tools->isFulfilled());
    }

    public function testOrderDoesntHaveTools()
    {
        $itemList = $this->createMock(ItemList::class);
        $switchItem = $this->createMock(SwitchesItems::class);

        // Configure the stub.
        $itemList->method('getItems')
            ->willReturn([$switchItem]);

        $tools = new Tools($itemList);
        $this->assertFalse($tools->isFulfilled());
    }

    public function testCustomerRevenueCondition()
    {
        $customer = $this->createMock(Customer::class);
        $customer->method('getRevenue')->willReturn(2.99);

        $customerRevenueCondition = new CustomerRevenue($customer, 100);

        $this->assertFalse($customerRevenueCondition->isFulfilled());
    }

    public function testMinimumItemsCondition()
    {
        $valuesMap = [
            (object) ['quantity' => 2],
            (object) ['quantity' => 10],
        ];

        $itemsCondition = $this->createMock(Switches::class);
        $itemsCondition->method('getItems')->willReturn($valuesMap);
        $itemsCondition->method('isFulfilled')->willReturn(true);

        $condition = new MinimumItems($itemsCondition, 5);
        $this->assertTrue($condition->isFulfilled());
    }

    public function testMinimumIdenticalItemsCondition()
    {
        $valuesMap = [
            (object) ['quantity' => 2],
            (object) ['quantity' => 10],
        ];

        $itemsCondition = $this->createMock(Switches::class);
        $itemsCondition->method('getItems')->willReturn($valuesMap);
        $itemsCondition->method('isFulfilled')->willReturn(true);

        $condition = new MinimumIdenticalItems($itemsCondition, 10);
        $this->assertTrue($condition->isFulfilled());
        $this->assertEquals(1, count($condition->getItemsHavingConditionFulfilled()));
    }
}