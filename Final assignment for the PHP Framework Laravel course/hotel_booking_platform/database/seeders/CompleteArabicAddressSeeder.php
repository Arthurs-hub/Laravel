<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteArabicAddressSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('CREATE TABLE IF NOT EXISTS hotel_arabic_addresses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            english_title VARCHAR(255) NOT NULL,
            arabic_address VARCHAR(500) NOT NULL,
            UNIQUE KEY unique_title (english_title)
        )');

        $addresses = [
            // Russia
            'The Ritz-Carlton Moscow' => 'شارع تفيرسكايا 3، موسكو',
            'Four Seasons Hotel Moscow' => 'أوخوتني رياد 2، موسكو',
            'Hotel Metropol Moscow' => 'تياترالني بروزد 2، موسكو',
            'Ararat Park Hyatt Moscow' => 'شارع نيغلينايا 4، موسكو',
            'The St. Regis Moscow' => 'شارع نيكولسكايا 12، موسكو',
            'Lotte Hotel Moscow' => 'نوفينسكي بوليفارد 8، موسكو',
            'Radisson Collection Hotel Moscow' => 'كوتوزوفسكي بروسبكت 2/1، موسكو',
            'Swissotel Krasnye Holmy Moscow' => 'كوسمودامياسكايا نابيريزنايا 52، موسكو',
            'Hotel National Moscow' => 'شارع موخوفايا 15/1، موسكو',
            'Baltschug Kempinski Moscow' => 'شارع بالتشوغ 1، موسكو',

            // Norway
            'Hotel Continental Oslo' => 'ستورتينغسغاتا 24/26، أوسلو',
            'The Thief Oslo' => 'لاندغانغن 1، أوسلو',
            'Grand Hotel Oslo' => 'كارل يوهانس غيت 31، أوسلو',
            'Radisson Blu Plaza Hotel Oslo' => 'سونيا هينيس بلاس 3، أوسلو',
            'Clarion Hotel The Hub' => 'بيسكوب غونيروس غيت 3، أوسلو',
            'Scandic Holmenkollen Park' => 'كونغيفاين 26، أوسلو',
            'Hotel Bristol Oslo' => 'كريستيان الرابع غيت 7، أوسلو',
            'Amerikalinjen Oslo' => 'يرنبانيتورغيت 2، أوسلو',
            'Scandic Vulkan' => 'فولكان 13B، أوسلو',
            'Villa Frogner' => 'نوردال برونس غيت 26، أوسلو',

            // USA
            'The Plaza New York' => '768 الجادة الخامسة، نيويورك، نيويورك',
            'The St. Regis New York' => '2 شارع 55 الشرقي، نيويورك، نيويورك',
            'The Carlyle New York' => '35 شارع 76 الشرقي، نيويورك، نيويورك',
            'The Pierre New York' => '2 شارع 61 الشرقي، نيويورك، نيويورك',
            'The Ritz-Carlton New York' => '50 سنترال بارك الجنوبي، نيويورك، نيويورك',
            'Four Seasons Hotel New York' => 'شارع 57، نيويورك، نيويورك',
            'The Mark New York' => '25 شارع 77 الشرقي، نيويورك، نيويورك',
            'The Lowell New York' => '28 شارع 63 الشرقي، نيويورك، نيويورك',
            'The Sherry-Netherland' => '781 الجادة الخامسة، نيويورك، نيويورك',
            'The Greenwich Hotel' => '377 شارع غرينتش، نيويورك، نيويورك',

            // France
            'The Ritz Paris' => '15 ساحة فاندوم، باريس',
            'Four Seasons Hotel George V' => '31 شارع جورج الخامس، باريس',
            'Le Bristol Paris' => '112 شارع فوبورغ سان أونوريه، باريس',
            'Hotel Plaza Athénée' => '25 شارع مونتين، باريس',
            'Le Meurice' => '228 شارع ريفولي، باريس',
            'Shangri-La Hotel Paris' => '10 شارع إيينا، باريس',
            'Park Hyatt Paris-Vendôme' => '5 شارع لا بيه، باريس',
            'Hotel de Crillon' => '10 ساحة الكونكورد، باريس',
            'La Réserve Paris' => '42 شارع غابرييل، باريس',
            'Hotel Lutetia' => '45 بوليفارد راسباي، باريس',

            // Germany
            'Hotel Adlon Kempinski Berlin' => 'أونتر دين ليندن 77، برلين',
            'The Ritz-Carlton Berlin' => 'بوتسدامر بلاتز 3، برلين',
            'Regent Berlin' => 'شارلوتنشتراسه 49، برلين',
            'Hotel de Rome Berlin' => 'بيهرنشتراسه 37، برلين',
            'Das Stue Berlin' => 'دراكشتراسه 1، برلين',
            'Soho House Berlin' => 'تورشتراسه 1، برلين',
            'Grand Hyatt Berlin' => 'مارلين ديتريش بلاتز 2، برلين',
            'Hotel Brandenburger Hof' => 'آيسليبنر شتراسه 14، برلين',
            'Orania Berlin' => 'أورانينشتراسه 40، برلين',
            'Titanic Gendarmenmarkt Berlin' => 'فرانزوزيشه شتراسه 30، برلين',

            // Italy
            'Hotel de Russie Rome' => 'فيا ديل بابوينو 9، روما',
            'The St. Regis Rome' => 'فيا فيتوريو إيمانويلي أورلاندو 3، روما',
            'Hotel Splendido Portofino' => 'ساليتا باراتا 16، بورتوفينو',
            'Gritti Palace Venice' => 'كامبو سانتا ماريا ديل جيليو 2467، البندقية',
            'Villa San Martino Martina Franca' => 'فيا تارانتو 59، مارتينا فرانكا',
            'Four Seasons Hotel Milano' => 'فيا جيسو 6/8، ميلان',
            'Hotel Villa Cimbrone Ravello' => 'فيا سانتا كيارا 26، رافيلو',
            'Belmond Hotel Caruso' => 'بيازا سان جيوفاني ديل تورو 2، رافيلو',
            'Palazzo Margherita Basilicata' => 'كورسو أومبرتو 64، برنالدا',
            'Hotel Hassler Roma' => 'بيازا ترينيتا دي مونتي 6، روما',

            // Spain
            'Hotel Ritz Madrid' => 'ساحة الولاء 5، مدريد',
            'Four Seasons Hotel Madrid' => 'شارع إشبيلية 3، مدريد',
            'Hotel Arts Barcelona' => 'شارع مارينا 19-21، برشلونة',
            'Hotel Casa Fuster Barcelona' => 'باسيو دي غراسيا 132، برشلونة',
            'Hotel Alfonso XIII Seville' => 'شارع سان فرناندو 2، إشبيلية',
            'Parador de Granada' => 'شارع ريال دي الحمراء، غرناطة',
            'Hotel Villa Magna Madrid' => 'باسيو دي لا كاستيانا 22، مدريد',
            'Gran Hotel La Florida Barcelona' => 'كاريتيرا فاليفيدريرا إلى تيبيدابو 83-93، برشلونة',
            'Hotel Maria Cristina San Sebastian' => 'باسيو ريبوبليكا أرجنتينا 4، سان سيباستيان',
            'Hospes Palacio del Bailio Cordoba' => 'شارع راميريز دي لاس كاساس ديزا 10-12، قرطبة',

            // United Kingdom
            'The Savoy London' => 'ستراند، لندن WC2R 0EZ',
            'Claridges London' => 'شارع بروك، لندن W1K 4HR',
            'The Ritz London' => '150 بيكاديلي، لندن W1J 9BR',
            'The Langham London' => '1C بورتلاند بليس، لندن W1B 1JA',
            'Shangri-La Hotel at The Shard' => '31 شارع سانت توماس، لندن SE1 9QU',
            'The Connaught London' => 'كارلوس بليس، لندن W1K 2AL',
            'Corinthia Hotel London' => 'وايتهول بليس، لندن SW1A 2BD',
            'The Dorchester London' => '53 بارك لين، لندن W1K 1QA',
            'Rosewood London' => '252 هاي هولبورن، لندن WC1V 7EN',
            'The Ned London' => '27 بولتري، لندن EC2R 8AJ',

            // Japan
            'The Ritz-Carlton Tokyo' => '9-7-1 أكاساكا، مينا سيتي، طوكيو',
            'Aman Tokyo' => '1-5-6 أوتيماتشي، تشيودا سيتي، طوكيو',
            'Park Hyatt Tokyo' => '3-7-1-2 نيشي شينجوكو، شينجوكو سيتي، طوكيو',
            'Four Seasons Hotel Tokyo at Marunouchi' => '1-11-1 مارونوتشي، تشيودا سيتي، طوكيو',
            'The Peninsula Tokyo' => '1-8-1 يوراكوتشو، تشيودا سيتي، طوكيو',
            'Hoshinoya Tokyo' => '1-9-1 أوتيماتشي، تشيودا سيتي، طوكيو',
            'Imperial Hotel Tokyo' => '1-1-1 أوتشيسايوايتشو، تشيودا سيتي، طوكيو',
            'Shangri-La Hotel Tokyo' => '1-8-3 مارونوتشي، تشيودا سيتي، طوكيو',
            'The Tokyo Station Hotel' => '1-9-1 مارونوتشي، تشيودا سيتي، طوكيو',
            'Mandarin Oriental Tokyo' => '2-1-1 نيهونباشي موروماتشي، تشو سيتي، طوكيو',

            // China
            'The Peninsula Beijing' => '8 زقاق السمك الذهبي، وانغفوجينغ، بكين',
            'Four Seasons Hotel Beijing' => '48 طريق ليانغماتشياو، بكين',
            'The Ritz-Carlton Beijing' => '83A طريق جيانغو، بكين',
            'Park Hyatt Beijing' => '2 شارع جيانغومنواي، بكين',
            'Shangri-La Hotel Beijing' => '29 طريق زيزويوان، بكين',
            'The St. Regis Beijing' => '21 شارع جيانغومنواي، بكين',
            'Grand Hyatt Beijing' => '1 شارع تشانغ آن الشرقي، بكين',
            'Rosewood Beijing' => 'مركز جينغ غوانغ، هوجيالو، بكين',
            'NUO Hotel Beijing' => '2A طريق جيانغتاي، بكين',
            'The Opposite House Beijing' => '11 طريق سانليتون، بكين',

            // India
            'The Oberoi New Delhi' => 'طريق الدكتور ذاكر حسين، نيودلهي',
            'The Leela Palace New Delhi' => 'الحي الدبلوماسي، نيودلهي',
            'Taj Mahal Palace Mumbai' => 'أبولو بوندر، مومباي',
            'The Oberoi Mumbai' => 'ناريمان بوينت، مومباي',
            'Four Seasons Hotel Mumbai' => '114 طريق الدكتور إي موسى، مومباي',
            'The Leela Palace Udaipur' => 'بحيرة بيتشولا، أودايبور',
            'Taj Lake Palace Udaipur' => 'بحيرة بيتشولا، أودايبور',
            'Rambagh Palace Jaipur' => 'طريق بهاواني سينغ، جايبور',
            'The Oberoi Rajvilas Jaipur' => 'طريق غونر، جايبور',
            'ITC Grand Chola Chennai' => '63 طريق ماونت، تشيناي',

            // Brazil
            'Copacabana Palace Rio de Janeiro' => 'أفينيدا أتلانتيكا 1702، ريو دي جانيرو',
            'Fasano Hotel Rio de Janeiro' => 'أفينيدا فييرا سوتو 80، ريو دي جانيرو',
            'Hotel Unique São Paulo' => 'أفينيدا بريغاديرو لويس أنطونيو 4700، ساو باولو',
            'Fasano São Paulo' => 'شارع فيتوريو فاسانو 88، ساو باولو',
            'Grand Hyatt São Paulo' => 'أفينيدا داس ناسويس أونيداس 13301، ساو باولو',
            'Hotel Emiliano São Paulo' => 'شارع أوسكار فريري 384، ساو باولو',
            'Rosewood São Paulo' => 'ألاميدا سانتوس 2200، ساو باولو',
            'JW Marriott Hotel Rio de Janeiro' => 'أفينيدا أتلانتيكا 2600، ريو دي جانيرو',
            'Fairmont Rio de Janeiro Copacabana' => 'أفينيدا أتلانتيكا 4240، ريو دي جانيرو',
            'Hotel Fasano Boa Vista' => 'فازيندا بوا فيستا، بورتو فيليز',

            // Canada
            'Fairmont Chateau Lake Louise' => '111 طريق بحيرة لويز، بحيرة لويز، ألبرتا',
            'Four Seasons Hotel Toronto' => '60 شارع يوركفيل، تورونتو، أونتاريو',
            'The Ritz-Carlton Toronto' => '181 شارع ويلينغتون الغربي، تورونتو، أونتاريو',
            'Fairmont Royal York Toronto' => '100 شارع فرونت الغربي، تورونتو، أونتاريو',
            'Rosewood Hotel Georgia Vancouver' => '801 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
            'Four Seasons Hotel Vancouver' => '791 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
            'Fairmont Hotel Vancouver' => '900 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
            'Château Frontenac Quebec City' => '1 شارع دي كارييريس، مدينة كيبيك، كيبيك',
            'Four Seasons Hotel Montreal' => '1440 شارع دي لا مونتاني، مونتريال، كيبيك',
            'The Ritz-Carlton Montreal' => '1228 شارع شيربروك الغربي، مونتريال، كيبيك',

            // Australia
            'Park Hyatt Sydney' => '7 طريق هيكسون، سيدني، نيو ساوث ويلز',
            'Four Seasons Hotel Sydney' => '199 شارع جورج، سيدني، نيو ساوث ويلز',
            'The Langham Sydney' => '89-113 شارع كينت، سيدني، نيو ساوث ويلز',
            'Shangri-La Hotel Sydney' => '176 شارع كمبرلاند، سيدني، نيو ساوث ويلز',
            'Crown Towers Melbourne' => '8 شارع وايتمان، ملبورن، فيكتوريا',
            'Park Hyatt Melbourne' => '1 ساحة البرلمان، ملبورن، فيكتوريا',
            'The Langham Melbourne' => '1 شارع ساوثغيت، ملبورن، فيكتوريا',
            'COMO The Treasury Perth' => '1 شارع الكاتدرائية، بيرث، أستراليا الغربية',
            'Emporium Hotel South Bank Brisbane' => '267 شارع غراي، بريسبان، كوينزلاند',
            'The Calile Hotel Brisbane' => '39 شارع جيمس، بريسبان، كوينزلاند',

            // Mexico
            'Four Seasons Hotel Mexico City' => 'باسيو دي لا ريفورما 500، مكسيكو سيتي',
            'The St. Regis Mexico City' => 'باسيو دي لا ريفورما 439، مكسيكو سيتي',
            'JW Marriott Hotel Mexico City' => 'أندريس بيلو 29، مكسيكو سيتي',
            'Grand Fiesta Americana Chapultepec' => 'ماريانو إسكوبيدو 756، مكسيكو سيتي',
            'Hotel Presidente InterContinental' => 'كامبوس إليسيوس 218، مكسيكو سيتي',
            'Rosewood San Miguel de Allende' => 'نيميسيو دييز 11، سان ميغيل دي أليندي',
            'Belmond Casa de Sierra Nevada' => 'هوسبيسيو 35، سان ميغيل دي أليندي',
            'Grand Velas Riviera Maya' => 'كاريتيرا كانكون-تولوم كم 62، بلايا ديل كارمن',
            'Rosewood Mayakoba' => 'كاريتيرا فيديرال كانكون-بلايا ديل كارمن كم 298',
            'Banyan Tree Mayakoba' => 'كاريتيرا فيديرال كانكون-بلايا ديل كارمن كم 298',

            // Turkey
            'Four Seasons Hotel Istanbul at Sultanahmet' => 'تيفكيفهانه سوكاك رقم 1، إسطنبول',
            'Çırağan Palace Kempinski Istanbul' => 'تشيراغان جاديسي 32، إسطنبول',
            'The Ritz-Carlton Istanbul' => 'سوزر بلازا، أسكروجاغي جاديسي رقم 6، إسطنبول',
            'Shangri-La Bosphorus Istanbul' => 'سينان باشا محلسي، حيرتين إسكيليسي سوكاك 1، إسطنبول',
            'Park Hyatt Istanbul Macka Palas' => 'برونز سوكاك رقم 4، إسطنبول',
            'Six Senses Kaplankaya' => 'كابلانكايا، بودروم',
            'Mandarin Oriental Bodrum' => 'جنت كويو، بودروم',
            'The Edition Bodrum' => 'يالي كافاك مارينا، بودروم',
            'Swissotel The Bosphorus Istanbul' => 'فيشنيزادي محلسي، أجيسو سوكاك رقم 19، إسطنبول',
            'Conrad Istanbul Bosphorus' => 'جيهان نوما محلسي، ساراي جاديسي رقم 5، إسطنبول',

            // Thailand
            'The Oriental Bangkok' => '48 شارع أورينتال، بانكوك',
            'Four Seasons Hotel Bangkok' => '155 طريق راجادامري، بانكوك',
            'The St. Regis Bangkok' => '159 طريق راجادامري، بانكوك',
            'Park Hyatt Bangkok' => '88 طريق وايرليس، بانكوك',
            'Shangri-La Hotel Bangkok' => '89 سوي وات سوان بلو، بانكوك',
            'Rosewood Bangkok' => '1041/38 طريق بلوينتشيت، بانكوك',
            'Four Seasons Resort Chiang Mai' => '502 مو 1، طريق ماي ريم-ساموينغ القديم، تشيانغ ماي',
            'Anantara Golden Triangle' => '229 مو 1، تشيانغ سين، تشيانغ راي',
            'Six Senses Yao Noi' => '56 مو 5، كوه ياو نوي، فانغ نغا',
            'Amanpuri Phuket' => 'شاطئ بانسيا، فوكيت',

            // South Korea
            'Park Hyatt Seoul' => '606 تيهيران-رو، غانغنام-غو، سيول',
            'Four Seasons Hotel Seoul' => '97 سايمونان-رو، جونغنو-غو، سيول',
            'The Shilla Seoul' => '249 دونغهو-رو، جونغ-غو، سيول',
            'Grand Hyatt Seoul' => '322 سوول-رو، يونغسان-غو، سيول',
            'JW Marriott Hotel Seoul' => '176 سينبانبو-رو، سيوتشو-غو، سيول',
            'Lotte Hotel Seoul' => '30 أولجي-رو، جونغ-غو، سيول',
            'Conrad Seoul' => '10 غوكجيغيومونغ-رو، يونغدونغبو-غو، سيول',
            'Banyan Tree Club & Spa Seoul' => '60 جانغتشونغدان-رو، جونغ-غو، سيول',
            'Signiel Seoul' => '300 أولمبيك-رو، سونغبا-غو، سيول',
            'The Westin Chosun Seoul' => '106 سوغونغ-رو، جونغ-غو، سيول',

            // UAE
            'Burj Al Arab Jumeirah' => 'طريق شاطئ جميرا، دبي',
            'Four Seasons Resort Dubai at Jumeirah Beach' => 'طريق شاطئ جميرا، دبي',
            'The Ritz-Carlton Dubai' => 'مركز دبي المالي العالمي، دبي',
            'Park Hyatt Dubai' => 'نادي دبي كريك للغولف واليخوت، دبي',
            'Atlantis The Palm' => 'طريق الهلال، نخلة جميرا، دبي',
            'Emirates Palace Abu Dhabi' => 'طريق الكورنيش الغربي، أبوظبي',
            'The St. Regis Abu Dhabi' => 'أبراج الأمة، الكورنيش، أبوظبي',
            'Four Seasons Hotel Abu Dhabi' => 'جزيرة المارية، أبوظبي',
            'Rosewood Abu Dhabi' => 'جزيرة المارية، أبوظبي',
            'Shangri-La Hotel Qaryat Al Beri' => 'قرية البري، أبوظبي',

            // Singapore
            'Marina Bay Sands' => '10 شارع بايفرونت، سنغافورة',
            'The Ritz-Carlton Millenia Singapore' => '7 شارع رافلز، سنغافورة',
            'Four Seasons Hotel Singapore' => '190 أورتشارد بوليفارد، سنغافورة',
            'Shangri-La Hotel Singapore' => '22 طريق أورانج غروف، سنغافورة',
            'The St. Regis Singapore' => '29 طريق تانغلين، سنغافورة',
            'Capella Singapore' => '1 ذا نولز، جزيرة سنتوسا، سنغافورة',
            'Raffles Hotel Singapore' => '1 طريق الشاطئ، سنغافورة',
            'Park Hyatt Singapore' => '10 طريق سكوتس، سنغافورة',
            'Conrad Centennial Singapore' => '2 بوليفارد تيماسيك، سنغافورة',
            'Mandarin Oriental Singapore' => '5 شارع رافلز، سنغافورة'
        ];

        foreach ($addresses as $title => $address) {
            DB::table('hotel_arabic_addresses')->insertOrIgnore([
                'english_title' => $title,
                'arabic_address' => $address
            ]);
        }
    }
}