<?php

namespace App\Http\Controllers;

class TranslationHelper
{
    public static function translateFacility($title)
    {
        $translations = [
            'Бассейн' => __('facility.pool'),
            'Парковка' => __('facility.parking'),
            'Wi-Fi на всей территории' => __('facility.wifi_everywhere'),
            'Ресторан' => __('facility.restaurant'),
            'Бар' => __('facility.bar'),
            'Фитнес-центр' => __('facility.fitness_center'),
            'Спа-центр' => __('facility.spa_center'),
            'Спа' => __('facility.spa_center'),
            'Кондиционер' => __('facility.ac'),
            'Мини-бар' => __('facility.minibar'),
            'Сейф' => __('facility.safe'),
            'Чайник/кофеварка' => __('facility.tea_coffee'),
            'Балкон' => __('facility.balcony'),
            'Рабочий стол' => __('facility.work_desk'),
            'Халат' => __('facility.bathrobe'),
            'Собственная ванная комната' => __('facility.private_bathroom'),
            'Телевизор с плоским экраном' => __('facility.flat_tv'),
            'Бесплатный Wi-Fi в номере' => __('facility.free_wifi_in_room'),
            'Шкаф или гардероб' => __('facility.closet'),
            'Фен' => __('facility.hair_dryer'),
            'Тапочки' => __('facility.slippers'),
            'Трансфер от/до аэропорта' => __('facility.airport_transfer'),
            'Консьерж' => __('facility.concierge'),
            'Трансфер' => __('facility.transfer')
        ];

        return $translations[$title] ?? $title;
    }

    public static function translateRoomType($type)
    {
        $translations = [
            'Стандарт' => __('room_type.standard'),
            'Комфорт' => __('room_type.comfort'),
            'Полулюкс' => __('room_type.junior_suite'),
            'Люкс' => __('room_type.suite'),
            'Президентский люкс' => __('room_type.presidential_suite'),
        ];

        return $translations[$type] ?? $type;
    }

    public static function getCurrency()
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'ru' => '₽',
            'fr', 'de', 'it', 'es' => '€',
            'ar' => 'د.إ',
            default => '$'
        };
    }

    public static function formatPrice($price)
    {
        $locale = app()->getLocale();
        $currency = self::getCurrency();

        return match ($locale) {
            'ru' => number_format($price, 0, '.', ' ') . ' ' . $currency,
            'fr', 'de', 'it', 'es' => number_format($price * 0.011, 0, '.', ' ') . ' ' . $currency,
            'ar' => number_format($price * 0.041, 0, '.', ' ') . ' ' . $currency,
            default => '$' . number_format($price * 0.011, 0)
        };
    }
}