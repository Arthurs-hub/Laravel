<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArabicTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        // Arabic descriptions for hotels
        $arabicDescriptions = [
            'The Ritz-Carlton Toronto' => 'فندق فاخر في المنطقة المالية بتورونتو مع إطلالات رائعة على المدينة وخدمة استثنائية.',
            'Four Seasons Hotel Sydney' => 'فندق راقي في قلب سيدني مع إطلالة على الميناء وخدمات عالمية المستوى.',
            'Park Hyatt Melbourne' => 'فندق بوتيك أنيق في ملبورن مع تصميم معاصر وموقع مثالي في وسط المدينة.',
            'Shangri-La Hotel Sydney' => 'فندق فاخر مع إطلالة خلابة على ميناء سيدني وخدمات متميزة.',
            'The Langham Melbourne' => 'فندق تاريخي أنيق مع ديكور كلاسيكي وخدمة شخصية راقية.',
            'Crown Towers Melbourne' => 'فندق فاخر مع كازينو ومرافق ترفيهية متنوعة في ملبورن.',
            'COMO The Treasury Perth' => 'فندق بوتيك فاخر في مبنى تراثي مع تصميم عصري في بيرث.',
            'The Calile Hotel Brisbane' => 'فندق عصري مع تصميم استوائي أنيق وإطلالة على النهر في بريسبان.',
            'Emporium Hotel South Bank Brisbane' => 'فندق بوتيك معاصر مع فن محلي وتصميم مبتكر في بريسبان.',
            'The Langham Sydney' => 'فندق فاخر في منطقة الصخور التاريخية مع إطلالة على الميناء.',
            
            'Copacabana Palace Rio de Janeiro' => 'فندق أيقوني على شاطئ كوباكابانا الشهير مع تاريخ عريق وأناقة برازيلية.',
            'Fasano Hotel Rio de Janeiro' => 'فندق بوتيك فاخر مع تصميم إيطالي راقي في إيبانيما.',
            'JW Marriott Hotel Rio de Janeiro' => 'فندق عصري على شاطئ كوباكابانا مع مرافق متطورة.',
            'Fairmont Rio de Janeiro Copacabana' => 'فندق فاخر مع إطلالة على المحيط وخدمات عالمية.',
            'Hotel Fasano Boa Vista' => 'منتجع فاخر في الريف البرازيلي مع طبيعة خلابة.',
            'Rosewood São Paulo' => 'فندق فاخر في قلب ساو باولو مع تصميم معاصر.',
            'Hotel Emiliano São Paulo' => 'فندق بوتيك أنيق مع خدمة شخصية متميزة.',
            'Grand Hyatt São Paulo' => 'فندق عصري في منطقة الأعمال مع مرافق شاملة.',
            'Hotel Unique São Paulo' => 'فندق مميز بتصميم معماري فريد وإطلالة بانورامية.',
            'Fasano São Paulo' => 'فندق إيطالي أنيق في قلب المدينة مع مطعم مشهور.',
            
            'Château Frontenac Quebec City' => 'فندق تاريخي أيقوني في كيبيك سيتي مع طابع فرنسي أصيل.',
            'Fairmont Chateau Lake Louise' => 'منتجع جبلي فاخر مع إطلالة على البحيرة والجبال الثلجية.',
            'The Ritz-Carlton Toronto' => 'فندق فاخر في وسط تورونتو مع خدمة استثنائية.',
            'Four Seasons Hotel Toronto' => 'فندق راقي في قلب المدينة مع مرافق عصرية.',
            'Four Seasons Hotel Vancouver' => 'فندق فاخر مع إطلالة على الجبال والمحيط.',
            'Four Seasons Hotel Montreal' => 'فندق أنيق في وسط مونتريال مع طابع فرنسي.',
            'Fairmont Hotel Vancouver' => 'فندق تاريخي فاخر في قلب فانكوفر.',
            'Fairmont Royal York Toronto' => 'فندق كلاسيكي أيقوني في تورونتو مع تاريخ عريق.',
            'The Ritz-Carlton Montreal' => 'فندق فاخر في المدينة القديمة مع أجواء أوروبية.',
            'Rosewood Hotel Georgia Vancouver' => 'فندق بوتيك تاريخي مع تجديد عصري أنيق.',
        ];

        // Arabic addresses for hotels
        $arabicAddresses = [
            'The Ritz-Carlton Toronto' => '181 شارع ويلينغتون الغربي، تورونتو، أونتاريو',
            'Four Seasons Hotel Sydney' => '199 شارع جورج، سيدني، نيو ساوث ويلز',
            'Park Hyatt Melbourne' => '1 شارع راسل الشرقي، ملبورن، فيكتوريا',
            'Shangri-La Hotel Sydney' => '176 شارع كمبرلاند، سيدني، نيو ساوث ويلز',
            'The Langham Melbourne' => '1 ساوثجيت أفينيو، ساوثبانك، ملبورن',
            'Crown Towers Melbourne' => '8 شارع وايتمان، ساوثبانك، ملبورن',
            'COMO The Treasury Perth' => '1 كاتدرائية أفينيو، بيرث، أستراليا الغربية',
            'The Calile Hotel Brisbane' => '218 شارع مارجريت، بريسبان، كوينزلاند',
            'Emporium Hotel South Bank Brisbane' => '267 شارع جراي، ساوث بريسبان، كوينزلاند',
            'The Langham Sydney' => '89-113 شارع كنت، سيدني، نيو ساوث ويلز',
            
            'Copacabana Palace Rio de Janeiro' => 'أفينيدا أتلانتيكا 1702، كوباكابانا، ريو دي جانيرو',
            'Fasano Hotel Rio de Janeiro' => 'أفينيدا فييرا سوتو 80، إيبانيما، ريو دي جانيرو',
            'JW Marriott Hotel Rio de Janeiro' => 'أفينيدا أتلانتيكا 2600، كوباكابانا، ريو دي جانيرو',
            'Fairmont Rio de Janeiro Copacabana' => 'أفينيدا أتلانتيكا 4240، كوباكابانا، ريو دي جانيرو',
            'Hotel Fasano Boa Vista' => 'فازيندا بوا فيستا، بورتو فيليز، ماتو جروسو',
            'Rosewood São Paulo' => 'روا إيتابيرا 124، بيلا فيستا، ساو باولو',
            'Hotel Emiliano São Paulo' => 'روا أوسكار فريري 384، سيردينيا، ساو باولو',
            'Grand Hyatt São Paulo' => 'أفينيدا داس ناسويس 1380، بروكلين نوفو، ساو باولو',
            'Hotel Unique São Paulo' => 'أفينيدا بريجاديرو لويس أنطونيو 4700، جاردين بوليستا، ساو باولو',
            'Fasano São Paulo' => 'روا فيتوريو فاسانو 88، سيردينيا، ساو باولو',
            
            'Château Frontenac Quebec City' => '1 روا دي كاريير، كيبيك سيتي، كيبيك',
            'Fairmont Chateau Lake Louise' => '111 بحيرة لويز درايف، بحيرة لويز، ألبرتا',
            'The Ritz-Carlton Toronto' => '181 شارع ويلينغتون الغربي، تورونتو، أونتاريو',
            'Four Seasons Hotel Toronto' => '60 شارع يورك، تورونتو، أونتاريو',
            'Four Seasons Hotel Vancouver' => '791 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
            'Four Seasons Hotel Montreal' => '1440 روا دي لا مونتاني، مونتريال، كيبيك',
            'Fairmont Hotel Vancouver' => '900 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
            'Fairmont Royal York Toronto' => '100 شارع فرونت الغربي، تورونتو، أونتاريو',
            'The Ritz-Carlton Montreal' => '1228 شيرمان شيرمان، مونتريال، كيبيك',
            'Rosewood Hotel Georgia Vancouver' => '801 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
        ];

        // Arabic hotel name translations
        $arabicTranslations = [
            'The Ritz-Carlton Toronto' => 'ريتز كارلتون تورونتو',
            'Four Seasons Hotel Sydney' => 'فندق فور سيزونز سيدني',
            'Park Hyatt Melbourne' => 'بارك حياة ملبورن',
            'Shangri-La Hotel Sydney' => 'فندق شانغريلا سيدني',
            'The Langham Melbourne' => 'لانغهام ملبورن',
            'Crown Towers Melbourne' => 'أبراج كراون ملبورن',
            'COMO The Treasury Perth' => 'كومو تريجري بيرث',
            'The Calile Hotel Brisbane' => 'فندق كاليل بريسبان',
            'Emporium Hotel South Bank Brisbane' => 'فندق إمبوريوم ساوث بانك بريسبان',
            'The Langham Sydney' => 'لانغهام سيدني',
            
            'Copacabana Palace Rio de Janeiro' => 'قصر كوباكابانا ريو دي جانيرو',
            'Fasano Hotel Rio de Janeiro' => 'فندق فاسانو ريو دي جانيرو',
            'JW Marriott Hotel Rio de Janeiro' => 'فندق جي دبليو ماريوت ريو دي جانيرو',
            'Fairmont Rio de Janeiro Copacabana' => 'فيرمونت ريو دي جانيرو كوباكابانا',
            'Hotel Fasano Boa Vista' => 'فندق فاسانو بوا فيستا',
            'Rosewood São Paulo' => 'روزوود ساو باولو',
            'Hotel Emiliano São Paulo' => 'فندق إميليانو ساو باولو',
            'Grand Hyatt São Paulo' => 'جراند حياة ساو باولو',
            'Hotel Unique São Paulo' => 'فندق يونيك ساو باولو',
            'Fasano São Paulo' => 'فاسانو ساو باولو',
            
            'Château Frontenac Quebec City' => 'شاتو فرونتيناك كيبيك سيتي',
            'Fairmont Chateau Lake Louise' => 'فيرمونت شاتو بحيرة لويز',
            'Four Seasons Hotel Toronto' => 'فندق فور سيزونز تورونتو',
            'Four Seasons Hotel Vancouver' => 'فندق فور سيزونز فانكوفر',
            'Four Seasons Hotel Montreal' => 'فندق فور سيزونز مونتريال',
            'Fairmont Hotel Vancouver' => 'فندق فيرمونت فانكوفر',
            'Fairmont Royal York Toronto' => 'فيرمونت رويال يورك تورونتو',
            'The Ritz-Carlton Montreal' => 'ريتز كارلتون مونتريال',
            'Rosewood Hotel Georgia Vancouver' => 'فندق روزوود جورجيا فانكوفر',
        ];

        // Insert Arabic descriptions
        foreach ($arabicDescriptions as $englishTitle => $arabicDescription) {
            DB::table('hotel_arabic_descriptions')->insert([
                'english_title' => $englishTitle,
                'arabic_description' => $arabicDescription,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Arabic addresses
        foreach ($arabicAddresses as $englishTitle => $arabicAddress) {
            DB::table('hotel_arabic_addresses')->insert([
                'english_title' => $englishTitle,
                'arabic_address' => $arabicAddress,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Arabic translations
        foreach ($arabicTranslations as $originalTitle => $arabicTitle) {
            $slug = \Str::slug($arabicTitle);
            DB::table('hotel_translations')->insert([
                'original_title' => $originalTitle,
                'arabic_title' => $arabicTitle,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}