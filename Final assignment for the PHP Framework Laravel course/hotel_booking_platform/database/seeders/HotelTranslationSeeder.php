<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelTranslationSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('CREATE TABLE IF NOT EXISTS hotel_translations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            original_title VARCHAR(255) NOT NULL,
            arabic_title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            UNIQUE KEY unique_original (original_title),
            UNIQUE KEY unique_slug (slug)
        )');

        $translations = [
            // Russia
            ['The Ritz-Carlton Moscow', 'فندق ريتز كارلتون موسكو', 'the_ritz_carlton_moscow'],
            ['Four Seasons Hotel Moscow', 'فندق فور سيزونز موسكو', 'four_seasons_hotel_moscow'],
            ['Hotel Metropol Moscow', 'فندق متروبول موسكو', 'hotel_metropol_moscow'],
            ['Ararat Park Hyatt Moscow', 'فندق أرارات بارك حياة موسكو', 'ararat_park_hyatt_moscow'],
            ['The St. Regis Moscow', 'فندق سانت ريجيس موسكو', 'the_st_regis_moscow'],
            ['Lotte Hotel Moscow', 'فندق لوته موسكو', 'lotte_hotel_moscow'],
            ['Radisson Collection Hotel Moscow', 'فندق راديسون كوليكشن موسكو', 'radisson_collection_hotel_moscow'],
            ['Swissotel Krasnye Holmy Moscow', 'فندق سويس أوتيل كراسني خولمي موسكو', 'swissotel_krasnye_holmy_moscow'],
            ['Hotel National Moscow', 'فندق ناشيونال موسكو', 'hotel_national_moscow'],
            ['Baltschug Kempinski Moscow', 'فندق بالتشوغ كيمبينسكي موسكو', 'baltschug_kempinski_moscow'],

            // Norway
            ['Hotel Continental Oslo', 'فندق كونتيننتال أوسلو', 'hotel_continental_oslo'],
            ['The Thief Oslo', 'فندق ذا ثيف أوسلو', 'the_thief_oslo'],
            ['Grand Hotel Oslo', 'فندق غراند أوسلو', 'grand_hotel_oslo'],
            ['Radisson Blu Plaza Hotel Oslo', 'فندق راديسون بلو بلازا أوسلو', 'radisson_blu_plaza_hotel_oslo'],
            ['Clarion Hotel The Hub', 'فندق كلاريون ذا هاب', 'clarion_hotel_the_hub'],
            ['Scandic Holmenkollen Park', 'فندق سكانديك هولمنكولن بارك', 'scandic_holmenkollen_park'],
            ['Hotel Bristol Oslo', 'فندق بريستول أوسلو', 'hotel_bristol_oslo'],
            ['Amerikalinjen Oslo', 'فندق أمريكالينين أوسلو', 'amerikalinjen_oslo'],
            ['Scandic Vulkan', 'فندق سكانديك فولكان', 'scandic_vulkan'],
            ['Villa Frogner', 'فيلا فروغنر', 'villa_frogner'],

            // USA
            ['The Plaza New York', 'فندق بلازا نيويورك', 'the_plaza_new_york'],
            ['The St. Regis New York', 'فندق سانت ريجيس نيويورك', 'the_st_regis_new_york'],
            ['The Carlyle New York', 'فندق كارلايل نيويورك', 'the_carlyle_new_york'],
            ['The Pierre New York', 'فندق بيير نيويورك', 'the_pierre_new_york'],
            ['The Ritz-Carlton New York', 'فندق ريتز كارلتون نيويورك', 'the_ritz_carlton_new_york'],
            ['Four Seasons Hotel New York', 'فندق فور سيزونز نيويورك', 'four_seasons_hotel_new_york'],
            ['The Mark New York', 'فندق ذا مارك نيويورك', 'the_mark_new_york'],
            ['The Lowell New York', 'فندق لويل نيويورك', 'the_lowell_new_york'],
            ['The Sherry-Netherland', 'فندق شيري نيذرلاند', 'the_sherry_netherland'],
            ['The Greenwich Hotel', 'فندق غرينتش', 'the_greenwich_hotel'],

            // France
            ['The Ritz Paris', 'فندق ريتز باريس', 'the_ritz_paris'],
            ['Four Seasons Hotel George V', 'فندق فور سيزونز جورج الخامس', 'four_seasons_hotel_george_v'],
            ['Le Bristol Paris', 'فندق لو بريستول باريس', 'le_bristol_paris'],
            ['Hotel Plaza Athénée', 'فندق بلازا أثينيه', 'hotel_plaza_athenee'],
            ['Le Meurice', 'فندق لو موريس', 'le_meurice'],
            ['Shangri-La Hotel Paris', 'فندق شانغريلا باريس', 'shangri_la_hotel_paris'],
            ['Park Hyatt Paris-Vendôme', 'فندق بارك حياة باريس فاندوم', 'park_hyatt_paris_vendome'],
            ['Hotel de Crillon', 'فندق دو كريون', 'hotel_de_crillon'],
            ['La Réserve Paris', 'فندق لا ريزيرف باريس', 'la_reserve_paris'],
            ['Hotel Lutetia', 'فندق لوتيتيا', 'hotel_lutetia'],

            // Germany
            ['Hotel Adlon Kempinski Berlin', 'فندق أدلون كيمبينسكي برلين', 'hotel_adlon_kempinski_berlin'],
            ['The Ritz-Carlton Berlin', 'فندق ريتز كارلتون برلين', 'the_ritz_carlton_berlin'],
            ['Regent Berlin', 'فندق ريجنت برلين', 'regent_berlin'],
            ['Hotel de Rome Berlin', 'فندق دو روم برلين', 'hotel_de_rome_berlin'],
            ['Das Stue Berlin', 'فندق داس ستو برلين', 'das_stue_berlin'],
            ['Soho House Berlin', 'فندق سوهو هاوس برلين', 'soho_house_berlin'],
            ['Grand Hyatt Berlin', 'فندق غراند حياة برلين', 'grand_hyatt_berlin'],
            ['Hotel Brandenburger Hof', 'فندق براندنبورغر هوف', 'hotel_brandenburger_hof'],
            ['Orania Berlin', 'فندق أورانيا برلين', 'orania_berlin'],
            ['Titanic Gendarmenmarkt Berlin', 'فندق تيتانيك جندارمنماركت برلين', 'titanic_gendarmenmarkt_berlin'],

            // Italy
            ['Hotel de Russie Rome', 'فندق دو روسي روما', 'hotel_de_russie_rome'],
            ['The St. Regis Rome', 'فندق سانت ريجيس روما', 'the_st_regis_rome'],
            ['Hotel Splendido Portofino', 'فندق سبليندو بورتوفينو', 'hotel_splendido_portofino'],
            ['Gritti Palace Venice', 'قصر غريتي البندقية', 'gritti_palace_venice'],
            ['Villa San Martino Martina Franca', 'فيلا سان مارتينو مارتينا فرانكا', 'villa_san_martino_martina_franca'],
            ['Four Seasons Hotel Milano', 'فندق فور سيزونز ميلانو', 'four_seasons_hotel_milano'],
            ['Hotel Villa Cimbrone Ravello', 'فندق فيلا تشيمبروني رافيلو', 'hotel_villa_cimbrone_ravello'],
            ['Belmond Hotel Caruso', 'فندق بيلموند كاروسو', 'belmond_hotel_caruso'],
            ['Palazzo Margherita Basilicata', 'قصر مارغريتا باسيليكاتا', 'palazzo_margherita_basilicata'],
            ['Hotel Hassler Roma', 'فندق هاسلر روما', 'hotel_hassler_roma'],

            // Spain
            ['Hotel Ritz Madrid', 'فندق ريتز مدريد', 'hotel_ritz_madrid'],
            ['Four Seasons Hotel Madrid', 'فندق فور سيزونز مدريد', 'four_seasons_hotel_madrid'],
            ['Hotel Arts Barcelona', 'فندق آرتس برشلونة', 'hotel_arts_barcelona'],
            ['Hotel Casa Fuster Barcelona', 'فندق كاسا فوستر برشلونة', 'hotel_casa_fuster_barcelona'],
            ['Hotel Alfonso XIII Seville', 'فندق ألفونسو الثالث عشر إشبيلية', 'hotel_alfonso_xiii_seville'],
            ['Parador de Granada', 'بارادور غرناطة', 'parador_de_granada'],
            ['Hotel Villa Magna Madrid', 'فندق فيلا ماغنا مدريد', 'hotel_villa_magna_madrid'],
            ['Gran Hotel La Florida Barcelona', 'فندق غران لا فلوريدا برشلونة', 'gran_hotel_la_florida_barcelona'],
            ['Hotel Maria Cristina San Sebastian', 'فندق ماريا كريستينا سان سيباستيان', 'hotel_maria_cristina_san_sebastian'],
            ['Hospes Palacio del Bailio Cordoba', 'فندق هوسبيس قصر البايليو قرطبة', 'hospes_palacio_del_bailio_cordoba'],

            // United Kingdom
            ['The Savoy London', 'فندق سافوي لندن', 'the_savoy_london'],
            ['Claridges London', 'فندق كلاريدجز لندن', 'claridges_london'],
            ['The Ritz London', 'فندق ريتز لندن', 'the_ritz_london'],
            ['The Langham London', 'فندق لانغهام لندن', 'the_langham_london'],
            ['Shangri-La Hotel at The Shard', 'فندق شانغريلا في ذا شارد', 'shangri_la_hotel_at_the_shard'],
            ['The Connaught London', 'فندق كونوت لندن', 'the_connaught_london'],
            ['Corinthia Hotel London', 'فندق كورينثيا لندن', 'corinthia_hotel_london'],
            ['The Dorchester London', 'فندق دورتشستر لندن', 'the_dorchester_london'],
            ['Rosewood London', 'فندق روزوود لندن', 'rosewood_london'],
            ['The Ned London', 'فندق ذا نيد لندن', 'the_ned_london'],

            // Japan
            ['The Ritz-Carlton Tokyo', 'فندق ريتز كارلتون طوكيو', 'the_ritz_carlton_tokyo'],
            ['Aman Tokyo', 'فندق أمان طوكيو', 'aman_tokyo'],
            ['Park Hyatt Tokyo', 'فندق بارك حياة طوكيو', 'park_hyatt_tokyo'],
            ['Four Seasons Hotel Tokyo at Marunouchi', 'فندق فور سيزونز طوكيو في مارونوتشي', 'four_seasons_hotel_tokyo_at_marunouchi'],
            ['The Peninsula Tokyo', 'فندق بينينسولا طوكيو', 'the_peninsula_tokyo'],
            ['Hoshinoya Tokyo', 'فندق هوشينويا طوكيو', 'hoshinoya_tokyo'],
            ['Imperial Hotel Tokyo', 'فندق إمبريال طوكيو', 'imperial_hotel_tokyo'],
            ['Shangri-La Hotel Tokyo', 'فندق شانغريلا طوكيو', 'shangri_la_hotel_tokyo'],
            ['The Tokyo Station Hotel', 'فندق محطة طوكيو', 'the_tokyo_station_hotel'],
            ['Mandarin Oriental Tokyo', 'فندق ماندارين أورينتال طوكيو', 'mandarin_oriental_tokyo'],

            // China
            ['The Peninsula Beijing', 'فندق بينينسولا بكين', 'the_peninsula_beijing'],
            ['Four Seasons Hotel Beijing', 'فندق فور سيزونز بكين', 'four_seasons_hotel_beijing'],
            ['The Ritz-Carlton Beijing', 'فندق ريتز كارلتون بكين', 'the_ritz_carlton_beijing'],
            ['Park Hyatt Beijing', 'فندق بارك حياة بكين', 'park_hyatt_beijing'],
            ['Shangri-La Hotel Beijing', 'فندق شانغريلا بكين', 'shangri_la_hotel_beijing'],
            ['The St. Regis Beijing', 'فندق سانت ريجيس بكين', 'the_st_regis_beijing'],
            ['Grand Hyatt Beijing', 'فندق غراند حياة بكين', 'grand_hyatt_beijing'],
            ['Rosewood Beijing', 'فندق روزوود بكين', 'rosewood_beijing'],
            ['NUO Hotel Beijing', 'فندق نو بكين', 'nuo_hotel_beijing'],
            ['The Opposite House Beijing', 'فندق أوبوزيت هاوس بكين', 'the_opposite_house_beijing'],

            // India
            ['The Oberoi New Delhi', 'فندق أوبيروي نيودلهي', 'the_oberoi_new_delhi'],
            ['The Leela Palace New Delhi', 'قصر ليلا نيودلهي', 'the_leela_palace_new_delhi'],
            ['Taj Mahal Palace Mumbai', 'قصر تاج محل مومباي', 'taj_mahal_palace_mumbai'],
            ['The Oberoi Mumbai', 'فندق أوبيروي مومباي', 'the_oberoi_mumbai'],
            ['Four Seasons Hotel Mumbai', 'فندق فور سيزونز مومباي', 'four_seasons_hotel_mumbai'],
            ['The Leela Palace Udaipur', 'قصر ليلا أودايبور', 'the_leela_palace_udaipur'],
            ['Taj Lake Palace Udaipur', 'قصر تاج البحيرة أودايبور', 'taj_lake_palace_udaipur'],
            ['Rambagh Palace Jaipur', 'قصر رامباغ جايبور', 'rambagh_palace_jaipur'],
            ['The Oberoi Rajvilas Jaipur', 'فندق أوبيروي راجفيلاس جايبور', 'the_oberoi_rajvilas_jaipur'],
            ['ITC Grand Chola Chennai', 'فندق آي تي سي غراند تشولا تشيناي', 'itc_grand_chola_chennai'],

            // Brazil
            ['Copacabana Palace Rio de Janeiro', 'قصر كوباكابانا ريو دي جانيرو', 'copacabana_palace_rio_de_janeiro'],
            ['Fasano Hotel Rio de Janeiro', 'فندق فاسانو ريو دي جانيرو', 'fasano_hotel_rio_de_janeiro'],
            ['Hotel Unique São Paulo', 'فندق يونيك ساو باولو', 'hotel_unique_sao_paulo'],
            ['Fasano São Paulo', 'فندق فاسانو ساو باولو', 'fasano_sao_paulo'],
            ['Grand Hyatt São Paulo', 'فندق غراند حياة ساو باولو', 'grand_hyatt_sao_paulo'],
            ['Hotel Emiliano São Paulo', 'فندق إيميليانو ساو باولو', 'hotel_emiliano_sao_paulo'],
            ['Rosewood São Paulo', 'فندق روزوود ساو باولو', 'rosewood_sao_paulo'],
            ['JW Marriott Hotel Rio de Janeiro', 'فندق جي دبليو ماريوت ريو دي جانيرو', 'jw_marriott_hotel_rio_de_janeiro'],
            ['Fairmont Rio de Janeiro Copacabana', 'فندق فيرمونت ريو دي جانيرو كوباكابانا', 'fairmont_rio_de_janeiro_copacabana'],
            ['Hotel Fasano Boa Vista', 'فندق فاسانو بوا فيستا', 'hotel_fasano_boa_vista'],

            // Canada
            ['Fairmont Chateau Lake Louise', 'فندق فيرمونت شاتو بحيرة لويز', 'fairmont_chateau_lake_louise'],
            ['Four Seasons Hotel Toronto', 'فندق فور سيزونز تورونتو', 'four_seasons_hotel_toronto'],
            ['The Ritz-Carlton Toronto', 'فندق ريتز كارلتون تورونتو', 'the_ritz_carlton_toronto'],
            ['Fairmont Royal York Toronto', 'فندق فيرمونت رويال يورك تورونتو', 'fairmont_royal_york_toronto'],
            ['Rosewood Hotel Georgia Vancouver', 'فندق روزوود جورجيا فانكوفر', 'rosewood_hotel_georgia_vancouver'],
            ['Four Seasons Hotel Vancouver', 'فندق فور سيزونز فانكوفر', 'four_seasons_hotel_vancouver'],
            ['Fairmont Hotel Vancouver', 'فندق فيرمونت فانكوفر', 'fairmont_hotel_vancouver'],
            ['Château Frontenac Quebec City', 'شاتو فرونتيناك مدينة كيبيك', 'chateau_frontenac_quebec_city'],
            ['Four Seasons Hotel Montreal', 'فندق فور سيزونز مونتريال', 'four_seasons_hotel_montreal'],
            ['The Ritz-Carlton Montreal', 'فندق ريتز كارلتون مونتريال', 'the_ritz_carlton_montreal'],

            // Australia
            ['Park Hyatt Sydney', 'فندق بارك حياة سيدني', 'park_hyatt_sydney'],
            ['Four Seasons Hotel Sydney', 'فندق فور سيزونز سيدني', 'four_seasons_hotel_sydney'],
            ['The Langham Sydney', 'فندق لانغهام سيدني', 'the_langham_sydney'],
            ['Shangri-La Hotel Sydney', 'فندق شانغريلا سيدني', 'shangri_la_hotel_sydney'],
            ['Crown Towers Melbourne', 'أبراج كراون ملبورن', 'crown_towers_melbourne'],
            ['Park Hyatt Melbourne', 'فندق بارك حياة ملبورن', 'park_hyatt_melbourne'],
            ['The Langham Melbourne', 'فندق لانغهام ملبورن', 'the_langham_melbourne'],
            ['COMO The Treasury Perth', 'فندق كومو الخزانة بيرث', 'como_the_treasury_perth'],
            ['Emporium Hotel South Bank Brisbane', 'فندق إمبوريوم ساوث بانك بريسبان', 'emporium_hotel_south_bank_brisbane'],
            ['The Calile Hotel Brisbane', 'فندق كاليل بريسبان', 'the_calile_hotel_brisbane'],

            // Mexico
            ['Four Seasons Hotel Mexico City', 'فندق فور سيزونز مكسيكو سيتي', 'four_seasons_hotel_mexico_city'],
            ['The St. Regis Mexico City', 'فندق سانت ريجيس مكسيكو سيتي', 'the_st_regis_mexico_city'],
            ['JW Marriott Hotel Mexico City', 'فندق جي دبليو ماريوت مكسيكو سيتي', 'jw_marriott_hotel_mexico_city'],
            ['Grand Fiesta Americana Chapultepec', 'فندق غراند فييستا أمريكانا تشابولتيبيك', 'grand_fiesta_americana_chapultepec'],
            ['Hotel Presidente InterContinental', 'فندق بريزيدنتي إنتركونتيننتال', 'hotel_presidente_intercontinental'],
            ['Rosewood San Miguel de Allende', 'فندق روزوود سان ميغيل دي أليندي', 'rosewood_san_miguel_de_allende'],
            ['Belmond Casa de Sierra Nevada', 'فندق بيلموند كاسا دي سييرا نيفادا', 'belmond_casa_de_sierra_nevada'],
            ['Grand Velas Riviera Maya', 'فندق غراند فيلاس ريفييرا مايا', 'grand_velas_riviera_maya'],
            ['Rosewood Mayakoba', 'فندق روزوود مايكوبا', 'rosewood_mayakoba'],
            ['Banyan Tree Mayakoba', 'فندق بانيان تري مايكوبا', 'banyan_tree_mayakoba'],

            // Turkey
            ['Four Seasons Hotel Istanbul at Sultanahmet', 'فندق فور سيزونز إسطنبول في السلطان أحمد', 'four_seasons_hotel_istanbul_at_sultanahmet'],
            ['Çırağan Palace Kempinski Istanbul', 'قصر تشيراغان كيمبينسكي إسطنبول', 'ciragan_palace_kempinski_istanbul'],
            ['The Ritz-Carlton Istanbul', 'فندق ريتز كارلتون إسطنبول', 'the_ritz_carlton_istanbul'],
            ['Shangri-La Bosphorus Istanbul', 'فندق شانغريلا البوسفور إسطنبول', 'shangri_la_bosphorus_istanbul'],
            ['Park Hyatt Istanbul Macka Palas', 'فندق بارك حياة إسطنبول ماتشكا بالاس', 'park_hyatt_istanbul_macka_palas'],
            ['Six Senses Kaplankaya', 'فندق سيكس سينسز كابلانكايا', 'six_senses_kaplankaya'],
            ['Mandarin Oriental Bodrum', 'فندق ماندارين أورينتال بودروم', 'mandarin_oriental_bodrum'],
            ['The Edition Bodrum', 'فندق إديشن بودروم', 'the_edition_bodrum'],
            ['Swissotel The Bosphorus Istanbul', 'فندق سويس أوتيل البوسفور إسطنبول', 'swissotel_the_bosphorus_istanbul'],
            ['Conrad Istanbul Bosphorus', 'فندق كونراد إسطنبول البوسفور', 'conrad_istanbul_bosphorus'],

            // Thailand
            ['The Oriental Bangkok', 'فندق أورينتال بانكوك', 'the_oriental_bangkok'],
            ['Four Seasons Hotel Bangkok', 'فندق فور سيزونز بانكوك', 'four_seasons_hotel_bangkok'],
            ['The St. Regis Bangkok', 'فندق سانت ريجيس بانكوك', 'the_st_regis_bangkok'],
            ['Park Hyatt Bangkok', 'فندق بارك حياة بانكوك', 'park_hyatt_bangkok'],
            ['Shangri-La Hotel Bangkok', 'فندق شانغريلا بانكوك', 'shangri_la_hotel_bangkok'],
            ['Rosewood Bangkok', 'فندق روزوود بانكوك', 'rosewood_bangkok'],
            ['Four Seasons Resort Chiang Mai', 'منتجع فور سيزونز تشيانغ ماي', 'four_seasons_resort_chiang_mai'],
            ['Anantara Golden Triangle', 'فندق أنانتارا المثلث الذهبي', 'anantara_golden_triangle'],
            ['Six Senses Yao Noi', 'فندق سيكس سينسز ياو نوي', 'six_senses_yao_noi'],
            ['Amanpuri Phuket', 'فندق أمانبوري فوكيت', 'amanpuri_phuket'],

            // South Korea
            ['Park Hyatt Seoul', 'فندق بارك حياة سيول', 'park_hyatt_seoul'],
            ['Four Seasons Hotel Seoul', 'فندق فور سيزونز سيول', 'four_seasons_hotel_seoul'],
            ['The Shilla Seoul', 'فندق شيلا سيول', 'the_shilla_seoul'],
            ['Grand Hyatt Seoul', 'فندق غراند حياة سيول', 'grand_hyatt_seoul'],
            ['JW Marriott Hotel Seoul', 'فندق جي دبليو ماريوت سيول', 'jw_marriott_hotel_seoul'],
            ['Lotte Hotel Seoul', 'فندق لوته سيول', 'lotte_hotel_seoul'],
            ['Conrad Seoul', 'فندق كونراد سيول', 'conrad_seoul'],
            ['Banyan Tree Club & Spa Seoul', 'فندق بانيان تري كلوب وسبا سيول', 'banyan_tree_club_spa_seoul'],
            ['Signiel Seoul', 'فندق سيغنيل سيول', 'signiel_seoul'],
            ['The Westin Chosun Seoul', 'فندق ويستن تشوسون سيول', 'the_westin_chosun_seoul'],

            // UAE
            ['Burj Al Arab Jumeirah', 'برج العرب جميرا', 'burj_al_arab_jumeirah'],
            ['Four Seasons Resort Dubai at Jumeirah Beach', 'منتجع فور سيزونز دبي في شاطئ جميرا', 'four_seasons_resort_dubai_at_jumeirah_beach'],
            ['The Ritz-Carlton Dubai', 'فندق ريتز كارلتون دبي', 'the_ritz_carlton_dubai'],
            ['Park Hyatt Dubai', 'فندق بارك حياة دبي', 'park_hyatt_dubai'],
            ['Atlantis The Palm', 'أتلانتس النخلة', 'atlantis_the_palm'],
            ['Emirates Palace Abu Dhabi', 'قصر الإمارات أبوظبي', 'emirates_palace_abu_dhabi'],
            ['The St. Regis Abu Dhabi', 'فندق سانت ريجيس أبوظبي', 'the_st_regis_abu_dhabi'],
            ['Four Seasons Hotel Abu Dhabi', 'فندق فور سيزونز أبوظبي', 'four_seasons_hotel_abu_dhabi'],
            ['Rosewood Abu Dhabi', 'فندق روزوود أبوظبي', 'rosewood_abu_dhabi'],
            ['Shangri-La Hotel Qaryat Al Beri', 'فندق شانغريلا قرية البري', 'shangri_la_hotel_qaryat_al_beri'],

            // Singapore
            ['Marina Bay Sands', 'مارينا باي ساندز', 'marina_bay_sands'],
            ['The Ritz-Carlton Millenia Singapore', 'فندق ريتز كارلتون ميلينيا سنغافورة', 'the_ritz_carlton_millenia_singapore'],
            ['Four Seasons Hotel Singapore', 'فندق فور سيزونز سنغافورة', 'four_seasons_hotel_singapore'],
            ['Shangri-La Hotel Singapore', 'فندق شانغريلا سنغافورة', 'shangri_la_hotel_singapore'],
            ['The St. Regis Singapore', 'فندق سانت ريجيس سنغافورة', 'the_st_regis_singapore'],
            ['Capella Singapore', 'فندق كابيلا سنغافورة', 'capella_singapore'],
            ['Raffles Hotel Singapore', 'فندق رافلز سنغافورة', 'raffles_hotel_singapore'],
            ['Park Hyatt Singapore', 'فندق بارك حياة سنغافورة', 'park_hyatt_singapore'],
            ['Conrad Centennial Singapore', 'فندق كونراد سنتينيال سنغافورة', 'conrad_centennial_singapore'],
            ['Mandarin Oriental Singapore', 'فندق ماندارين أورينتال سنغافورة', 'mandarin_oriental_singapore']
        ];

        foreach ($translations as $translation) {
            DB::table('hotel_translations')->insertOrIgnore([
                'original_title' => $translation[0],
                'arabic_title' => $translation[1],
                'slug' => $translation[2]
            ]);
        }
    }
}