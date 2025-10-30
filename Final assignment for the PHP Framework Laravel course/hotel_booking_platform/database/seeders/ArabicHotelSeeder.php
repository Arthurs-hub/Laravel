<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class ArabicHotelSeeder extends Seeder
{
    public function run(): void
    {
        $arabicHotels = [
            // Russia
            'The Ritz-Carlton Moscow' => [
                'title' => 'فندق ريتز كارلتون موسكو',
                'address' => 'شارع تفيرسكايا 3، موسكو',
                'description' => 'فندق فاخر في قلب موسكو مع إطلالة على الكرملين.'
            ],
            'Four Seasons Hotel Moscow' => [
                'title' => 'فندق فور سيزونز موسكو',
                'address' => 'أوخوتني رياد 2، موسكو',
                'description' => 'فندق أنيق بالقرب من الساحة الحمراء ومسرح البولشوي.'
            ],
            'Hotel Metropol Moscow' => [
                'title' => 'فندق متروبول موسكو',
                'address' => 'تياترالني بروزد 2، موسكو',
                'description' => 'فندق تاريخي بطراز الآرت نوفو مع هندسة معمارية فريدة.'
            ],
            'Ararat Park Hyatt Moscow' => [
                'title' => 'فندق أرارات بارك حياة موسكو',
                'address' => 'شارع نيغلينايا 4، موسكو',
                'description' => 'فندق حديث بإطلالات بانورامية على وسط موسكو.'
            ],
            'The St. Regis Moscow' => [
                'title' => 'فندق سانت ريجيس موسكو',
                'address' => 'شارع نيكولسكايا 12، موسكو',
                'description' => 'فندق راقي مع خدمة الخادم الشخصي وغرف فاخرة.'
            ],

            // Norway
            'Hotel Continental Oslo' => [
                'title' => 'فندق كونتيننتال أوسلو',
                'address' => 'ستورتينغسغاتا 24/26، أوسلو',
                'description' => 'فندق تاريخي في وسط أوسلو مع ضيافة نرويجية تقليدية.'
            ],
            'The Thief Oslo' => [
                'title' => 'فندق ذا ثيف أوسلو',
                'address' => 'لاندغانغن 1، أوسلو',
                'description' => 'فندق تصميمي حديث على شبه جزيرة تيوفهولمن مع إطلالة على الفيورد.'
            ],
            'Grand Hotel Oslo' => [
                'title' => 'فندق غراند أوسلو',
                'address' => 'كارل يوهانس غيت 31، أوسلو',
                'description' => 'فندق أسطوري في الشارع الرئيسي لأوسلو مع تاريخ عريق.'
            ],
            'Radisson Blu Plaza Hotel Oslo' => [
                'title' => 'فندق راديسون بلو بلازا أوسلو',
                'address' => 'سونيا هينيس بلاس 3، أوسلو',
                'description' => 'فندق ناطحة سحاب بإطلالات بانورامية على المدينة والفيورد.'
            ],

            // USA
            'The Plaza New York' => [
                'title' => 'فندق بلازا نيويورك',
                'address' => '768 الجادة الخامسة، نيويورك، نيويورك',
                'description' => 'فندق أسطوري على الجادة الخامسة مع إطلالة على سنترال بارك.'
            ],
            'The St. Regis New York' => [
                'title' => 'فندق سانت ريجيس نيويورك',
                'address' => '2 شارع 55 الشرقي، نيويورك، نيويورك',
                'description' => 'فندق فاخر في ميدتاون مع خدمة الخادم الشخصي.'
            ],
            'Four Seasons Hotel New York' => [
                'title' => 'فندق فور سيزونز نيويورك',
                'address' => 'شارع 57، نيويورك، نيويورك',
                'description' => 'فندق حديث بإطلالات بانورامية على المدينة.'
            ],

            // France
            'The Ritz Paris' => [
                'title' => 'فندق ريتز باريس',
                'address' => '15 ساحة فاندوم، باريس',
                'description' => 'فندق أسطوري في ساحة فاندوم بتاريخ عريق.'
            ],
            'Four Seasons Hotel George V' => [
                'title' => 'فندق فور سيزونز جورج الخامس',
                'address' => '31 شارع جورج الخامس، باريس',
                'description' => 'فندق فاخر بالقرب من الشانزليزيه.'
            ],

            // Germany
            'Hotel Adlon Kempinski Berlin' => [
                'title' => 'فندق أدلون كيمبينسكي برلين',
                'address' => 'أونتر دين ليندن 77، برلين',
                'description' => 'فندق أسطوري عند بوابة براندنبورغ.'
            ],

            // Italy
            'Gritti Palace Venice' => [
                'title' => 'قصر غريتي البندقية',
                'address' => 'كامبو سانتا ماريا ديل جيليو 2467، البندقية',
                'description' => 'فندق قصر على القناة الكبرى في البندقية.'
            ],

            // Spain
            'Hotel Casa Fuster Barcelona' => [
                'title' => 'فندق كاسا فوستر برشلونة',
                'address' => 'باسيو دي غراسيا 132، برشلونة',
                'description' => 'فندق حداثي في باسيو دي غراسيا.'
            ],

            // United Kingdom
            'Claridges London' => [
                'title' => 'فندق كلاريدجز لندن',
                'address' => 'شارع بروك، لندن W1K 4HR',
                'description' => 'أيقونة الأناقة البريطانية في مايفير.'
            ],

            // Japan
            'Mandarin Oriental Tokyo' => [
                'title' => 'فندق ماندارين أورينتال طوكيو',
                'address' => '2-1-1 نيهونباشي موروماتشي، تشو سيتي، طوكيو',
                'description' => 'فندق حديث مطل على نهر سوميدا.'
            ],

            // China
            'Shangri-La Hotel Beijing' => [
                'title' => 'فندق شانغريلا بكين',
                'address' => '29 طريق زيزويوان، بكين',
                'description' => 'فندق بحدائق تقليدية في وسط المدينة.'
            ],

            // India
            'Rambagh Palace Jaipur' => [
                'title' => 'قصر رامباغ جايبور',
                'address' => 'طريق بهاواني سينغ، جايبور',
                'description' => 'قصر مهراجا سابق تحول إلى فندق.'
            ],

            // Brazil
            'Hotel Unique São Paulo' => [
                'title' => 'فندق يونيك ساو باولو',
                'address' => 'أفينيدا بريغاديرو لويس أنطونيو 4700، ساو باولو',
                'description' => 'فندق تصميمي على شكل بطيخة.'
            ],

            // Canada
            'The Ritz-Carlton Toronto' => [
                'title' => 'فندق ريتز كارلتون تورونتو',
                'address' => '181 شارع ويلينغتون الغربي، تورونتو، أونتاريو',
                'description' => 'فندق أنيق في الحي المالي.'
            ],

            // Australia
            'Park Hyatt Melbourne' => [
                'title' => 'فندق بارك حياة ملبورن',
                'address' => '1 ساحة البرلمان، ملبورن، فيكتوريا',
                'description' => 'فندق حديث على ضفاف نهر يارا.'
            ],

            // Mexico
            'Banyan Tree Mayakoba' => [
                'title' => 'فندق بانيان تري مايكوبا',
                'address' => 'كاريتيرا فيديرال كانكون-بلايا ديل كارمن كم 298',
                'description' => 'فيلات على الماء في محمية طبيعية.'
            ],

            // Turkey
            'Six Senses Kaplankaya' => [
                'title' => 'فندق سيكس سينسز كابلانكايا',
                'address' => 'كابلانكايا، بودروم',
                'description' => 'منتجع بيئي على الساحل الإيجي.'
            ],

            // Thailand
            'The St. Regis Bangkok' => [
                'title' => 'فندق سانت ريجيس بانكوك',
                'address' => '159 طريق راجادامري، بانكوك',
                'description' => 'فندق فاخر في راجادامري رود.'
            ],

            // South Korea
            'Banyan Tree Club & Spa Seoul' => [
                'title' => 'فندق بانيان تري كلوب وسبا سيول',
                'address' => '60 جانغتشونغدان-رو، جونغ-غو، سيول',
                'description' => 'منتجع حضري بمركز سبا.'
            ],

            // UAE
            'The Ritz-Carlton Dubai' => [
                'title' => 'فندق ريتز كارلتون دبي',
                'address' => 'مركز دبي المالي العالمي، دبي',
                'description' => 'فندق فاخر في المركز المالي.'
            ],

            // Singapore
            'Four Seasons Hotel Singapore' => [
                'title' => 'فندق فور سيزونز سنغافورة',
                'address' => '190 أورتشارد بوليفارد، سنغافورة',
                'description' => 'فندق أنيق في أورتشارد رود.'
            ]
        ];

        foreach ($arabicHotels as $originalTitle => $arabicData) {
            Hotel::where('title', $originalTitle)->update([
                'title' => $arabicData['title'],
                'address' => $arabicData['address'],
                'description' => $arabicData['description']
            ]);
        }
    }
}