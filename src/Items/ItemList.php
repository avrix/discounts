<?php

namespace discounts\Items;

use Exception;

class ItemList
{
    /**
     * List of item objects.
     *
     * @var array
     */
    private $items;

    /**
     * Products list holder.
     *
     * @var array
     */
    private $availableProducts;

    /**
     * ItemList constructor.
     *
     * @param array $items
     *
     * @throws Exception
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $itemCategory = $this->identifyItemCategory($item);
            switch($itemCategory) {
                case Switches::ITEM_CATEGORY:
                    $this->items[] = new Switches($item);
                    break;

                case Tools::ITEM_CATEGORY:
                    $this->items[] = new Tools($item);
                    break;

                default:
                    throw new Exception('Undefined item category');
            }
        }
    }

    /**
     * Item list getter.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get product category by item id.
     *
     * @param array $item
     *
     * @return int|null
     *
     * @throws Exception
     */
    private function identifyItemCategory(array $item): ?int
    {
        if (empty($this->availableProducts)) {
            $this->availableProducts = $this->getAvailableProducts();
        }

        if (!array_key_exists('product-id', $item)) {
            throw new Exception('Item has undefined property');
        }

        $productId = $item['product-id'];

        return array_key_exists($productId, $this->availableProducts) ? $this->availableProducts[$productId] : null;
    }

    /**
     * Get list of all available products.
     *
     * @return array
     */
    private function getAvailableProducts(): array
    {
        $products = json_decode(file_get_contents(__DIR__ . '/../../data/products.json'), true);

        return array_column($products, 'category', 'id');
    }
}