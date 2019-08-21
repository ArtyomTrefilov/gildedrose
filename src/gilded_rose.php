<?php

class GildedRose {

    //Наименования товаров
    const DEXTERITY = '+5 Dexterity Vest';
    const AGED_BRIE = 'Aged Brie';
    const ELIXIR    = 'Elixir of the Mongoose';
    const SULFURAS  = 'Sulfuras, Hand of Ragnaros';
    const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    const CONJURED  = 'Conjured Mana Cake';
    
    /**
     * Максимальное качество товара
     */
    const MAX_QUALITY = 50;
    
    /**
     * Минимальное качество товара
     */
    const MIN_QUALITY = 0;
    
    private $items;
    
    /**
     * Конструктор
     * @param array $items - массив характеристик товатов
     */
    function __construct($items) {        
        $this->items = $items;
    }
    
    /**
     * Изменение изменение качества товара за 1 день с учетов срока хранения товара
     */       
    function update_quality() {       
        foreach ($this->items as $item) {
            //товар «Sulfuras» не подвержен ухудшению качества, поэтому пропускаем его;           
            if ($item->name == self::SULFURAS) {
                continue;
            }            
            /*
             * Если у товара со временем ухудшается качество и качество уже 0,
             * то переходим к следующему товару
             */            
            if (($item->name != self::AGED_BRIE && $item->name != self::BACKSTAGE) && $item->quality == self::MIN_QUALITY) {
                $item->sell_in--;
                continue;
            }
             
            //Скидываем срок хранения на 1 день
            $item->sell_in--;             
            
            switch ($item->name) {
                case self::DEXTERITY: {
                    $item->quality = self::getQualSimple($item->sell_in, $item->quality);
                    continue;
                }
                case self::ELIXIR: {
                    $item->quality = self::getQualSimple($item->sell_in, $item->quality);
                    continue;
                }
                case self::AGED_BRIE: {                    
                    $item->quality = self::getQualAged($item->sell_in, $item->quality);                    
                    continue;
                }
                case self::BACKSTAGE: {
                    $item->quality = self::getQualBack($item->sell_in, $item->quality);
                    continue;
                }
                case self::CONJURED: {
                    $item->quality = self::getQualConj($item->sell_in, $item->quality);
                    continue;
                }
            }
        }
    }
    
    /**
     * Расчет качества для обычных товаров
     * @param int $sellIn  - срок хранения
     * @param int $quality - качество товара
     * @return int
     */
    static private function getQualSimple($sellIn, $quality) {
        if ($sellIn < 0) {            
            return $quality > 1 ? $quality - 2 : self::MIN_QUALITY;
        } else {                
            return $quality > 0 ? $quality - 1 : self::MIN_QUALITY;
        }
    }
    
    /**
     * Расчет качества для обычных товароа "Aged Brie"
     * @param int $sellIn  - срок хранения
     * @param int $quality - качество товара
     * @return int
     */
    static private function getQualAged($sellIn, $quality) {
        if ($sellIn < 0) {            
            if ($quality < self::MAX_QUALITY - 1) {
                return $quality + 2;
            }
        } else {
            if ($quality < self::MAX_QUALITY) {
                return $quality + 1;
            }               
        } 
        return self::MAX_QUALITY;
    }
    
    /**
     * Расчет качества для обычных товароа "Backstage"
     * @param int $sellIn  - срок хранения
     * @param int $quality - качество товара
     * @return int
     */
    static private function getQualBack($sellIn, $quality) {
        if ($sellIn < 0) {            
            return 0;
        } else {                
            if ($quality < self::MAX_QUALITY) {
                $quality++;
                if ($sellIn < 10 && $quality < self::MAX_QUALITY) {
                    $quality++;
                }
                if ($sellIn < 5 && $quality < self::MAX_QUALITY) {
                    $quality++;
                }
                return $quality;
            } else {
               return self::MAX_QUALITY;  
            }    
        }
    }
    
    /**
     * Расчет качества для обычных товароа "Conjured"
     * @param int $sellIn  - срок хранения
     * @param int $quality - качество товара
     * @return int
     */
    static private function getQualConj($sellIn, $quality) {
        if ($sellIn < 0) {            
            return $quality > 3 ? $quality - 4 : self::MIN_QUALITY;
        } else {                
            return $quality > 1 ? $quality - 2 : self::MIN_QUALITY;
        }
    }
}

class Item {
    
    public $name;
    public $sell_in;
    public $quality;

    /**
     * Конструктор
     * @param string $name - наименование товара
     * @param int    $sell_in - срок хранения
     * @param int    $quality - качество товара
     */
    function __construct($name, $sell_in, $quality) {        
        $this->name = $name;  
        $this->sell_in = $sell_in;        
        $this->quality = $quality;
        
    }
    
    /**
     * Вывод в строку
     * @return sring
     */
    public function __toString() {        
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }

}

