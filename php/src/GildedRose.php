<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }
    const MAX_QUALITY = 50;
    const ZERO = 0;
    
    public function increaseQuality(Item $item): void
    {
        if($item->quality < self::MAX_QUALITY) {
            $item->quality++;
        }
    }

    public function decreaseQuality(Item $item): void
    {
        $item->quality--;
    }

    public function increaseSellIn(Item $item): void
    {
        $item->sellIn++;
    }
    public function decreaseSellIn(Item $item): void
    {
        $item->sellIn--;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name != EnumItem::AGED_BRIE->value and $item->name != EnumItem::BACKSTAGE->value) {
                if ($item->quality > self::ZERO) {
                    if ($item->name != EnumItem::SULFURAS->value) {
                        $this->decreaseQuality($item);
                    }
                }
            } else {
                if ($item->quality < self::MAX_QUALITY) {
                    $this->increaseQuality($item);
                    if ($item->name == EnumItem::BACKSTAGE->value) {
                        if ($item->sellIn < 11) {
                            if ($item->quality < self::MAX_QUALITY) {
                                $this->increaseQuality($item);
                            }
                        }
                        if ($item->sellIn < 6) {
                            if ($item->quality < self::MAX_QUALITY) {
                                $this->increaseQuality($item);
                            }
                        }
                    }
                }
            }

            if ($item->name != EnumItem::SULFURAS->value) {
                $this->decreaseSellIn($item);
            }

            if ($item->sellIn < self::ZERO) {
                if ($item->name != EnumItem::AGED_BRIE->value) {
                    if ($item->name != EnumItem::BACKSTAGE->value) {
                        if ($item->quality > self::ZERO) {
                            if ($item->name != EnumItem::SULFURAS->value) {
                                $this->decreaseQuality($item);
                            }
                        }
                    } else {
                        $item->quality = self::ZERO;
                    }
                } else {
                    if ($item->quality < self::MAX_QUALITY) {
                        $this->increaseQuality($item);
                    }
                }
            }
        }
    }
}
