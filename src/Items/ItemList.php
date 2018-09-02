<?php

namespace discounts\Items;


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
                    $this->items[] = new Item($item);
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
     */
    private function identifyItemCategory(array $item): ?int
    {
        if (empty($this->availableProducts)) {
            $this->availableProducts = $this->getAvailableProducts();
        }

        return array_key_exists($item['product-id'], $this->availableProducts) ? $this->availableProducts[$item['product-id']] : null;
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