<?php

namespace App\Helpers;

class TranslationHelper
{
    public static function translateRoomType($roomType)
    {
        $translations = [
            'Стандарт' => __('Standard Room'),
            'Комфорт' => __('Comfort Room'),
            'Полулюкс' => __('Junior Suite'),
            'Люкс' => __('Suite'),
            'Президентский люкс' => __('Presidential Suite'),
            'Президентский' => __('Presidential Suite'),
        ];

        return $translations[$roomType] ?? $roomType;
    }


    public static function translateRoomDescription($roomType)
    {
        if (str_starts_with($roomType, 'rooms.description.')) {
            return __($roomType);
        }

        $descriptions = [
            'Стандарт' => __('rooms.description.standard'),
            'Комфорт' => __('rooms.description.comfort'),
            'Полулюкс' => __('rooms.description.junior_suite'),
            'Люкс' => __('rooms.description.suite'),
            'Президентский люкс' => __('rooms.description.presidential_suite'),
            'Президентский' => __('rooms.description.presidential_suite'),
        ];

        return $descriptions[$roomType] ?? __('rooms.description');
    }


    public static function formatPrice($price)
    {
        $locale = app()->getLocale();

        $convertedPrice = self::convertFromRubles($price, $locale);

        $currencies = [
            'ru' => '₽',
            'en' => '$',
            'ar' => 'د.إ',
            'de' => '€',
            'it' => '€',
            'fr' => '€',
            'es' => '€',
        ];

        $currency = $currencies[$locale] ?? '₽';
        $formattedPrice = number_format($convertedPrice, 0, ',', ' ');

        return $formattedPrice . ' ' . $currency;
    }


    public static function convertFromRubles($priceInRubles, $locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        $exchangeRates = [
            'ru' => 1.0,
            'en' => 0.011,
            'ar' => 0.040,
            'de' => 0.010,
            'it' => 0.010,
            'fr' => 0.010,
            'es' => 0.010,
        ];

        $rate = $exchangeRates[$locale] ?? 1.0;
        return round($priceInRubles * $rate);
    }


    public static function translateBookingStatus($status)
    {
        $locale = app()->getLocale();

        $translations = [
            'ru' => [
                'Подтверждено' => 'Подтверждено',
                'В ожидании' => 'В ожидании',
                'Отменено' => 'Отменено',
            ],
            'en' => [
                'Подтверждено' => 'Confirmed',
                'В ожидании' => 'Pending',
                'Отменено' => 'Cancelled',
            ],
            'ar' => [
                'Подтверждено' => 'مؤكد',
                'В ожидании' => 'في الانتظار',
                'Отменено' => 'ملغي',
            ],
            'de' => [
                'Подтверждено' => 'Bestätigt',
                'В ожидании' => 'Ausstehend',
                'Отменено' => 'Storniert',
            ],
            'it' => [
                'Подтверждено' => 'Confermato',
                'В ожидании' => 'In attesa',
                'Отменено' => 'Annullato',
            ],
            'fr' => [
                'Подтверждено' => 'Confirmé',
                'В ожидании' => 'En attente',
                'Отменено' => 'Annulé',
            ],
            'es' => [
                'Подтверждено' => 'Confirmado',
                'В ожидании' => 'Pendiente',
                'Отменено' => 'Cancelado',
            ],
        ];

        return $translations[$locale][$status] ?? $status;
    }


    public static function getCurrency($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        $currencies = [
            'ru' => '₽',
            'en' => '$',
            'ar' => 'د.إ',
            'de' => '€',
            'it' => '€',
            'fr' => '€',
            'es' => '€',
        ];

        return $currencies[$locale] ?? '₽';
    }


    public static function getCurrencySymbol()
    {
        $locale = app()->getLocale();

        $currencies = [
            'ru' => '₽',
            'en' => '$',
            'ar' => 'د.إ',
            'de' => '€',
            'it' => '€',
            'fr' => '€',
            'es' => '€',
        ];

        return $currencies[$locale] ?? '₽';
    }


