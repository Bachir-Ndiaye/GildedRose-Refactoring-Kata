<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\EnumItem;
use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    // -1 sur un item avant date de péremption
    public function testNormalItemMinusOneQualityBeforeSellDate(): void
    {
        $items = [new Item('Normal Item Minus One Before Sell Date', 10, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sellIn);
        $this->assertEquals(19, $items[0]->quality);
    }

    public function testNormalItemMinusTwoQualityAfterSellDate(): void
    {
        $items = [new Item('Normal Item Minus Two After Sell Date', 0, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sellIn);
        $this->assertEquals(18, $items[0]->quality);
    }

    public function testNonNegativeValueForQuality(): void
    {
        $items = [new Item('Non Negative Value for Quality', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->quality);
    }

    public function testAgedBrieIncreaseOneQuality(): void
    {
        $items = [new Item(EnumItem::AGED_BRIE->value, 10, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sellIn);
        $this->assertEquals(21, $items[0]->quality);
    }

    public function testAgedBrieIncreaseTwiceQualityAfterSellDate(): void
    {
        $items = [new Item(EnumItem::AGED_BRIE->value, 0, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sellIn);
        $this->assertEquals(22, $items[0]->quality);
    }

    public function testSulfurasNeverChanges(): void
    {
        $items = [new Item(EnumItem::SULFURAS->value, 0, 90)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->sellIn);
        $this->assertEquals(90, $items[0]->quality);
    }

    public function testBackstageIncreasesByOneMoreTenDays(): void
    {
        $items = [new Item(EnumItem::BACKSTAGE->value, 15, 30)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(31, $items[0]->quality);
    }

    public function testBackstageIncreasesByTwoEqualOrLessTenDays(): void
    {
        $items = [new Item(EnumItem::BACKSTAGE->value, 10, 30)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(32, $items[0]->quality);
    }

    public function testNormalItemNotOverFiftyInQuality(): void
    {
        $items = [new Item(EnumItem::BACKSTAGE->value, 10, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(50, $items[0]->quality);
    }
}
