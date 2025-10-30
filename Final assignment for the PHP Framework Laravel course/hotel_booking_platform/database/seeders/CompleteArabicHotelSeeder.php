<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class CompleteArabicHotelSeeder extends Seeder
{
    public function run(): void
    {
        $allArabicHotels = [
            // Russia (10 hotels)
            'The Ritz-Carlton Moscow' => ['title' => 'فندق ريتز كارلتون موسكو', 'address' => 'شارع تفيرسكايا 3، موسكو'],
            'Four Seasons Hotel Moscow' => ['title' => 'فندق فور سيزونز موسكو', 'address' => 'أوخوتني رياد 2، موسكو'],
            'Hotel Metropol Moscow' => ['title' => 'فندق متروبول موسكو', 'address' => 'تياترالني بروزد 2، موسكو'],
            'Ararat Park Hyatt Moscow' => ['title' => 'فندق أرارات بارك حياة موسكو', 'address' => 'شارع نيغلينايا 4، موسكو'],
            'The St. Regis Moscow' => ['title' => 'فندق سانت ريجيس موسكو', 'address' => 'شارع نيكولسكايا 12، موسكو'],
            'Lotte Hotel Moscow' => ['title' => 'فندق لوته موسكو', 'address' => 'نوفينسكي بوليفارد 8، موسكو'],
            'Radisson Collection Hotel Moscow' => ['title' => 'فندق راديسون كوليكشن موسكو', 'address' => 'كوتوزوفسكي بروسبكت 2/1، موسكو'],
            'Swissotel Krasnye Holmy Moscow' => ['title' => 'فندق سويس أوتيل كراسني خولمي موسكو', 'address' => 'كوسمودامياسكايا نابيريزنايا 52، موسكو'],
            'Hotel National Moscow' => ['title' => 'فندق ناشيونال موسكو', 'address' => 'شارع موخوفايا 15/1، موسكو'],
            'Baltschug Kempinski Moscow' => ['title' => 'فندق بالتشوغ كيمبينسكي موسكو', 'address' => 'شارع بالتشوغ 1، موسكو'],

            // Norway (10 hotels)
            'Hotel Continental Oslo' => ['title' => 'فندق كونتيننتال أوسلو', 'address' => 'ستورتينغسغاتا 24/26، أوسلو'],
            'The Thief Oslo' => ['title' => 'فندق ذا ثيف أوسلو', 'address' => 'لاندغانغن 1، أوسلو'],
            'Grand Hotel Oslo' => ['title' => 'فندق غراند أوسلو', 'address' => 'كارل يوهانس غيت 31، أوسلو'],
            'Radisson Blu Plaza Hotel Oslo' => ['title' => 'فندق راديسون بلو بلازا أوسلو', 'address' => 'سونيا هينيس بلاس 3، أوسلو'],
            'Clarion Hotel The Hub' => ['title' => 'فندق كلاريون ذا هاب', 'address' => 'بيسكوب غونيروس غيت 3، أوسلو'],
            'Scandic Holmenkollen Park' => ['title' => 'فندق سكانديك هولمنكولن بارك', 'address' => 'كونغيفاين 26، أوسلو'],
            'Hotel Bristol Oslo' => ['title' => 'فندق بريستول أوسلو', 'address' => 'كريستيان الرابع غيت 7، أوسلو'],
            'Amerikalinjen Oslo' => ['title' => 'فندق أمريكالينين أوسلو', 'address' => 'يرنبانيتورغيت 2، أوسلو'],
            'Scandic Vulkan' => ['title' => 'فندق سكانديك فولكان', 'address' => 'فولكان 13B، أوسلو'],
            'Villa Frogner' => ['title' => 'فيلا فروغنر', 'address' => 'نوردال برونس غيت 26، أوسلو'],

            // USA (10 hotels)
            'The Plaza New York' => ['title' => 'فندق بلازا نيويورك', 'address' => '768 الجادة الخامسة، نيويورك، نيويورك'],
            'The St. Regis New York' => ['title' => 'فندق سانت ريجيس نيويورك', 'address' => '2 شارع 55 الشرقي، نيويورك، نيويورك'],
            'The Carlyle New York' => ['title' => 'فندق كارلايل نيويورك', 'address' => '35 شارع 76 الشرقي، نيويورك، نيويورك'],
            'The Pierre New York' => ['title' => 'فندق بيير نيويورك', 'address' => '2 شارع 61 الشرقي، نيويورك، نيويورك'],
            'The Ritz-Carlton New York' => ['title' => 'فندق ريتز كارلتون نيويورك', 'address' => '50 سنترال بارك الجنوبي، نيويورك، نيويورك'],
            'Four Seasons Hotel New York' => ['title' => 'فندق فور سيزونز نيويورك', 'address' => 'شارع 57، نيويورك، نيويورك'],
            'The Mark New York' => ['title' => 'فندق ذا مارك نيويورك', 'address' => '25 شارع 77 الشرقي، نيويورك، نيويورك'],
            'The Lowell New York' => ['title' => 'فندق لويل نيويورك', 'address' => '28 شارع 63 الشرقي، نيويورك، نيويورك'],
            'The Sherry-Netherland' => ['title' => 'فندق شيري نيذرلاند', 'address' => '781 الجادة الخامسة، نيويورك، نيويورك'],
            'The Greenwich Hotel' => ['title' => 'فندق غرينتش', 'address' => '377 شارع غرينتش، نيويورك، نيويورك'],

            // France (10 hotels)
            'The Ritz Paris' => ['title' => 'فندق ريتز باريس', 'address' => '15 ساحة فاندوم، باريس'],
            'Four Seasons Hotel George V' => ['title' => 'فندق فور سيزونز جورج الخامس', 'address' => '31 شارع جورج الخامس، باريس'],
            'Le Bristol Paris' => ['title' => 'فندق لو بريستول باريس', 'address' => '112 شارع فوبورغ سان أونوريه، باريس'],
            'Hotel Plaza Athénée' => ['title' => 'فندق بلازا أثينيه', 'address' => '25 شارع مونتين، باريس'],
            'Le Meurice' => ['title' => 'فندق لو موريس', 'address' => '228 شارع ريفولي، باريس'],
            'Shangri-La Hotel Paris' => ['title' => 'فندق شانغريلا باريس', 'address' => '10 شارع إيينا، باريس'],
            'Park Hyatt Paris-Vendôme' => ['title' => 'فندق بارك حياة باريس فاندوم', 'address' => '5 شارع لا بيه، باريس'],
            'Hotel de Crillon' => ['title' => 'فندق دو كريون', 'address' => '10 ساحة الكونكورد، باريس'],
            'La Réserve Paris' => ['title' => 'فندق لا ريزيرف باريس', 'address' => '42 شارع غابرييل، باريس'],
            'Hotel Lutetia' => ['title' => 'فندق لوتيتيا', 'address' => '45 بوليفارد راسباي، باريس'],

            // Germany (10 hotels)
            'Hotel Adlon Kempinski Berlin' => ['title' => 'فندق أدلون كيمبينسكي برلين', 'address' => 'أونتر دين ليندن 77، برلين'],
            'The Ritz-Carlton Berlin' => ['title' => 'فندق ريتز كارلتون برلين', 'address' => 'بوتسدامر بلاتز 3، برلين'],
            'Regent Berlin' => ['title' => 'فندق ريجنت برلين', 'address' => 'شارلوتنشتراسه 49، برلين'],
            'Hotel de Rome Berlin' => ['title' => 'فندق دو روم برلين', 'address' => 'بيهرنشتراسه 37، برلين'],
            'Das Stue Berlin' => ['title' => 'فندق داس ستو برلين', 'address' => 'دراكشتراسه 1، برلين'],
            'Soho House Berlin' => ['title' => 'فندق سوهو هاوس برلين', 'address' => 'تورشتراسه 1، برلين'],
            'Grand Hyatt Berlin' => ['title' => 'فندق غراند حياة برلين', 'address' => 'مارلين ديتريش بلاتز 2، برلين'],
            'Hotel Brandenburger Hof' => ['title' => 'فندق براندنبورغر هوف', 'address' => 'آيسليبنر شتراسه 14، برلين'],
            'Orania Berlin' => ['title' => 'فندق أورانيا برلين', 'address' => 'أورانينشتراسه 40، برلين'],
            'Titanic Gendarmenmarkt Berlin' => ['title' => 'فندق تيتانيك جندارمنماركت برلين', 'address' => 'فرانزوزيشه شتراسه 30، برلين'],

            // Italy (10 hotels)
            'Hotel de Russie Rome' => ['title' => 'فندق دو روسي روما', 'address' => 'فيا ديل بابوينو 9، روما'],
            'The St. Regis Rome' => ['title' => 'فندق سانت ريجيس روما', 'address' => 'فيا فيتوريو إيمانويلي أورلاندو 3، روما'],
            'Hotel Splendido Portofino' => ['title' => 'فندق سبليندو بورتوفينو', 'address' => 'ساليتا باراتا 16، بورتوفينو'],
            'Gritti Palace Venice' => ['title' => 'قصر غريتي البندقية', 'address' => 'كامبو سانتا ماريا ديل جيليو 2467، البندقية'],
            'Villa San Martino Martina Franca' => ['title' => 'فيلا سان مارتينو مارتينا فرانكا', 'address' => 'فيا تارانتو 59، مارتينا فرانكا'],
            'Four Seasons Hotel Milano' => ['title' => 'فندق فور سيزونز ميلانو', 'address' => 'فيا جيسو 6/8، ميلان'],
            'Hotel Villa Cimbrone Ravello' => ['title' => 'فندق فيلا تشيمبروني رافيلو', 'address' => 'فيا سانتا كيارا 26، رافيلو'],
            'Belmond Hotel Caruso' => ['title' => 'فندق بيلموند كاروسو', 'address' => 'بيازا سان جيوفاني ديل تورو 2، رافيلو'],
            'Palazzo Margherita Basilicata' => ['title' => 'قصر مارغريتا باسيليكاتا', 'address' => 'كورسو أومبرتو 64، برنالدا'],
            'Hotel Hassler Roma' => ['title' => 'فندق هاسلر روما', 'address' => 'بيازا ترينيتا دي مونتي 6، روما'],

            // Spain (10 hotels)
            'Hotel Ritz Madrid' => ['title' => 'فندق ريتز مدريد', 'address' => 'ساحة الولاء 5، مدريد'],
            'Four Seasons Hotel Madrid' => ['title' => 'فندق فور سيزونز مدريد', 'address' => 'شارع إشبيلية 3، مدريد'],
            'Hotel Arts Barcelona' => ['title' => 'فندق آرتس برشلونة', 'address' => 'شارع مارينا 19-21، برشلونة'],
            'Hotel Casa Fuster Barcelona' => ['title' => 'فندق كاسا فوستر برشلونة', 'address' => 'باسيو دي غراسيا 132، برشلونة'],
            'Hotel Alfonso XIII Seville' => ['title' => 'فندق ألفونسو الثالث عشر إشبيلية', 'address' => 'شارع سان فرناندو 2، إشبيلية'],
            'Parador de Granada' => ['title' => 'بارادور غرناطة', 'address' => 'شارع ريال دي الحمراء، غرناطة'],
            'Hotel Villa Magna Madrid' => ['title' => 'فندق فيلا ماغنا مدريد', 'address' => 'باسيو دي لا كاستيانا 22، مدريد'],
            'Gran Hotel La Florida Barcelona' => ['title' => 'فندق غران لا فلوريدا برشلونة', 'address' => 'كاريتيرا فاليفيدريرا إلى تيبيدابو 83-93، برشلونة'],
            'Hotel Maria Cristina San Sebastian' => ['title' => 'فندق ماريا كريستينا سان سيباستيان', 'address' => 'باسيو ريبوبليكا أرجنتينا 4، سان سيباستيان'],
            'Hospes Palacio del Bailio Cordoba' => ['title' => 'فندق هوسبيس قصر البايليو قرطبة', 'address' => 'شارع راميريز دي لاس كاساس ديزا 10-12، قرطبة'],

            // United Kingdom (10 hotels)
            'The Savoy London' => ['title' => 'فندق سافوي لندن', 'address' => 'ستراند، لندن WC2R 0EZ'],
            'Claridges London' => ['title' => 'فندق كلاريدجز لندن', 'address' => 'شارع بروك، لندن W1K 4HR'],
            'The Ritz London' => ['title' => 'فندق ريتز لندن', 'address' => '150 بيكاديلي، لندن W1J 9BR'],
            'The Langham London' => ['title' => 'فندق لانغهام لندن', 'address' => '1C بورتلاند بليس، لندن W1B 1JA'],
            'Shangri-La Hotel at The Shard' => ['title' => 'فندق شانغريلا في ذا شارد', 'address' => '31 شارع سانت توماس، لندن SE1 9QU'],
            'The Connaught London' => ['title' => 'فندق كونوت لندن', 'address' => 'كارلوس بليس، لندن W1K 2AL'],
            'Corinthia Hotel London' => ['title' => 'فندق كورينثيا لندن', 'address' => 'وايتهول بليس، لندن SW1A 2BD'],
            'The Dorchester London' => ['title' => 'فندق دورتشستر لندن', 'address' => '53 بارك لين، لندن W1K 1QA'],
            'Rosewood London' => ['title' => 'فندق روزوود لندن', 'address' => '252 هاي هولبورن، لندن WC1V 7EN'],
            'The Ned London' => ['title' => 'فندق ذا نيد لندن', 'address' => '27 بولتري، لندن EC2R 8AJ'],

            // Japan (10 hotels)
            'The Ritz-Carlton Tokyo' => ['title' => 'فندق ريتز كارلتون طوكيو', 'address' => '9-7-1 أكاساكا، مينا سيتي، طوكيو'],
            'Aman Tokyo' => ['title' => 'فندق أمان طوكيو', 'address' => '1-5-6 أوتيماتشي، تشيودا سيتي، طوكيو'],
            'Park Hyatt Tokyo' => ['title' => 'فندق بارك حياة طوكيو', 'address' => '3-7-1-2 نيشي شينجوكو، شينجوكو سيتي، طوكيو'],
            'Four Seasons Hotel Tokyo at Marunouchi' => ['title' => 'فندق فور سيزونز طوكيو في مارونوتشي', 'address' => '1-11-1 مارونوتشي، تشيودا سيتي، طوكيو'],
            'The Peninsula Tokyo' => ['title' => 'فندق بينينسولا طوكيو', 'address' => '1-8-1 يوراكوتشو، تشيودا سيتي، طوكيو'],
            'Hoshinoya Tokyo' => ['title' => 'فندق هوشينويا طوكيو', 'address' => '1-9-1 أوتيماتشي، تشيودا سيتي، طوكيو'],
            'Imperial Hotel Tokyo' => ['title' => 'فندق إمبريال طوكيو', 'address' => '1-1-1 أوتشيسايوايتشو، تشيودا سيتي، طوكيو'],
            'Shangri-La Hotel Tokyo' => ['title' => 'فندق شانغريلا طوكيو', 'address' => '1-8-3 مارونوتشي، تشيودا سيتي، طوكيو'],
            'The Tokyo Station Hotel' => ['title' => 'فندق محطة طوكيو', 'address' => '1-9-1 مارونوتشي، تشيودا سيتي، طوكيو'],
            'Mandarin Oriental Tokyo' => ['title' => 'فندق ماندارين أورينتال طوكيو', 'address' => '2-1-1 نيهونباشي موروماتشي، تشو سيتي، طوكيو'],

            // China (10 hotels)
            'The Peninsula Beijing' => ['title' => 'فندق بينينسولا بكين', 'address' => '8 زقاق السمك الذهبي، وانغفوجينغ، بكين'],
            'Four Seasons Hotel Beijing' => ['title' => 'فندق فور سيزونز بكين', 'address' => '48 طريق ليانغماتشياو، بكين'],
            'The Ritz-Carlton Beijing' => ['title' => 'فندق ريتز كارلتون بكين', 'address' => '83A طريق جيانغو، بكين'],
            'Park Hyatt Beijing' => ['title' => 'فندق بارك حياة بكين', 'address' => '2 شارع جيانغومنواي، بكين'],
            'Shangri-La Hotel Beijing' => ['title' => 'فندق شانغريلا بكين', 'address' => '29 طريق زيزويوان، بكين'],
            'The St. Regis Beijing' => ['title' => 'فندق سانت ريجيس بكين', 'address' => '21 شارع جيانغومنواي، بكين'],
            'Grand Hyatt Beijing' => ['title' => 'فندق غراند حياة بكين', 'address' => '1 شارع تشانغ آن الشرقي، بكين'],
            'Rosewood Beijing' => ['title' => 'فندق روزوود بكين', 'address' => 'مركز جينغ غوانغ، هوجيالو، بكين'],
            'NUO Hotel Beijing' => ['title' => 'فندق نو بكين', 'address' => '2A طريق جيانغتاي، بكين'],
            'The Opposite House Beijing' => ['title' => 'فندق أوبوزيت هاوس بكين', 'address' => '11 طريق سانليتون، بكين'],

            // India (10 hotels)
            'The Oberoi New Delhi' => ['title' => 'فندق أوبيروي نيودلهي', 'address' => 'طريق الدكتور ذاكر حسين، نيودلهي'],
            'The Leela Palace New Delhi' => ['title' => 'قصر ليلا نيودلهي', 'address' => 'الحي الدبلوماسي، نيودلهي'],
            'Taj Mahal Palace Mumbai' => ['title' => 'قصر تاج محل مومباي', 'address' => 'أبولو بوندر، مومباي'],
            'The Oberoi Mumbai' => ['title' => 'فندق أوبيروي مومباي', 'address' => 'ناريمان بوينت، مومباي'],
            'Four Seasons Hotel Mumbai' => ['title' => 'فندق فور سيزونز مومباي', 'address' => '114 طريق الدكتور إي موسى، مومباي'],
            'The Leela Palace Udaipur' => ['title' => 'قصر ليلا أودايبور', 'address' => 'بحيرة بيتشولا، أودايبور'],
            'Taj Lake Palace Udaipur' => ['title' => 'قصر تاج البحيرة أودايبور', 'address' => 'بحيرة بيتشولا، أودايبور'],
            'Rambagh Palace Jaipur' => ['title' => 'قصر رامباغ جايبور', 'address' => 'طريق بهاواني سينغ، جايبور'],
            'The Oberoi Rajvilas Jaipur' => ['title' => 'فندق أوبيروي راجفيلاس جايبور', 'address' => 'طريق غونر، جايبور'],
            'ITC Grand Chola Chennai' => ['title' => 'فندق آي تي سي غراند تشولا تشيناي', 'address' => '63 طريق ماونت، تشيناي'],

            // Brazil (10 hotels)
            'Copacabana Palace Rio de Janeiro' => ['title' => 'قصر كوباكابانا ريو دي جانيرو', 'address' => 'أفينيدا أتلانتيكا 1702، ريو دي جانيرو'],
            'Fasano Hotel Rio de Janeiro' => ['title' => 'فندق فاسانو ريو دي جانيرو', 'address' => 'أفينيدا فييرا سوتو 80، ريو دي جانيرو'],
            'Hotel Unique São Paulo' => ['title' => 'فندق يونيك ساو باولو', 'address' => 'أفينيدا بريغاديرو لويس أنطونيو 4700، ساو باولو'],
            'Fasano São Paulo' => ['title' => 'فندق فاسانو ساو باولو', 'address' => 'شارع فيتوريو فاسانو 88، ساو باولو'],
            'Grand Hyatt São Paulo' => ['title' => 'فندق غراند حياة ساو باولو', 'address' => 'أفينيدا داس ناسويس أونيداس 13301، ساو باولو'],
            'Hotel Emiliano São Paulo' => ['title' => 'فندق إيميليانو ساو باولو', 'address' => 'شارع أوسكار فريري 384، ساو باولو'],
            'Rosewood São Paulo' => ['title' => 'فندق روزوود ساو باولو', 'address' => 'ألاميدا سانتوس 2200، ساو باولو'],
            'JW Marriott Hotel Rio de Janeiro' => ['title' => 'فندق جي دبليو ماريوت ريو دي جانيرو', 'address' => 'أفينيدا أتلانتيكا 2600، ريو دي جانيرو'],
            'Fairmont Rio de Janeiro Copacabana' => ['title' => 'فندق فيرمونت ريو دي جانيرو كوباكابانا', 'address' => 'أفينيدا أتلانتيكا 4240، ريو دي جانيرو'],
            'Hotel Fasano Boa Vista' => ['title' => 'فندق فاسانو بوا فيستا', 'address' => 'فازيندا بوا فيستا، بورتو فيليز'],

            // Canada (10 hotels)
            'Fairmont Chateau Lake Louise' => ['title' => 'فندق فيرمونت شاتو بحيرة لويز', 'address' => '111 طريق بحيرة لويز، بحيرة لويز، ألبرتا'],
            'Four Seasons Hotel Toronto' => ['title' => 'فندق فور سيزونز تورونتو', 'address' => '60 شارع يوركفيل، تورونتو، أونتاريو'],
            'The Ritz-Carlton Toronto' => ['title' => 'فندق ريتز كارلتون تورونتو', 'address' => '181 شارع ويلينغتون الغربي، تورونتو، أونتاريو'],
            'Fairmont Royal York Toronto' => ['title' => 'فندق فيرمونت رويال يورك تورونتو', 'address' => '100 شارع فرونت الغربي، تورونتو، أونتاريو'],
            'Rosewood Hotel Georgia Vancouver' => ['title' => 'فندق روزوود جورجيا فانكوفر', 'address' => '801 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية'],
            'Four Seasons Hotel Vancouver' => ['title' => 'فندق فور سيزونز فانكوفر', 'address' => '791 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية'],
            'Fairmont Hotel Vancouver' => ['title' => 'فندق فيرمونت فانكوفر', 'address' => '900 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية'],
            'Château Frontenac Quebec City' => ['title' => 'شاتو فرونتيناك مدينة كيبيك', 'address' => '1 شارع دي كارييريس، مدينة كيبيك، كيبيك'],
            'Four Seasons Hotel Montreal' => ['title' => 'فندق فور سيزونز مونتريال', 'address' => '1440 شارع دي لا مونتاني، مونتريال، كيبيك'],
            'The Ritz-Carlton Montreal' => ['title' => 'فندق ريتز كارلتون مونتريال', 'address' => '1228 شارع شيربروك الغربي، مونتريال، كيبيك'],

            // Australia (10 hotels)
            'Park Hyatt Sydney' => ['title' => 'فندق بارك حياة سيدني', 'address' => '7 طريق هيكسون، سيدني، نيو ساوث ويلز'],
            'Four Seasons Hotel Sydney' => ['title' => 'فندق فور سيزونز سيدني', 'address' => '199 شارع جورج، سيدني، نيو ساوث ويلز'],
            'The Langham Sydney' => ['title' => 'فندق لانغهام سيدني', 'address' => '89-113 شارع كينت، سيدني، نيو ساوث ويلز'],
            'Shangri-La Hotel Sydney' => ['title' => 'فندق شانغريلا سيدني', 'address' => '176 شارع كمبرلاند، سيدني، نيو ساوث ويلز'],
            'Crown Towers Melbourne' => ['title' => 'أبراج كراون ملبورن', 'address' => '8 شارع وايتمان، ملبورن، فيكتوريا'],
            'Park Hyatt Melbourne' => ['title' => 'فندق بارك حياة ملبورن', 'address' => '1 ساحة البرلمان، ملبورن، فيكتوريا'],
            'The Langham Melbourne' => ['title' => 'فندق لانغهام ملبورن', 'address' => '1 شارع ساوثغيت، ملبورن، فيكتوريا'],
            'COMO The Treasury Perth' => ['title' => 'فندق كومو الخزانة بيرث', 'address' => '1 شارع الكاتدرائية، بيرث، أستراليا الغربية'],
            'Emporium Hotel South Bank Brisbane' => ['title' => 'فندق إمبوريوم ساوث بانك بريسبان', 'address' => '267 شارع غراي، بريسبان، كوينزلاند'],
            'The Calile Hotel Brisbane' => ['title' => 'فندق كاليل بريسبان', 'address' => '39 شارع جيمس، بريسبان، كوينزلاند'],

            // Mexico (10 hotels)
            'Four Seasons Hotel Mexico City' => ['title' => 'فندق فور سيزونز مكسيكو سيتي', 'address' => 'باسيو دي لا ريفورما 500، مكسيكو سيتي'],
            'The St. Regis Mexico City' => ['title' => 'فندق سانت ريجيس مكسيكو سيتي', 'address' => 'باسيو دي لا ريفورما 439، مكسيكو سيتي'],
            'JW Marriott Hotel Mexico City' => ['title' => 'فندق جي دبليو ماريوت مكسيكو سيتي', 'address' => 'أندريس بيلو 29، مكسيكو سيتي'],
            'Grand Fiesta Americana Chapultepec' => ['title' => 'فندق غراند فييستا أمريكانا تشابولتيبيك', 'address' => 'ماريانو إسكوبيدو 756، مكسيكو سيتي'],
            'Hotel Presidente InterContinental' => ['title' => 'فندق بريزيدنتي إنتركونتيننتال', 'address' => 'كامبوس إليسيوس 218، مكسيكو سيتي'],
            'Rosewood San Miguel de Allende' => ['title' => 'فندق روزوود سان ميغيل دي أليندي', 'address' => 'نيميسيو دييز 11، سان ميغيل دي أليندي'],
            'Belmond Casa de Sierra Nevada' => ['title' => 'فندق بيلموند كاسا دي سييرا نيفادا', 'address' => 'هوسبيسيو 35، سان ميغيل دي أليندي'],
            'Grand Velas Riviera Maya' => ['title' => 'فندق غراند فيلاس ريفييرا مايا', 'address' => 'كاريتيرا كانكون-تولوم كم 62، بلايا ديل كارمن'],
            'Rosewood Mayakoba' => ['title' => 'فندق روزوود مايكوبا', 'address' => 'كاريتيرا فيديرال كانكون-بلايا ديل كارمن كم 298'],
            'Banyan Tree Mayakoba' => ['title' => 'فندق بانيان تري مايكوبا', 'address' => 'كاريتيرا فيديرال كانكون-بلايا ديل كارمن كم 298'],

            // Turkey (10 hotels)
            'Four Seasons Hotel Istanbul at Sultanahmet' => ['title' => 'فندق فور سيزونز إسطنبول في السلطان أحمد', 'address' => 'تيفكيفهانه سوكاك رقم 1، إسطنبول'],
            'Çırağan Palace Kempinski Istanbul' => ['title' => 'قصر تشيراغان كيمبينسكي إسطنبول', 'address' => 'تشيراغان جاديسي 32، إسطنبول'],
            'The Ritz-Carlton Istanbul' => ['title' => 'فندق ريتز كارلتون إسطنبول', 'address' => 'سوزر بلازا، أسكروجاغي جاديسي رقم 6، إسطنبول'],
            'Shangri-La Bosphorus Istanbul' => ['title' => 'فندق شانغريلا البوسفور إسطنبول', 'address' => 'سينان باشا محلسي، حيرتين إسكيليسي سوكاك 1، إسطنبول'],
            'Park Hyatt Istanbul Macka Palas' => ['title' => 'فندق بارك حياة إسطنبول ماتشكا بالاس', 'address' => 'برونز سوكاك رقم 4، إسطنبول'],
            'Six Senses Kaplankaya' => ['title' => 'فندق سيكس سينسز كابلانكايا', 'address' => 'كابلانكايا، بودروم'],
            'Mandarin Oriental Bodrum' => ['title' => 'فندق ماندارين أورينتال بودروم', 'address' => 'جنت كويو، بودروم'],
            'The Edition Bodrum' => ['title' => 'فندق إديشن بودروم', 'address' => 'يالي كافاك مارينا، بودروم'],
            'Swissotel The Bosphorus Istanbul' => ['title' => 'فندق سويس أوتيل البوسفور إسطنبول', 'address' => 'فيشنيزادي محلسي، أجيسو سوكاك رقم 19، إسطنبول'],
            'Conrad Istanbul Bosphorus' => ['title' => 'فندق كونراد إسطنبول البوسفور', 'address' => 'جيهان نوما محلسي، ساراي جاديسي رقم 5، إسطنبول'],

            // Thailand (10 hotels)
            'The Oriental Bangkok' => ['title' => 'فندق أورينتال بانكوك', 'address' => '48 شارع أورينتال، بانكوك'],
            'Four Seasons Hotel Bangkok' => ['title' => 'فندق فور سيزونز بانكوك', 'address' => '155 طريق راجادامري، بانكوك'],
            'The St. Regis Bangkok' => ['title' => 'فندق سانت ريجيس بانكوك', 'address' => '159 طريق راجادامري، بانكوك'],
            'Park Hyatt Bangkok' => ['title' => 'فندق بارك حياة بانكوك', 'address' => '88 طريق وايرليس، بانكوك'],
            'Shangri-La Hotel Bangkok' => ['title' => 'فندق شانغريلا بانكوك', 'address' => '89 سوي وات سوان بلو، بانكوك'],
            'Rosewood Bangkok' => ['title' => 'فندق روزوود بانكوك', 'address' => '1041/38 طريق بلوينتشيت، بانكوك'],
            'Four Seasons Resort Chiang Mai' => ['title' => 'منتجع فور سيزونز تشيانغ ماي', 'address' => '502 مو 1، طريق ماي ريم-ساموينغ القديم، تشيانغ ماي'],
            'Anantara Golden Triangle' => ['title' => 'فندق أنانتارا المثلث الذهبي', 'address' => '229 مو 1، تشيانغ سين، تشيانغ راي'],
            'Six Senses Yao Noi' => ['title' => 'فندق سيكس سينسز ياو نوي', 'address' => '56 مو 5، كوه ياو نوي، فانغ نغا'],
            'Amanpuri Phuket' => ['title' => 'فندق أمانبوري فوكيت', 'address' => 'شاطئ بانسيا، فوكيت'],

            // South Korea (10 hotels)
            'Park Hyatt Seoul' => ['title' => 'فندق بارك حياة سيول', 'address' => '606 تيهيران-رو، غانغنام-غو، سيول'],
            'Four Seasons Hotel Seoul' => ['title' => 'فندق فور سيزونز سيول', 'address' => '97 سايمونان-رو، جونغنو-غو، سيول'],
            'The Shilla Seoul' => ['title' => 'فندق شيلا سيول', 'address' => '249 دونغهو-رو، جونغ-غو، سيول'],
            'Grand Hyatt Seoul' => ['title' => 'فندق غراند حياة سيول', 'address' => '322 سوول-رو، يونغسان-غو، سيول'],
            'JW Marriott Hotel Seoul' => ['title' => 'فندق جي دبليو ماريوت سيول', 'address' => '176 سينبانبو-رو، سيوتشو-غو، سيول'],
            'Lotte Hotel Seoul' => ['title' => 'فندق لوته سيول', 'address' => '30 أولجي-رو، جونغ-غو، سيول'],
            'Conrad Seoul' => ['title' => 'فندق كونراد سيول', 'address' => '10 غوكجيغيومونغ-رو، يونغدونغبو-غو، سيول'],
            'Banyan Tree Club & Spa Seoul' => ['title' => 'فندق بانيان تري كلوب وسبا سيول', 'address' => '60 جانغتشونغدان-رو، جونغ-غو، سيول'],
            'Signiel Seoul' => ['title' => 'فندق سيغنيل سيول', 'address' => '300 أولمبيك-رو، سونغبا-غو، سيول'],
            'The Westin Chosun Seoul' => ['title' => 'فندق ويستن تشوسون سيول', 'address' => '106 سوغونغ-رو، جونغ-غو، سيول'],

            // UAE (10 hotels)
            'Burj Al Arab Jumeirah' => ['title' => 'برج العرب جميرا', 'address' => 'طريق شاطئ جميرا، دبي'],
            'Four Seasons Resort Dubai at Jumeirah Beach' => ['title' => 'منتجع فور سيزونز دبي في شاطئ جميرا', 'address' => 'طريق شاطئ جميرا، دبي'],
            'The Ritz-Carlton Dubai' => ['title' => 'فندق ريتز كارلتون دبي', 'address' => 'مركز دبي المالي العالمي، دبي'],
            'Park Hyatt Dubai' => ['title' => 'فندق بارك حياة دبي', 'address' => 'نادي دبي كريك للغولف واليخوت، دبي'],
            'Atlantis The Palm' => ['title' => 'أتلانتس النخلة', 'address' => 'طريق الهلال، نخلة جميرا، دبي'],
            'Emirates Palace Abu Dhabi' => ['title' => 'قصر الإمارات أبوظبي', 'address' => 'طريق الكورنيش الغربي، أبوظبي'],
            'The St. Regis Abu Dhabi' => ['title' => 'فندق سانت ريجيس أبوظبي', 'address' => 'أبراج الأمة، الكورنيش، أبوظبي'],
            'Four Seasons Hotel Abu Dhabi' => ['title' => 'فندق فور سيزونز أبوظبي', 'address' => 'جزيرة المارية، أبوظبي'],
            'Rosewood Abu Dhabi' => ['title' => 'فندق روزوود أبوظبي', 'address' => 'جزيرة المارية، أبوظبي'],
            'Shangri-La Hotel Qaryat Al Beri' => ['title' => 'فندق شانغريلا قرية البري', 'address' => 'قرية البري، أبوظبي'],

            // Singapore (10 hotels)
            'Marina Bay Sands' => ['title' => 'مارينا باي ساندز', 'address' => '10 شارع بايفرونت، سنغافورة'],
            'The Ritz-Carlton Millenia Singapore' => ['title' => 'فندق ريتز كارلتون ميلينيا سنغافورة', 'address' => '7 شارع رافلز، سنغافورة'],
            'Four Seasons Hotel Singapore' => ['title' => 'فندق فور سيزونز سنغافورة', 'address' => '190 أورتشارد بوليفارد، سنغافورة'],
            'Shangri-La Hotel Singapore' => ['title' => 'فندق شانغريلا سنغافورة', 'address' => '22 طريق أورانج غروف، سنغافورة'],
            'The St. Regis Singapore' => ['title' => 'فندق سانت ريجيس سنغافورة', 'address' => '29 طريق تانغلين، سنغافورة'],
            'Capella Singapore' => ['title' => 'فندق كابيلا سنغافورة', 'address' => '1 ذا نولز، جزيرة سنتوسا، سنغافورة'],
            'Raffles Hotel Singapore' => ['title' => 'فندق رافلز سنغافورة', 'address' => '1 طريق الشاطئ، سنغافورة'],
            'Park Hyatt Singapore' => ['title' => 'فندق بارك حياة سنغافورة', 'address' => '10 طريق سكوتس، سنغافورة'],
            'Conrad Centennial Singapore' => ['title' => 'فندق كونراد سنتينيال سنغافورة', 'address' => '2 بوليفارد تيماسيك، سنغافورة'],
            'Mandarin Oriental Singapore' => ['title' => 'فندق ماندارين أورينتال سنغافورة', 'address' => '5 شارع رافلز، سنغافورة']
        ];

        foreach ($allArabicHotels as $originalTitle => $arabicData) {
            Hotel::where('title', $originalTitle)->update([
                'title' => $arabicData['title'],
                'address' => $arabicData['address']
            ]);
        }
    }
}