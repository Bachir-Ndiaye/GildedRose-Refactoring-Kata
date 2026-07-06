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
    const MIN_QUALITY = 0;
    const ZERO = 0;

    private function increaseQuality(Item $item): void
    {
        if($item->quality < self::MAX_QUALITY) {
            $item->quality++;
        }
    }

    private function decreaseQuality(Item $item): void
    {
        if($item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
    }

    private function increaseSellIn(Item $item): void
    {
        $item->sellIn++;
    }
    private function decreaseSellIn(Item $item): void
    {
        $item->sellIn--;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name){
                case EnumItem::AGED_BRIE->value: 
                    $this->updateAgedBrie($item); 
                    break;
                case EnumItem::BACKSTAGE->value:
                    $this->updateBackstage($item);
                    break;
                case EnumItem::SULFURAS->value:
                    $this->updatedSulfuras($item);
                    break;
                default:
                    $this->updateNormalItem($item);
            }
        }
    }

    private function updateNormalItem(Item $item): void
    {
        $this->decreaseQuality($item);
        $this->decreaseSellIn($item);

        if($item->sellIn < self::ZERO) {
            $this->decreaseQuality($item);
        }
    }

    private function updateAgedBrie(Item $item): void
    {
        $this->increaseQuality($item);
        $this->decreaseSellIn($item);

        if($item->sellIn < self::ZERO) {
            $this->increaseQuality($item);
        }
    }

    private function updateBackstage(Item $item): void
    {
        $this->increaseQuality($item);

        if($item->sellIn <= 10) {
            $this->increaseQuality($item);
        }

        if($item->sellIn <= 5) {
            $this->increaseQuality($item);
        }
        $this->decreaseSellIn($item);

        if($item->sellIn < self::ZERO) {
            $this->setQualityToZero($item);
        }
    }

    private function setQualityToZero(Item $item): void
    {
        $item->quality = self::ZERO;
    }

    private function updatedSulfuras(Item $item): void
    {
        // "Sulfuras" est un objet légendaire et comme tel sa qualité est de 80 et elle ne change jamais.
    }
}