    public static function translateFacility($facilityTitle)
    {
        if (str_starts_with($facilityTitle, 'facility.')) {
            return __($facilityTitle);
        }

        $translations = [
            'Бассейн' => __('facility.pool'),
            'Парковка' => __('facility.parking'),
            'Wi-Fi на всей территории' => __('facility.wifi_everywhere'),
            'Wi-Fi' => __('facility.wifi_everywhere'),
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
            'Трансфер' => __('facility.transfer'),


            'facility.parking' => __('facility.parking'),
            'facility.pool' => __('facility.pool'),
            'facility.spa_center' => __('facility.spa_center'),
            'facility.restaurant' => __('facility.restaurant'),
            'facility.fitness_center' => __('facility.fitness_center'),
            'facility.bar' => __('facility.bar'),
        ];

        $result = $translations[$facilityTitle] ?? null;

        if (!$result) {
            $lowerTitle = strtolower($facilityTitle);
            if (strpos($lowerTitle, 'parking') !== false || strpos($lowerTitle, 'парковка') !== false) {
                return __('facility.parking');
            }
            if (strpos($lowerTitle, 'pool') !== false || strpos($lowerTitle, 'бассейн') !== false) {
                return __('facility.pool');
            }
            if (strpos($lowerTitle, 'spa') !== false || strpos($lowerTitle, 'спа') !== false) {
                return __('facility.spa_center');
            }
            if (strpos($lowerTitle, 'restaurant') !== false || strpos($lowerTitle, 'ресторан') !== false) {
                return __('facility.restaurant');
            }
            if (strpos($lowerTitle, 'fitness') !== false || strpos($lowerTitle, 'фитнес') !== false) {
                return __('facility.fitness_center');
            }
            if (strpos($lowerTitle, 'wifi') !== false || strpos($lowerTitle, 'wi-fi') !== false) {
                return __('facility.wifi_everywhere');
            }
            if (strpos($lowerTitle, 'minibar') !== false || strpos($lowerTitle, 'мини-бар') !== false) {
                return __('facility.minibar');
            }
            if (strpos($lowerTitle, 'air') !== false || strpos($lowerTitle, 'кондиционер') !== false) {
                return __('facility.ac');
            }
        }

        return $result ?? $facilityTitle;
    }


    public static function getExchangeRate($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }

        $exchangeRates = [
            'ru' => 1.0,
            'en' => 0.011,
            'ar' => 0.040,
            'de' => 0.010,
            'it' => 0.010,
            'fr' => 0.010,
            'es' => 0.010,
        ];

        return $exchangeRates[$locale] ?? 1.0;
    }


    public static function convertPriceForEdit($priceInRubles)
    {
        $locale = app()->getLocale();
        $exchangeRate = self::getExchangeRate($locale);

        return round($priceInRubles * $exchangeRate, 2);
    }


    public static function translateHotelDescription($description)
    {
        if (!$description) {
            return '';
        }

        $translations = [
            'Современный отель в торговом районе' => __('hotel.modern_hotel_business_district'),
            'Роскошный отель в центре города' => __('hotel.luxury_hotel_city_center'),
            'Уютный отель рядом с пляжем' => __('hotel.cozy_hotel_near_beach'),
            'Элегантный отель в историческом центре' => __('hotel.elegant_hotel_historic_center'),
            'Бутик-отель с видом на море' => __('hotel.boutique_hotel_sea_view'),
            'Семейный отель в тихом районе' => __('hotel.family_hotel_quiet_area'),
            'Отель класса люкс с спа-центром' => __('hotel.luxury_hotel_with_spa'),
            'Современный бизнес-отель' => __('hotel.modern_business_hotel'),
        ];

        return $translations[$description] ?? $description;
    }


    public static function getOriginalTitle($title)
    {
        return $title; 
    }


    public static function formatDateTime($dateTime, $format = 'd.m.Y H:i', $timezone = null)
    {
        if (!$dateTime) {
            return '';
        }

        if (!$dateTime instanceof \Carbon\Carbon) {
            $dateTime = \Carbon\Carbon::parse($dateTime);
        }

        $localTimezone = $timezone ?? config('app.timezone', 'Europe/Chisinau');
        return $dateTime->setTimezone($localTimezone)->format($format);
    }


    public static function formatDate($dateTime, $format = 'd.m.Y', $timezone = null)
    {
        return self::formatDateTime($dateTime, $format, $timezone);
    }


    public static function translateRoomTitle($roomTitle)
    {
        $translations = [
            'Номер Стандарт' => __('Standard Room'),
            'Номер Комфорт' => __('Comfort Room'),
            'Номер Полулюкс' => __('Junior Suite'),
            'Номер Люкс' => __('Suite'),
            'Президентский номер' => __('Presidential Suite'),
        ];

        return $translations[$roomTitle] ?? $roomTitle;
    }


    public static function getRoomDescription($roomType)
    {
        $descriptions = [
            'Стандарт' => __('rooms.description.standard'),
            'Комфорт' => __('rooms.description.comfort'),
            'Полулюкс' => __('rooms.description.junior_suite'),
            'Люкс' => __('rooms.description.suite'),
            'Президентский люкс' => __('rooms.description.presidential_suite'),
            'Президентский' => __('rooms.description.presidential'),
        ];

        return $descriptions[$roomType] ?? __('rooms.description');
    }
}
