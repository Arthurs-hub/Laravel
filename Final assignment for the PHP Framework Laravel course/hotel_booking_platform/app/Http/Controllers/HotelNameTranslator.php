<?php

namespace App\Http\Controllers;

class HotelNameTranslator
{
    public static function translateName($hotelTitle)
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $hotelTitle;
        }

        $translations = [
            'ar' => [

                'Crown Towers Melbourne' => 'أبراج كراون ملبورن',
                'Park Hyatt Melbourne' => 'بارك حياة ملبورن',
                'The Langham Melbourne' => 'ذا لانغهام ملبورن',
                'COMO The Treasury Perth' => 'كومو ذا تريجري بيرث',
                'Emporium Hotel South Bank Brisbane' => 'فندق إمبوريوم ساوث بانك بريسبان',
                'The Calile Hotel Brisbane' => 'فندق ذا كاليل بريسبان',
                'Park Hyatt Sydney' => 'بارك حياة سيدني',
                'Four Seasons Hotel Sydney' => 'فندق فور سيزونز سيدني',
                'The Langham Sydney' => 'ذا لانغهام سيدني',
                'Shangri-La Hotel Sydney' => 'فندق شانغريلا سيدني',

                // Brazilian hotels
                'JW Marriott Hotel Rio de Janeiro' => 'فندق جي دبليو ماريوت ريو دي جانيرو',
                'Fairmont Rio de Janeiro Copacabana' => 'فيرمونت ريو دي جانيرو كوباكابانا',
                'Copacabana Palace Rio de Janeiro' => 'قصر كوباكابانا ريو دي جانيرو',
                'Fasano Hotel Rio de Janeiro' => 'فندق فاسانو ريو دي جانيرو',
                'Hotel Unique São Paulo' => 'فندق يونيك ساو باولو',
                'Fasano São Paulo' => 'فاسانو ساو باولو',
                'Grand Hyatt São Paulo' => 'جراند حياة ساو باولو',
                'Hotel Emiliano São Paulo' => 'فندق إميليانو ساو باولو',
                'Rosewood São Paulo' => 'روزوود ساو باولو',
                'Hotel Fasano Boa Vista' => 'فندق فاسانو بوا فيستا',

                // Canadian hotels
                'Château Frontenac Quebec City' => 'شاتو فرونتيناك مدينة كيبيك',
                'Four Seasons Hotel Montreal' => 'فندق فور سيزونز مونتريال',
                'The Ritz-Carlton Montreal' => 'ذا ريتز كارلتون مونتريال',
                'Rosewood Hotel Georgia Vancouver' => 'فندق روزوود جورجيا فانكوفر',
                'Fairmont Hotel Vancouver' => 'فندق فيرمونت فانكوفر',
                'Fairmont Chateau Lake Louise' => 'فيرمونت شاتو بحيرة لويز',
                'Four Seasons Hotel Toronto' => 'فندق فور سيزونز تورونتو',
                'The Ritz-Carlton Toronto' => 'ذا ريتز كارلتون تورونتو',
                'Fairmont Royal York Toronto' => 'فيرمونت رويال يورك تورونتو',
                'Four Seasons Hotel Vancouver' => 'فندق فور سيزونز فانكوفر',

                // Chinese hotels
                'NUO Hotel Beijing' => 'فندق نوو بكين',
                'The Opposite House Beijing' => 'ذا أوبوزيت هاوس بكين',
                'The Ritz-Carlton Beijing' => 'ذا ريتز كارلتون بكين',
                'Park Hyatt Beijing' => 'بارك حياة بكين',
                'Shangri-La Hotel Beijing' => 'فندق شانغريلا بكين',
                'The St. Regis Beijing' => 'ذا سانت ريجيس بكين',
                'Grand Hyatt Beijing' => 'جراند حياة بكين',
                'The Peninsula Beijing' => 'ذا بينينسولا بكين',
                'Four Seasons Hotel Beijing' => 'فندق فور سيزونز بكين',
                'Rosewood Beijing' => 'روزوود بكين',

                // French hotels
                'Park Hyatt Paris-Vendôme' => 'بارك حياة باريس فاندوم',
                'La Réserve Paris' => 'لا ريزيرف باريس',
                'Hotel Lutetia' => 'فندق لوتيتيا',
                'Four Seasons Hotel George V' => 'فندق فور سيزونز جورج الخامس',
                'Le Bristol Paris' => 'لو بريستول باريس',
                'Le Meurice' => 'لو موريس',
                'Shangri-La Hotel Paris' => 'فندق شانغريلا باريس',
                'Hotel de Crillon' => 'فندق دو كريون',
                'The Ritz Paris' => 'ذا ريتز باريس',
                'Hotel Plaza Athénée' => 'فندق بلازا أثينيه',
            ]
        ];

        return $translations[$locale][$hotelTitle] ?? $hotelTitle;
    }

    public static function translateAddress($address)
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $address;
        }

        $translations = [
            'ar' => [
                // Australian addresses
                '8 Whiteman Street, Melbourne, VIC' => '8 شارع وايتمان، ملبورن، فيكتوريا',
                '1 Parliament Square, Melbourne, VIC' => '1 ميدان البرلمان، ملبورن، فيكتوريا',
                '1 Southgate Avenue, Melbourne, VIC' => '1 شارع ساوث غيت، ملبورن، فيكتوريا',
                '1 Cathedral Avenue, Perth, WA' => '1 شارع الكاتدرائية، بيرث، أستراليا الغربية',
                '267 Grey Street, Brisbane, QLD' => '267 شارع غراي، بريسبان، كوينزلاند',
                '39 James Street, Brisbane, QLD' => '39 شارع جيمس، بريسبان، كوينزلاند',
                '7 Hickson Road, Sydney, NSW' => '7 طريق هيكسون، سيدني، نيو ساوث ويلز',
                '199 George Street, Sydney, NSW' => '199 شارع جورج، سيدني، نيو ساوث ويلز',
                '89-113 Kent Street, Sydney, NSW' => '89-113 شارع كينت، سيدني، نيو ساوث ويلز',
                '176 Cumberland Street, Sydney, NSW' => '176 شارع كمبرلاند، سيدني، نيو ساوث ويلز',

                // Brazilian addresses
                'Avenida Atlântica 4240, Rio de Janeiro' => 'شارع أتلانتيكا 4240، ريو دي جانيرو',
                'Avenida Vieira Souto 80, Rio de Janeiro' => 'شارع فييرا سوتو 80، ريو دي جانيرو',
                'Avenida Brigadeiro Luís Antônio 4700, São Paulo' => 'شارع بريغاديرو لويس أنطونيو 4700، ساو باولو',
                'Rua Oscar Freire 384, São Paulo' => 'شارع أوسكار فريري 384، ساو باولو',

                // Canadian addresses
                '1 Rue des Carrières, Quebec City, QC' => '1 شارع دي كاريير، مدينة كيبيك، كيبيك',
                '1440 Rue de la Montagne, Montreal, QC' => '1440 شارع دي لا مونتاني، مونتريال، كيبيك',
                '1228 Sherbrooke Street West, Montreal, QC' => '1228 شارع شيربروك الغربي، مونتريال، كيبيك',
                '801 West Georgia Street, Vancouver, BC' => '801 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
                '791 West Georgia Street, Vancouver, BC' => '791 شارع جورجيا الغربي، فانكوفر، كولومبيا البريطانية',
                '111 Lake Louise Drive, Lake Louise, AB' => '111 طريق بحيرة لويز، بحيرة لويز، ألبرتا',
                '60 Yorkville Avenue, Toronto, ON' => '60 شارع يوركفيل، تورونتو، أونتاريو',
                '181 Wellington Street West, Toronto, ON' => '181 شارع ولينغتون الغربي، تورونتو، أونتاريو',
                '100 Front Street West, Toronto, ON' => '100 شارع فرونت الغربي، تورونتو، أونتاريو',

                // Chinese addresses
                '83A Jianguo Road, Beijing' => '83أ طريق جيانغوو، بكين',
                '11 Sanlitun Road, Beijing' => '11 طريق سانليتون، بكين',
                '1 East Chang An Avenue, Beijing' => '1 شارع تشانغ آن الشرقي، بكين',
                '2 Jianguomenwai Avenue, Beijing' => '2 شارع جيانغومينواي، بكين',
                '29 Zizhuyuan Road, Beijing' => '29 طريق زيزويوان، بكين',
                '21 Jianguomenwai Street, Beijing' => '21 شارع جيانغومينواي، بكين',
                '8 Goldfish Lane, Beijing' => '8 زقاق السمك الذهبي، بكين',
                '48 Liangmaqiao Road, Beijing' => '48 طريق ليانغماتشياو، بكين',
                '1 Sanlitun Road, Beijing' => '1 طريق سانليتون، بكين',

                // French addresses
                '5 Rue de la Paix, Paris' => '5 شارع السلام، باريس',
                '42 Avenue Gabriel, Paris' => '42 شارع غابرييل، باريس',
                '45 Boulevard Raspail, Paris' => '45 شارع راسباي، باريس',
                '31 Avenue George V, Paris' => '31 شارع جورج الخامس، باريس',
                '112 Rue du Faubourg Saint-Honoré, Paris' => '112 شارع فوبورغ سان أونوريه، باريس',
                '228 Rue de Rivoli, Paris' => '228 شارع ريفولي، باريس',
                '10 Avenue d\'Iéna, Paris' => '10 شارع إيينا، باريس',
                '10 Place de la Concorde, Paris' => '10 ميدان الكونكورد، باريس',
                '15 Place Vendôme, Paris' => '15 ميدان فاندوم، باريس',
                '25 Avenue Montaigne, Paris' => '25 شارع مونتين، باريس',

                // German hotels
                'Grand Hyatt Berlin' => 'جراند حياة برلين',
                'Hotel Brandenburger Hof' => 'فندق براندنبورغر هوف',
                'Orania Berlin' => 'أورانيا برلين',
                'Titanic Gendarmenmarkt Berlin' => 'تيتانيك جندارمين ماركت برلين',
                'The Ritz-Carlton Berlin' => 'ذا ريتز كارلتون برلين',
                'Regent Berlin' => 'ريجنت برلين',
                'Hotel de Rome Berlin' => 'فندق دو روم برلين',
                'Das Stue Berlin' => 'داس ستو برلين',
                'Soho House Berlin' => 'سوهو هاوس برلين',
                'Hotel Adlon Kempinski Berlin' => 'فندق أدلون كيمبينسكي برلين',

                // German addresses
                'Marlene-Dietrich-Platz 2, Berlin' => 'ميدان مارلين ديتريش 2، برلين',
                'Eislebener Straße 14, Berlin' => 'شارع إيسليبينر 14، برلين',
                'Oranienstraße 40, Berlin' => 'شارع أورانين 40، برلين',
                'Charlottenstraße 50-52, Berlin' => 'شارع شارلوتين 50-52، برلين',
                'Potsdamer Platz 3, Berlin' => 'ميدان بوتسدام 3، برلين',
                'Charlottenstraße 49, Berlin' => 'شارع شارلوتين 49، برلين',
                'Behrenstraße 37, Berlin' => 'شارع بيهرين 37، برلين',
                'Drakestraße 1, Berlin' => 'شارع دريك 1، برلين',
                'Torstraße 1, Berlin' => 'شارع تور 1، برلين',
                'Unter den Linden 77, Berlin' => 'تحت الزيزفون 77، برلين',

                // Indian hotels
                'ITC Grand Chola Chennai' => 'آي تي سي جراند تشولا تشيناي',
                'Taj Mahal Palace Mumbai' => 'قصر تاج محل مومباي',
                'The Oberoi Mumbai' => 'ذا أوبيروي مومباي',
                'Four Seasons Hotel Mumbai' => 'فندق فور سيزونز مومباي',
                'The Leela Palace Udaipur' => 'قصر ليلا أودايبور',
                'Taj Lake Palace Udaipur' => 'قصر تاج ليك أودايبور',
                'Rambagh Palace Jaipur' => 'قصر رامباغ جايبور',
                'The Oberoi Rajvilas Jaipur' => 'ذا أوبيروي راجفيلاس جايبور',
                'The Oberoi New Delhi' => 'ذا أوبيروي نيو دلهي',
                'The Leela Palace New Delhi' => 'قصر ليلا نيو دلهي',

                // Indian addresses
                '63 Mount Road, Chennai' => '63 طريق ماونت، تشيناي',
                'Apollo Bunder, Mumbai' => 'أبولو بندر، مومباي',
                'Nariman Point, Mumbai' => 'نقطة ناريمان، مومباي',
                'Worli, Mumbai' => 'وورلي، مومباي',
                'Lake Pichola, Udaipur' => 'بحيرة بيتشولا، أودايبور',
                'Pichola Lake, Udaipur' => 'بحيرة بيتشولا، أودايبور',
                'Bhawani Singh Road, Jaipur' => 'طريق بهاواني سينغ، جايبور',
                'Goner Road, Jaipur' => 'طريق غونر، جايبور',
                'Dr. A.P.J. Abdul Kalam Road, New Delhi' => 'طريق د. أ. ب. ج. عبد الكلام، نيو دلهي',
                'Diplomatic Enclave, New Delhi' => 'الجيب الدبلوماسي، نيو دلهي',

                // Italian hotels
                'Palazzo Margherita Basilicata' => 'بالاتزو مارغيريتا بازيليكاتا',
                'Hotel Hassler Roma' => 'فندق هاسلر روما',
                'Hotel Villa Cimbrone Ravello' => 'فندق فيلا سيمبروني رافيلو',
                'Belmond Hotel Caruso' => 'فندق بيلموند كاروزو',
                'Hotel Splendido Portofino' => 'فندق سبلينديدو بورتوفينو',
                'Gritti Palace Venice' => 'قصر غريتي البندقية',
                'Villa San Martino Martina Franca' => 'فيلا سان مارتينو مارتينا فرانكا',
                'Four Seasons Hotel Milano' => 'فندق فور سيزونز ميلانو',
                'The St. Regis Rome' => 'ذا سانت ريجيس روما',
                'Hotel de Russie Rome' => 'فندق دو روسي روما',

                // Italian addresses
                'Via del Corso 126, Bernalda' => 'شارع ديل كورسو 126، بيرنالدا',
                'Piazza Trinità dei Monti 6, Rome' => 'ميدان ترينيتا ديي مونتي 6، روما',
                'Via Santa Chiara 26, Ravello' => 'شارع سانتا كيارا 26، رافيلو',
                'Piazza San Giovanni del Toro 2, Ravello' => 'ميدان سان جيوفاني ديل تورو 2، رافيلو',
                'Salita Baratta 16, Portofino' => 'ساليتا باراتا 16، بورتوفينو',
                'Campo Santa Maria del Giglio 2467, Venice' => 'ميدان سانتا ماريا ديل جيليو 2467، البندقية',
                'Via San Martino 8, Martina Franca' => 'شارع سان مارتينو 8، مارتينا فرانكا',
                'Via Montenapoleone 8, Milan' => 'شارع مونتينابوليوني 8، ميلان',
                'Via Vittorio Emanuele Orlando 3, Rome' => 'شارع فيتوريو إيمانويلي أورلاندو 3، روما',
                'Via del Babuino 9, Rome' => 'شارع ديل بابوينو 9، روما',
            ]
        ];

        return $translations[$locale][$address] ?? $address;
    }
}