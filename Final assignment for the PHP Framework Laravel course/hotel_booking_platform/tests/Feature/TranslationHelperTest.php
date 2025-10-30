<?php

namespace Tests\Feature;

use App\Http\Controllers\TranslationHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_format_price_with_different_locales()
    {
        app()->setLocale('en');
        $this->assertEquals('$110', TranslationHelper::formatPrice(10000));

        app()->setLocale('ru');
        $this->assertEquals('10 000 ₽', TranslationHelper::formatPrice(10000));

        app()->setLocale('de');
        $this->assertEquals('110 €', TranslationHelper::formatPrice(10000));

        app()->setLocale('fr');
        $this->assertEquals('110 €', TranslationHelper::formatPrice(10000));
    }

    public function test_format_price_with_zero_amount()
    {
        app()->setLocale('en');
        $this->assertEquals('$0', TranslationHelper::formatPrice(0));
    }

    public function test_format_price_with_large_amounts()
    {
        app()->setLocale('en');
        $this->assertEquals('$1,100', TranslationHelper::formatPrice(100000));
        $this->assertEquals('$11,000', TranslationHelper::formatPrice(1000000));
    }

    public function test_translate_facility_for_different_locales()
    {
        app()->setLocale('en');
        $result = TranslationHelper::translateFacility('Бассейн');
        $this->assertIsString($result);

        app()->setLocale('ru');
        $result = TranslationHelper::translateFacility('Бассейн');
        $this->assertIsString($result);

        $this->assertEquals('Unknown Facility', TranslationHelper::translateFacility('Unknown Facility'));
    }

    public function test_translate_facility_fallback_to_original()
    {
        app()->setLocale('en');
        $unknownFacility = 'Unknown Facility';
        $result = TranslationHelper::translateFacility($unknownFacility);
        $this->assertEquals($unknownFacility, $result);
    }

    public function test_translation_helper_get_currency()
    {
        app()->setLocale('en');
        $this->assertEquals('$', TranslationHelper::getCurrency());

        app()->setLocale('ru');
        $this->assertEquals('₽', TranslationHelper::getCurrency());

        app()->setLocale('de');
        $this->assertEquals('€', TranslationHelper::getCurrency());

        app()->setLocale('ar');
        $this->assertEquals('د.إ', TranslationHelper::getCurrency());
    }

    public function test_translation_helper_translate_room_type()
    {
        app()->setLocale('en');
        $result = TranslationHelper::translateRoomType('Стандарт');
        $this->assertIsString($result);

        app()->setLocale('ru');
        $result = TranslationHelper::translateRoomType('Люкс');
        $this->assertIsString($result);
    }

    public function test_translation_helper_handles_empty_strings()
    {
        app()->setLocale('en');

        $result = TranslationHelper::translateFacility('');
        $this->assertEquals('', $result);

        $result = TranslationHelper::translateRoomType('');
        $this->assertEquals('', $result);
    }

    public function test_translation_helper_handles_null_values()
    {
        app()->setLocale('en');

        $result = TranslationHelper::translateFacility(null);
        $this->assertEquals(null, $result);

        $result = TranslationHelper::translateRoomType(null);
        $this->assertEquals(null, $result);
    }

    public function test_currency_symbols_for_all_locales()
    {
        $expectedSymbols = [
            'en' => '$',
            'ru' => '₽',
            'de' => '€',
            'fr' => '€',
            'it' => '€',
            'es' => '€',
            'ar' => 'د.إ'
        ];

        foreach ($expectedSymbols as $locale => $expectedSymbol) {
            app()->setLocale($locale);
            $formatted = TranslationHelper::formatPrice(1000);
            $this->assertStringContainsString($expectedSymbol, $formatted);
        }
    }

    public function test_price_formatting_consistency()
    {
        $testPrices = [100, 1000, 10000, 100000];

        foreach ($testPrices as $price) {
            app()->setLocale('en');
            $formatted = TranslationHelper::formatPrice($price);
            $this->assertIsString($formatted);
            $this->assertNotEmpty($formatted);
            $this->assertStringContainsString('$', $formatted);
        }
    }

    public function test_facility_translation_exact_match()
    {
        app()->setLocale('ru');

        $this->assertEquals('Бассейн', TranslationHelper::translateFacility('Бассейн'));
        $this->assertEquals('wifi', TranslationHelper::translateFacility('wifi')); // Возвращает как есть
        $this->assertEquals('WIFI', TranslationHelper::translateFacility('WIFI')); // Возвращает как есть
    }

    public function test_translation_helper_price_conversion_rates()
    {
        app()->setLocale('en');
        $usd = TranslationHelper::formatPrice(1000);
        $this->assertStringContainsString('$', $usd);

        app()->setLocale('ru');
        $rub = TranslationHelper::formatPrice(1000);
        $this->assertStringContainsString('₽', $rub);
    }

    public function test_translation_helper_methods_exist()
    {
        $this->assertTrue(method_exists(TranslationHelper::class, 'formatPrice'));
        $this->assertTrue(method_exists(TranslationHelper::class, 'translateFacility'));
        $this->assertTrue(method_exists(TranslationHelper::class, 'translateRoomType'));
        $this->assertTrue(method_exists(TranslationHelper::class, 'getCurrency'));
    }

    public function test_price_formatting_with_decimal_amounts()
    {
        app()->setLocale('en');

        $this->assertIsString(TranslationHelper::formatPrice(1050)); // 10.50
        $this->assertIsString(TranslationHelper::formatPrice(999)); // 9.99
    }

    public function test_facility_translation_with_special_characters()
    {
        app()->setLocale('en');

        $facilityWithSpaces = 'Air Conditioning';
        $result = TranslationHelper::translateFacility($facilityWithSpaces);
        $this->assertIsString($result);
    }

    public function test_translation_helper_with_edge_cases()
    {
        app()->setLocale('en');

        $result = TranslationHelper::formatPrice(999999999);
        $this->assertIsString($result);
        $this->assertStringContainsString('$', $result);

        $result = TranslationHelper::formatPrice(-1000);
        $this->assertIsString($result);
    }
}
