<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\Room;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            // Russia (10 hotels)
            ['title' => 'The Ritz-Carlton Moscow', 'country' => 'Russia', 'address' => 'Tverskaya Street 3, Moscow'],
            ['title' => 'Four Seasons Hotel Moscow', 'country' => 'Russia', 'address' => 'Okhotny Ryad 2, Moscow'],
            ['title' => 'Hotel Metropol Moscow', 'country' => 'Russia', 'address' => 'Teatralny Proezd 2, Moscow'],
            ['title' => 'Ararat Park Hyatt Moscow', 'country' => 'Russia', 'address' => 'Neglinnaya Street 4, Moscow'],
            ['title' => 'The St. Regis Moscow', 'country' => 'Russia', 'address' => 'Nikolskaya Street 12, Moscow'],
            ['title' => 'Lotte Hotel Moscow', 'country' => 'Russia', 'address' => 'Novinskiy Boulevard 8, Moscow'],
            ['title' => 'Radisson Collection Hotel Moscow', 'country' => 'Russia', 'address' => 'Kutuzovsky Prospekt 2/1, Moscow'],
            ['title' => 'Swissotel Krasnye Holmy Moscow', 'country' => 'Russia', 'address' => 'Kosmodamianskaya Naberezhnaya 52, Moscow'],
            ['title' => 'Hotel National Moscow', 'country' => 'Russia', 'address' => 'Mokhovaya Street 15/1, Moscow'],
            ['title' => 'Baltschug Kempinski Moscow', 'country' => 'Russia', 'address' => 'Balchug Street 1, Moscow'],

            // Norway (10 hotels)
            ['title' => 'Hotel Continental Oslo', 'country' => 'Norway', 'address' => 'Stortingsgata 24/26, Oslo'],
            ['title' => 'The Thief Oslo', 'country' => 'Norway', 'address' => 'Landgangen 1, Oslo'],
            ['title' => 'Grand Hotel Oslo', 'country' => 'Norway', 'address' => 'Karl Johans gate 31, Oslo'],
            ['title' => 'Radisson Blu Plaza Hotel Oslo', 'country' => 'Norway', 'address' => 'Sonja Henies plass 3, Oslo'],
            ['title' => 'Clarion Hotel The Hub', 'country' => 'Norway', 'address' => 'Biskop Gunnerus gate 3, Oslo'],
            ['title' => 'Scandic Holmenkollen Park', 'country' => 'Norway', 'address' => 'Kongeveien 26, Oslo'],
            ['title' => 'Hotel Bristol Oslo', 'country' => 'Norway', 'address' => 'Kristian IVs gate 7, Oslo'],
            ['title' => 'Amerikalinjen Oslo', 'country' => 'Norway', 'address' => 'Jernbanetorget 2, Oslo'],
            ['title' => 'Scandic Vulkan', 'country' => 'Norway', 'address' => 'Vulkan 13B, Oslo'],
            ['title' => 'Villa Frogner', 'country' => 'Norway', 'address' => 'Nordahl Bruns gate 26, Oslo'],

            // USA (10 hotels)
            ['title' => 'The Plaza New York', 'country' => 'USA', 'address' => '768 5th Ave, New York, NY'],
            ['title' => 'The St. Regis New York', 'country' => 'USA', 'address' => '2 E 55th St, New York, NY'],
            ['title' => 'The Carlyle New York', 'country' => 'USA', 'address' => '35 E 76th St, New York, NY'],
            ['title' => 'The Pierre New York', 'country' => 'USA', 'address' => '2 E 61st St, New York, NY'],
            ['title' => 'The Ritz-Carlton New York', 'country' => 'USA', 'address' => '50 Central Park S, New York, NY'],
            ['title' => 'Four Seasons Hotel New York', 'country' => 'USA', 'address' => '57th St, New York, NY'],
            ['title' => 'The Mark New York', 'country' => 'USA', 'address' => '25 E 77th St, New York, NY'],
            ['title' => 'The Lowell New York', 'country' => 'USA', 'address' => '28 E 63rd St, New York, NY'],
            ['title' => 'The Sherry-Netherland', 'country' => 'USA', 'address' => '781 5th Ave, New York, NY'],
            ['title' => 'The Greenwich Hotel', 'country' => 'USA', 'address' => '377 Greenwich St, New York, NY'],

            // France (10 hotels)
            ['title' => 'The Ritz Paris', 'country' => 'France', 'address' => '15 Place Vendôme, Paris'],
            ['title' => 'Four Seasons Hotel George V', 'country' => 'France', 'address' => '31 Avenue George V, Paris'],
            ['title' => 'Le Bristol Paris', 'country' => 'France', 'address' => '112 Rue du Faubourg Saint-Honoré, Paris'],
            ['title' => 'Hotel Plaza Athénée', 'country' => 'France', 'address' => '25 Avenue Montaigne, Paris'],
            ['title' => 'Le Meurice', 'country' => 'France', 'address' => '228 Rue de Rivoli, Paris'],
            ['title' => 'Shangri-La Hotel Paris', 'country' => 'France', 'address' => '10 Avenue d\'Iéna, Paris'],
            ['title' => 'Park Hyatt Paris-Vendôme', 'country' => 'France', 'address' => '5 Rue de la Paix, Paris'],
            ['title' => 'Hotel de Crillon', 'country' => 'France', 'address' => '10 Place de la Concorde, Paris'],
            ['title' => 'La Réserve Paris', 'country' => 'France', 'address' => '42 Avenue Gabriel, Paris'],
            ['title' => 'Hotel Lutetia', 'country' => 'France', 'address' => '45 Boulevard Raspail, Paris'],

            // Germany (10 hotels)
            ['title' => 'Hotel Adlon Kempinski Berlin', 'country' => 'Germany', 'address' => 'Unter den Linden 77, Berlin'],
            ['title' => 'The Ritz-Carlton Berlin', 'country' => 'Germany', 'address' => 'Potsdamer Platz 3, Berlin'],
            ['title' => 'Regent Berlin', 'country' => 'Germany', 'address' => 'Charlottenstraße 49, Berlin'],
            ['title' => 'Hotel de Rome Berlin', 'country' => 'Germany', 'address' => 'Behrenstraße 37, Berlin'],
            ['title' => 'Das Stue Berlin', 'country' => 'Germany', 'address' => 'Drakestraße 1, Berlin'],
            ['title' => 'Soho House Berlin', 'country' => 'Germany', 'address' => 'Torstraße 1, Berlin'],
            ['title' => 'Grand Hyatt Berlin', 'country' => 'Germany', 'address' => 'Marlene-Dietrich-Platz 2, Berlin'],
            ['title' => 'Hotel Brandenburger Hof', 'country' => 'Germany', 'address' => 'Eislebener Straße 14, Berlin'],
            ['title' => 'Orania Berlin', 'country' => 'Germany', 'address' => 'Oranienstraße 40, Berlin'],
            ['title' => 'Titanic Gendarmenmarkt Berlin', 'country' => 'Germany', 'address' => 'Französische Straße 30, Berlin'],

            // Italy (10 hotels)
            ['title' => 'Hotel de Russie Rome', 'country' => 'Italy', 'address' => 'Via del Babuino 9, Rome'],
            ['title' => 'The St. Regis Rome', 'country' => 'Italy', 'address' => 'Via Vittorio Emanuele Orlando 3, Rome'],
            ['title' => 'Hotel Splendido Portofino', 'country' => 'Italy', 'address' => 'Salita Baratta 16, Portofino'],
            ['title' => 'Gritti Palace Venice', 'country' => 'Italy', 'address' => 'Campo Santa Maria del Giglio 2467, Venice'],
            ['title' => 'Villa San Martino Martina Franca', 'country' => 'Italy', 'address' => 'Via Taranto 59, Martina Franca'],
            ['title' => 'Four Seasons Hotel Milano', 'country' => 'Italy', 'address' => 'Via Gesù 6/8, Milan'],
            ['title' => 'Hotel Villa Cimbrone Ravello', 'country' => 'Italy', 'address' => 'Via Santa Chiara 26, Ravello'],
            ['title' => 'Belmond Hotel Caruso', 'country' => 'Italy', 'address' => 'Piazza San Giovanni del Toro 2, Ravello'],
            ['title' => 'Palazzo Margherita Basilicata', 'country' => 'Italy', 'address' => 'Corso Umberto 64, Bernalda'],
            ['title' => 'Hotel Hassler Roma', 'country' => 'Italy', 'address' => 'Piazza Trinità dei Monti 6, Rome'],

            // Spain (10 hotels)
            ['title' => 'Hotel Ritz Madrid', 'country' => 'Spain', 'address' => 'Plaza de la Lealtad 5, Madrid'],
            ['title' => 'Four Seasons Hotel Madrid', 'country' => 'Spain', 'address' => 'Calle de Sevilla 3, Madrid'],
            ['title' => 'Hotel Arts Barcelona', 'country' => 'Spain', 'address' => 'Carrer de la Marina 19-21, Barcelona'],
            ['title' => 'Hotel Casa Fuster Barcelona', 'country' => 'Spain', 'address' => 'Passeig de Gràcia 132, Barcelona'],
            ['title' => 'Hotel Alfonso XIII Seville', 'country' => 'Spain', 'address' => 'Calle San Fernando 2, Seville'],
            ['title' => 'Parador de Granada', 'country' => 'Spain', 'address' => 'Calle Real de la Alhambra, Granada'],
            ['title' => 'Hotel Villa Magna Madrid', 'country' => 'Spain', 'address' => 'Paseo de la Castellana 22, Madrid'],
            ['title' => 'Gran Hotel La Florida Barcelona', 'country' => 'Spain', 'address' => 'Carretera Vallvidrera al Tibidabo 83-93, Barcelona'],
            ['title' => 'Hotel Maria Cristina San Sebastian', 'country' => 'Spain', 'address' => 'Paseo República Argentina 4, San Sebastian'],
            ['title' => 'Hospes Palacio del Bailio Cordoba', 'country' => 'Spain', 'address' => 'Calle Ramírez de las Casas Deza 10-12, Cordoba'],

            // United Kingdom (10 hotels)
            ['title' => 'The Savoy London', 'country' => 'United Kingdom', 'address' => 'Strand, London WC2R 0EZ'],
            ['title' => 'Claridges London', 'country' => 'United Kingdom', 'address' => 'Brook Street, London W1K 4HR'],
            ['title' => 'The Ritz London', 'country' => 'United Kingdom', 'address' => '150 Piccadilly, London W1J 9BR'],
            ['title' => 'The Langham London', 'country' => 'United Kingdom', 'address' => '1C Portland Place, London W1B 1JA'],
            ['title' => 'Shangri-La Hotel at The Shard', 'country' => 'United Kingdom', 'address' => '31 St Thomas Street, London SE1 9QU'],
            ['title' => 'The Connaught London', 'country' => 'United Kingdom', 'address' => 'Carlos Place, London W1K 2AL'],
            ['title' => 'Corinthia Hotel London', 'country' => 'United Kingdom', 'address' => 'Whitehall Place, London SW1A 2BD'],
            ['title' => 'The Dorchester London', 'country' => 'United Kingdom', 'address' => '53 Park Lane, London W1K 1QA'],
            ['title' => 'Rosewood London', 'country' => 'United Kingdom', 'address' => '252 High Holborn, London WC1V 7EN'],
            ['title' => 'The Ned London', 'country' => 'United Kingdom', 'address' => '27 Poultry, London EC2R 8AJ'],

            // Japan (10 hotels)
            ['title' => 'The Ritz-Carlton Tokyo', 'country' => 'Japan', 'address' => '9-7-1 Akasaka, Minato City, Tokyo'],
            ['title' => 'Aman Tokyo', 'country' => 'Japan', 'address' => '1-5-6 Otemachi, Chiyoda City, Tokyo'],
            ['title' => 'Park Hyatt Tokyo', 'country' => 'Japan', 'address' => '3-7-1-2 Nishi Shinjuku, Shinjuku City, Tokyo'],
            ['title' => 'Four Seasons Hotel Tokyo at Marunouchi', 'country' => 'Japan', 'address' => '1-11-1 Marunouchi, Chiyoda City, Tokyo'],
            ['title' => 'The Peninsula Tokyo', 'country' => 'Japan', 'address' => '1-8-1 Yurakucho, Chiyoda City, Tokyo'],
            ['title' => 'Hoshinoya Tokyo', 'country' => 'Japan', 'address' => '1-9-1 Otemachi, Chiyoda City, Tokyo'],
            ['title' => 'Imperial Hotel Tokyo', 'country' => 'Japan', 'address' => '1-1-1 Uchisaiwaicho, Chiyoda City, Tokyo'],
            ['title' => 'Shangri-La Hotel Tokyo', 'country' => 'Japan', 'address' => '1-8-3 Marunouchi, Chiyoda City, Tokyo'],
            ['title' => 'The Tokyo Station Hotel', 'country' => 'Japan', 'address' => '1-9-1 Marunouchi, Chiyoda City, Tokyo'],
            ['title' => 'Mandarin Oriental Tokyo', 'country' => 'Japan', 'address' => '2-1-1 Nihonbashi Muromachi, Chuo City, Tokyo'],

            // China (10 hotels)
            ['title' => 'The Peninsula Beijing', 'country' => 'China', 'address' => '8 Goldfish Lane, Wangfujing, Beijing'],
            ['title' => 'Four Seasons Hotel Beijing', 'country' => 'China', 'address' => '48 Liangmaqiao Road, Beijing'],
            ['title' => 'The Ritz-Carlton Beijing', 'country' => 'China', 'address' => '83A Jianguo Road, Beijing'],
            ['title' => 'Park Hyatt Beijing', 'country' => 'China', 'address' => '2 Jianguomenwai Avenue, Beijing'],
            ['title' => 'Shangri-La Hotel Beijing', 'country' => 'China', 'address' => '29 Zizhuyuan Road, Beijing'],
            ['title' => 'The St. Regis Beijing', 'country' => 'China', 'address' => '21 Jianguomenwai Avenue, Beijing'],
            ['title' => 'Grand Hyatt Beijing', 'country' => 'China', 'address' => '1 East Chang An Avenue, Beijing'],
            ['title' => 'Rosewood Beijing', 'country' => 'China', 'address' => 'Jing Guang Centre, Hujialou, Beijing'],
            ['title' => 'NUO Hotel Beijing', 'country' => 'China', 'address' => '2A Jiangtai Road, Beijing'],
            ['title' => 'The Opposite House Beijing', 'country' => 'China', 'address' => '11 Sanlitun Road, Beijing'],

            // India (10 hotels)
            ['title' => 'The Oberoi New Delhi', 'country' => 'India', 'address' => 'Dr. Zakir Hussain Marg, New Delhi'],
            ['title' => 'The Leela Palace New Delhi', 'country' => 'India', 'address' => 'Diplomatic Enclave, New Delhi'],
            ['title' => 'Taj Mahal Palace Mumbai', 'country' => 'India', 'address' => 'Apollo Bunder, Mumbai'],
            ['title' => 'The Oberoi Mumbai', 'country' => 'India', 'address' => 'Nariman Point, Mumbai'],
            ['title' => 'Four Seasons Hotel Mumbai', 'country' => 'India', 'address' => '114 Dr. E Moses Road, Mumbai'],
            ['title' => 'The Leela Palace Udaipur', 'country' => 'India', 'address' => 'Lake Pichola, Udaipur'],
            ['title' => 'Taj Lake Palace Udaipur', 'country' => 'India', 'address' => 'Pichola Lake, Udaipur'],
            ['title' => 'Rambagh Palace Jaipur', 'country' => 'India', 'address' => 'Bhawani Singh Road, Jaipur'],
            ['title' => 'The Oberoi Rajvilas Jaipur', 'country' => 'India', 'address' => 'Goner Road, Jaipur'],
            ['title' => 'ITC Grand Chola Chennai', 'country' => 'India', 'address' => '63 Mount Road, Chennai'],

            // Brazil (10 hotels)
            ['title' => 'Copacabana Palace Rio de Janeiro', 'country' => 'Brazil', 'address' => 'Avenida Atlântica 1702, Rio de Janeiro'],
            ['title' => 'Fasano Hotel Rio de Janeiro', 'country' => 'Brazil', 'address' => 'Avenida Vieira Souto 80, Rio de Janeiro'],
            ['title' => 'Hotel Unique São Paulo', 'country' => 'Brazil', 'address' => 'Avenida Brigadeiro Luís Antônio 4700, São Paulo'],
            ['title' => 'Fasano São Paulo', 'country' => 'Brazil', 'address' => 'Rua Vittorio Fasano 88, São Paulo'],
            ['title' => 'Grand Hyatt São Paulo', 'country' => 'Brazil', 'address' => 'Avenida das Nações Unidas 13301, São Paulo'],
            ['title' => 'Hotel Emiliano São Paulo', 'country' => 'Brazil', 'address' => 'Rua Oscar Freire 384, São Paulo'],
            ['title' => 'Rosewood São Paulo', 'country' => 'Brazil', 'address' => 'Alameda Santos 2200, São Paulo'],
            ['title' => 'JW Marriott Hotel Rio de Janeiro', 'country' => 'Brazil', 'address' => 'Avenida Atlântica 2600, Rio de Janeiro'],
            ['title' => 'Fairmont Rio de Janeiro Copacabana', 'country' => 'Brazil', 'address' => 'Avenida Atlântica 4240, Rio de Janeiro'],
            ['title' => 'Hotel Fasano Boa Vista', 'country' => 'Brazil', 'address' => 'Fazenda Boa Vista, Porto Feliz'],

            // Canada (10 hotels)
            ['title' => 'Fairmont Chateau Lake Louise', 'country' => 'Canada', 'address' => '111 Lake Louise Drive, Lake Louise, AB'],
            ['title' => 'Four Seasons Hotel Toronto', 'country' => 'Canada', 'address' => '60 Yorkville Avenue, Toronto, ON'],
            ['title' => 'The Ritz-Carlton Toronto', 'country' => 'Canada', 'address' => '181 Wellington Street West, Toronto, ON'],
            ['title' => 'Fairmont Royal York Toronto', 'country' => 'Canada', 'address' => '100 Front Street West, Toronto, ON'],
            ['title' => 'Rosewood Hotel Georgia Vancouver', 'country' => 'Canada', 'address' => '801 West Georgia Street, Vancouver, BC'],
            ['title' => 'Four Seasons Hotel Vancouver', 'country' => 'Canada', 'address' => '791 West Georgia Street, Vancouver, BC'],
            ['title' => 'Fairmont Hotel Vancouver', 'country' => 'Canada', 'address' => '900 West Georgia Street, Vancouver, BC'],
            ['title' => 'Château Frontenac Quebec City', 'country' => 'Canada', 'address' => '1 Rue des Carrières, Quebec City, QC'],
            ['title' => 'Four Seasons Hotel Montreal', 'country' => 'Canada', 'address' => '1440 Rue de la Montagne, Montreal, QC'],
            ['title' => 'The Ritz-Carlton Montreal', 'country' => 'Canada', 'address' => '1228 Sherbrooke Street West, Montreal, QC'],

            // Australia (10 hotels)
            ['title' => 'Park Hyatt Sydney', 'country' => 'Australia', 'address' => '7 Hickson Road, Sydney, NSW'],
            ['title' => 'Four Seasons Hotel Sydney', 'country' => 'Australia', 'address' => '199 George Street, Sydney, NSW'],
            ['title' => 'The Langham Sydney', 'country' => 'Australia', 'address' => '89-113 Kent Street, Sydney, NSW'],
            ['title' => 'Shangri-La Hotel Sydney', 'country' => 'Australia', 'address' => '176 Cumberland Street, Sydney, NSW'],
            ['title' => 'Crown Towers Melbourne', 'country' => 'Australia', 'address' => '8 Whiteman Street, Melbourne, VIC'],
            ['title' => 'Park Hyatt Melbourne', 'country' => 'Australia', 'address' => '1 Parliament Square, Melbourne, VIC'],
            ['title' => 'The Langham Melbourne', 'country' => 'Australia', 'address' => '1 Southgate Avenue, Melbourne, VIC'],
            ['title' => 'COMO The Treasury Perth', 'country' => 'Australia', 'address' => '1 Cathedral Avenue, Perth, WA'],
            ['title' => 'Emporium Hotel South Bank Brisbane', 'country' => 'Australia', 'address' => '267 Grey Street, Brisbane, QLD'],
            ['title' => 'The Calile Hotel Brisbane', 'country' => 'Australia', 'address' => '39 James Street, Brisbane, QLD'],

            // Mexico (10 hotels)
            ['title' => 'Four Seasons Hotel Mexico City', 'country' => 'Mexico', 'address' => 'Paseo de la Reforma 500, Mexico City'],
            ['title' => 'The St. Regis Mexico City', 'country' => 'Mexico', 'address' => 'Paseo de la Reforma 439, Mexico City'],
            ['title' => 'JW Marriott Hotel Mexico City', 'country' => 'Mexico', 'address' => 'Andrés Bello 29, Mexico City'],
            ['title' => 'Grand Fiesta Americana Chapultepec', 'country' => 'Mexico', 'address' => 'Mariano Escobedo 756, Mexico City'],
            ['title' => 'Hotel Presidente InterContinental', 'country' => 'Mexico', 'address' => 'Campos Elíseos 218, Mexico City'],
            ['title' => 'Rosewood San Miguel de Allende', 'country' => 'Mexico', 'address' => 'Nemesio Diez 11, San Miguel de Allende'],
            ['title' => 'Belmond Casa de Sierra Nevada', 'country' => 'Mexico', 'address' => 'Hospicio 35, San Miguel de Allende'],
            ['title' => 'Grand Velas Riviera Maya', 'country' => 'Mexico', 'address' => 'Carretera Cancún-Tulum Km 62, Playa del Carmen'],
            ['title' => 'Rosewood Mayakoba', 'country' => 'Mexico', 'address' => 'Carretera Federal Cancun-Playa del Carmen Km 298'],
            ['title' => 'Banyan Tree Mayakoba', 'country' => 'Mexico', 'address' => 'Carretera Federal Cancun-Playa del Carmen Km 298'],

            // Turkey (10 hotels)
            ['title' => 'Four Seasons Hotel Istanbul at Sultanahmet', 'country' => 'Turkey', 'address' => 'Tevkifhane Sokak No 1, Istanbul'],
            ['title' => 'Çırağan Palace Kempinski Istanbul', 'country' => 'Turkey', 'address' => 'Çırağan Caddesi 32, Istanbul'],
            ['title' => 'The Ritz-Carlton Istanbul', 'country' => 'Turkey', 'address' => 'Suzer Plaza, Askerocagi Caddesi No 6, Istanbul'],
            ['title' => 'Shangri-La Bosphorus Istanbul', 'country' => 'Turkey', 'address' => 'Sinanpaşa Mahallesi, Hayrettin İskelesi Sokak 1, Istanbul'],
            ['title' => 'Park Hyatt Istanbul Macka Palas', 'country' => 'Turkey', 'address' => 'Bronz Sokak No 4, Istanbul'],
            ['title' => 'Six Senses Kaplankaya', 'country' => 'Turkey', 'address' => 'Kaplankaya, Bodrum'],
            ['title' => 'Mandarin Oriental Bodrum', 'country' => 'Turkey', 'address' => 'Cennet Koyu, Bodrum'],
            ['title' => 'The Edition Bodrum', 'country' => 'Turkey', 'address' => 'Yalikavak Marina, Bodrum'],
            ['title' => 'Swissotel The Bosphorus Istanbul', 'country' => 'Turkey', 'address' => 'Visnezade Mahallesi, Acisu Sokak No 19, Istanbul'],
            ['title' => 'Conrad Istanbul Bosphorus', 'country' => 'Turkey', 'address' => 'Cihannüma Mahallesi, Saray Caddesi No 5, Istanbul'],

            // Thailand (10 hotels)
            ['title' => 'The Oriental Bangkok', 'country' => 'Thailand', 'address' => '48 Oriental Avenue, Bangkok'],
            ['title' => 'Four Seasons Hotel Bangkok', 'country' => 'Thailand', 'address' => '155 Rajadamri Road, Bangkok'],
            ['title' => 'The St. Regis Bangkok', 'country' => 'Thailand', 'address' => '159 Rajadamri Road, Bangkok'],
            ['title' => 'Park Hyatt Bangkok', 'country' => 'Thailand', 'address' => '88 Wireless Road, Bangkok'],
            ['title' => 'Shangri-La Hotel Bangkok', 'country' => 'Thailand', 'address' => '89 Soi Wat Suan Plu, Bangkok'],
            ['title' => 'Rosewood Bangkok', 'country' => 'Thailand', 'address' => '1041/38 Ploenchit Road, Bangkok'],
            ['title' => 'Four Seasons Resort Chiang Mai', 'country' => 'Thailand', 'address' => '502 Moo 1, Mae Rim-Samoeng Old Road, Chiang Mai'],
            ['title' => 'Anantara Golden Triangle', 'country' => 'Thailand', 'address' => '229 Moo 1, Chiang Saen, Chiang Rai'],
            ['title' => 'Six Senses Yao Noi', 'country' => 'Thailand', 'address' => '56 Moo 5, Koh Yao Noi, Phang Nga'],
            ['title' => 'Amanpuri Phuket', 'country' => 'Thailand', 'address' => 'Pansea Beach, Phuket'],

            // South Korea (10 hotels)
            ['title' => 'Park Hyatt Seoul', 'country' => 'South Korea', 'address' => '606 Teheran-ro, Gangnam-gu, Seoul'],
            ['title' => 'Four Seasons Hotel Seoul', 'country' => 'South Korea', 'address' => '97 Saemunan-ro, Jongno-gu, Seoul'],
            ['title' => 'The Shilla Seoul', 'country' => 'South Korea', 'address' => '249 Dongho-ro, Jung-gu, Seoul'],
            ['title' => 'Grand Hyatt Seoul', 'country' => 'South Korea', 'address' => '322 Sowol-ro, Yongsan-gu, Seoul'],
            ['title' => 'JW Marriott Hotel Seoul', 'country' => 'South Korea', 'address' => '176 Sinbanpo-ro, Seocho-gu, Seoul'],
            ['title' => 'Lotte Hotel Seoul', 'country' => 'South Korea', 'address' => '30 Eulji-ro, Jung-gu, Seoul'],
            ['title' => 'Conrad Seoul', 'country' => 'South Korea', 'address' => '10 Gukjegeumyung-ro, Yeongdeungpo-gu, Seoul'],
            ['title' => 'Banyan Tree Club & Spa Seoul', 'country' => 'South Korea', 'address' => '60 Jangchungdan-ro, Jung-gu, Seoul'],
            ['title' => 'Signiel Seoul', 'country' => 'South Korea', 'address' => '300 Olympic-ro, Songpa-gu, Seoul'],
            ['title' => 'The Westin Chosun Seoul', 'country' => 'South Korea', 'address' => '106 Sogong-ro, Jung-gu, Seoul'],

            // UAE (10 hotels)
            ['title' => 'Burj Al Arab Jumeirah', 'country' => 'UAE', 'address' => 'Jumeirah Beach Road, Dubai'],
            ['title' => 'Four Seasons Resort Dubai at Jumeirah Beach', 'country' => 'UAE', 'address' => 'Jumeirah Beach Road, Dubai'],
            ['title' => 'The Ritz-Carlton Dubai', 'country' => 'UAE', 'address' => 'Dubai International Financial Centre, Dubai'],
            ['title' => 'Park Hyatt Dubai', 'country' => 'UAE', 'address' => 'Dubai Creek Golf & Yacht Club, Dubai'],
            ['title' => 'Atlantis The Palm', 'country' => 'UAE', 'address' => 'Crescent Road, The Palm Jumeirah, Dubai'],
            ['title' => 'Emirates Palace Abu Dhabi', 'country' => 'UAE', 'address' => 'West Corniche Road, Abu Dhabi'],
            ['title' => 'The St. Regis Abu Dhabi', 'country' => 'UAE', 'address' => 'Nation Towers, Corniche, Abu Dhabi'],
            ['title' => 'Four Seasons Hotel Abu Dhabi', 'country' => 'UAE', 'address' => 'Al Maryah Island, Abu Dhabi'],
            ['title' => 'Rosewood Abu Dhabi', 'country' => 'UAE', 'address' => 'Al Maryah Island, Abu Dhabi'],
            ['title' => 'Shangri-La Hotel Qaryat Al Beri', 'country' => 'UAE', 'address' => 'Qaryat Al Beri, Abu Dhabi'],

            // Singapore (10 hotels)
            ['title' => 'Marina Bay Sands', 'country' => 'Singapore', 'address' => '10 Bayfront Avenue, Singapore'],
            ['title' => 'The Ritz-Carlton Millenia Singapore', 'country' => 'Singapore', 'address' => '7 Raffles Avenue, Singapore'],
            ['title' => 'Four Seasons Hotel Singapore', 'country' => 'Singapore', 'address' => '190 Orchard Boulevard, Singapore'],
            ['title' => 'Shangri-La Hotel Singapore', 'country' => 'Singapore', 'address' => '22 Orange Grove Road, Singapore'],
            ['title' => 'The St. Regis Singapore', 'country' => 'Singapore', 'address' => '29 Tanglin Road, Singapore'],
            ['title' => 'Capella Singapore', 'country' => 'Singapore', 'address' => '1 The Knolls, Sentosa Island, Singapore'],
            ['title' => 'Raffles Hotel Singapore', 'country' => 'Singapore', 'address' => '1 Beach Road, Singapore'],
            ['title' => 'Park Hyatt Singapore', 'country' => 'Singapore', 'address' => '10 Scotts Road, Singapore'],
            ['title' => 'Conrad Centennial Singapore', 'country' => 'Singapore', 'address' => '2 Temasek Boulevard, Singapore'],
            ['title' => 'Mandarin Oriental Singapore', 'country' => 'Singapore', 'address' => '5 Raffles Avenue, Singapore'],
        ];

        $descriptions = [
            // Russia
            'The Ritz-Carlton Moscow' => 'Роскошный отель в самом сердце Москвы с видом на Кремль.',
            'Four Seasons Hotel Moscow' => 'Элегантный отель рядом с Красной площадью и Большим театром.',
            'Hotel Metropol Moscow' => 'Исторический отель в стиле модерн с уникальной архитектурой.',
            'Ararat Park Hyatt Moscow' => 'Современный отель с панорамными видами на центр Москвы.',
            'The St. Regis Moscow' => 'Изысканный отель с персональным дворецким и роскошными номерами.',
            'Lotte Hotel Moscow' => 'Современный небоскреб с видом на Москву-реку и центр города.',
            'Radisson Collection Hotel Moscow' => 'Стильный отель на Кутузовском проспекте с современным дизайном.',
            'Swissotel Krasnye Holmy Moscow' => 'Высотный отель с панорамными видами и швейцарским сервисом.',
            'Hotel National Moscow' => 'Легендарный отель с богатой историей и классическим интерьером.',
            'Baltschug Kempinski Moscow' => 'Роскошный отель на берегу Москвы-реки напротив Кремля.',

            // Norway
            'Hotel Continental Oslo' => 'Исторический отель в центре Осло с традиционным норвежским гостеприимством.',
            'The Thief Oslo' => 'Современный дизайн-отель на полуострове Тьювхольмен с видом на фьорд.',
            'Grand Hotel Oslo' => 'Легендарный отель на главной улице Осло с богатой историей.',
            'Radisson Blu Plaza Hotel Oslo' => 'Высотный отель с панорамными видами на город и фьорд.',
            'Clarion Hotel The Hub' => 'Современный эко-отель рядом с центральным вокзалом.',
            'Scandic Holmenkollen Park' => 'Отель в живописном районе с видом на лыжный трамплин.',
            'Hotel Bristol Oslo' => 'Элегантный бутик-отель в самом центре норвежской столицы.',
            'Amerikalinjen Oslo' => 'Стильный отель в здании бывшего офиса пароходной компании.',
            'Scandic Vulkan' => 'Современный отель в модном районе Грюнерлёкка.',
            'Villa Frogner' => 'Уютный бутик-отель в престижном районе Фрогнер.',

            // USA
            'The Plaza New York' => 'Легендарный отель на Пятой авеню с видом на Центральный парк.',
            'The St. Regis New York' => 'Роскошный отель в Мидтауне с персональным дворецким.',
            'The Carlyle New York' => 'Элегантный отель на Верхнем Ист-Сайде с джазовым кафе.',
            'The Pierre New York' => 'Классический отель напротив Центрального парка.',
            'The Ritz-Carlton New York' => 'Роскошный отель с видом на Центральный парк и спа-центром.',
            'Four Seasons Hotel New York' => 'Современный отель с панорамными видами на город.',
            'The Mark New York' => 'Стильный бутик-отель с уникальным дизайном Жака Гранжа.',
            'The Lowell New York' => 'Уютный отель с домашней атмосферой на Верхнем Ист-Сайде.',
            'The Sherry-Netherland' => 'Исторический отель с апартаментами и видом на Центральный парк.',
            'The Greenwich Hotel' => 'Бутик-отель в Трайбеке с уникальным дизайном и спа.',

            // France
            'The Ritz Paris' => 'Легендарный отель на площади Вандом с богатой историей.',
            'Four Seasons Hotel George V' => 'Роскошный отель рядом с Елисейскими полями.',
            'Le Bristol Paris' => 'Элегантный отель с садом и мишленовскими ресторанами.',
            'Hotel Plaza Athénée' => 'Икона парижского стиля на авеню Монтень.',
            'Le Meurice' => 'Дворцовый отель напротив сада Тюильри.',
            'Shangri-La Hotel Paris' => 'Бывший дворец принца с видом на Эйфелеву башню.',
            'Park Hyatt Paris-Vendôme' => 'Современный отель в историческом здании на площади Вандом.',
            'Hotel de Crillon' => 'Исторический дворец XVIII века на площади Согласия.',
            'La Réserve Paris' => 'Уютный отель рядом с Елисейским дворцом.',
            'Hotel Lutetia' => 'Легендарный отель в стиле ар-деко в Сен-Жермен-де-Пре.',

            // Germany
            'Hotel Adlon Kempinski Berlin' => 'Легендарный отель у Бранденбургских ворот.',
            'The Ritz-Carlton Berlin' => 'Роскошный отель на Потсдамской площади.',
            'Regent Berlin' => 'Элегантный отель в историческом центре Берлина.',
            'Hotel de Rome Berlin' => 'Бутик-отель в здании бывшего банка.',
            'Das Stue Berlin' => 'Дизайнерский отель рядом с зоопарком.',
            'Soho House Berlin' => 'Эксклюзивный клуб-отель в районе Митте.',
            'Grand Hyatt Berlin' => 'Современный отель с азиатским спа-центром.',
            'Hotel Brandenburger Hof' => 'Бутик-отель в тихом районе Шарлоттенбург.',
            'Orania Berlin' => 'Современный отель в креативном районе Кройцберг.',
            'Titanic Gendarmenmarkt Berlin' => 'Стильный отель на красивейшей площади Берлина.',

            // Italy
            'Hotel de Russie Rome' => 'Элегантный отель с террасным садом в центре Рима.',
            'The St. Regis Rome' => 'Роскошный отель рядом с фонтаном Треви.',
            'Hotel Splendido Portofino' => 'Легендарный отель на Итальянской Ривьере.',
            'Gritti Palace Venice' => 'Дворцовый отель на Гранд-канале в Венеции.',
            'Villa San Martino Martina Franca' => 'Роскошная вилла в сердце Апулии.',
            'Four Seasons Hotel Milano' => 'Элегантный отель в модном квартале Милана.',
            'Hotel Villa Cimbrone Ravello' => 'Романтический отель с садами на Амальфитанском побережье.',
            'Belmond Hotel Caruso' => 'Отель в бывшем дворце XI века с видом на море.',
            'Palazzo Margherita Basilicata' => 'Частная резиденция Фрэнсиса Форда Копполы.',
            'Hotel Hassler Roma' => 'Семейный отель на вершине Испанской лестницы.',

            // Spain
            'Hotel Ritz Madrid' => 'Легендарный отель в Золотом треугольнике искусств.',
            'Four Seasons Hotel Madrid' => 'Современный отель в историческом здании.',
            'Hotel Arts Barcelona' => 'Небоскреб у моря с современным искусством.',
            'Hotel Casa Fuster Barcelona' => 'Модернистский отель на Пасео де Грасиа.',
            'Hotel Alfonso XIII Seville' => 'Дворцовый отель в мавританском стиле.',
            'Parador de Granada' => 'Отель в монастыре XV века в Альгамбре.',
            'Hotel Villa Magna Madrid' => 'Элегантный отель в районе Саламанка.',
            'Gran Hotel La Florida Barcelona' => 'Отель на холме Тибидабо с видом на город.',
            'Hotel Maria Cristina San Sebastian' => 'Классический отель в стиле Belle Époque.',
            'Hospes Palacio del Bailio Cordoba' => 'Отель в римско-арабском дворце.',

            // United Kingdom
            'The Savoy London' => 'Легендарный отель на Стрэнде с богатой историей.',
            'Claridges London' => 'Икона британской элегантности в Мейфэре.',
            'The Ritz London' => 'Классический отель с традиционным послеполуденным чаем.',
            'The Langham London' => 'Первый гранд-отель Европы с викторианской роскошью.',
            'Shangri-La Hotel at The Shard' => 'Отель в небоскребе с панорамными видами на Лондон.',
            'The Connaught London' => 'Уютный отель с мишленовскими ресторанами.',
            'Corinthia Hotel London' => 'Роскошный отель с одним из лучших спа в мире.',
            'The Dorchester London' => 'Легендарный отель на Парк-Лейн.',
            'Rosewood London' => 'Элегантный отель в эдвардианском здании.',
            'The Ned London' => 'Отель в здании бывшего банка с множеством ресторанов.',

            // Japan
            'The Ritz-Carlton Tokyo' => 'Роскошный отель на верхних этажах небоскреба.',
            'Aman Tokyo' => 'Минималистский отель с японской эстетикой.',
            'Park Hyatt Tokyo' => 'Отель из фильма "Трудности перевода" с видом на Фудзи.',
            'Four Seasons Hotel Tokyo at Marunouchi' => 'Элегантный отель рядом с Императорским дворцом.',
            'The Peninsula Tokyo' => 'Роскошный отель в районе Гинза.',
            'Hoshinoya Tokyo' => 'Традиционный рёкан в современном исполнении.',
            'Imperial Hotel Tokyo' => 'Исторический отель с более чем вековой историей.',
            'Shangri-La Hotel Tokyo' => 'Отель с панорамными видами на Токийский залив.',
            'The Tokyo Station Hotel' => 'Отель в здании исторического вокзала.',
            'Mandarin Oriental Tokyo' => 'Современный отель с видом на реку Сумида.',

            // China
            'The Peninsula Beijing' => 'Легендарный отель рядом с Запретным городом.',
            'Four Seasons Hotel Beijing' => 'Современный отель в дипломатическом квартале.',
            'The Ritz-Carlton Beijing' => 'Роскошный отель в деловом центре города.',
            'Park Hyatt Beijing' => 'Элегантный отель с современным китайским дизайном.',
            'Shangri-La Hotel Beijing' => 'Отель с традиционными садами в центре города.',
            'The St. Regis Beijing' => 'Роскошный отель с персональным дворецким.',
            'Grand Hyatt Beijing' => 'Отель в торговом комплексе Oriental Plaza.',
            'Rosewood Beijing' => 'Современный отель с панорамными видами на город.',
            'NUO Hotel Beijing' => 'Отель с современным китайским дизайном.',
            'The Opposite House Beijing' => 'Дизайнерский отель в районе Санлитунь.',

            // India
            'The Oberoi New Delhi' => 'Элегантный отель с видом на гольф-клуб.',
            'The Leela Palace New Delhi' => 'Роскошный отель в дипломатическом анклаве.',
            'Taj Mahal Palace Mumbai' => 'Легендарный отель у Ворот Индии.',
            'The Oberoi Mumbai' => 'Отель с видом на Аравийское море.',
            'Four Seasons Hotel Mumbai' => 'Современный отель в деловом центре.',
            'The Leela Palace Udaipur' => 'Дворцовый отель на берегу озера Пичола.',
            'Taj Lake Palace Udaipur' => 'Плавучий дворец XVIII века на озере.',
            'Rambagh Palace Jaipur' => 'Бывший дворец махараджи, превращенный в отель.',
            'The Oberoi Rajvilas Jaipur' => 'Отель-форт с традиционной раджастханской архитектурой.',
            'ITC Grand Chola Chennai' => 'Роскошный отель в стиле династии Чола.',

            // Brazil
            'Copacabana Palace Rio de Janeiro' => 'Легендарный отель на пляже Копакабана.',
            'Fasano Hotel Rio de Janeiro' => 'Элегантный отель на пляже Ипанема.',
            'Hotel Unique São Paulo' => 'Дизайнерский отель в форме арбуза.',
            'Fasano São Paulo' => 'Стильный отель в районе Жардинс.',
            'Grand Hyatt São Paulo' => 'Современный отель в деловом центре.',
            'Hotel Emiliano São Paulo' => 'Бутик-отель на улице Оскара Фрейре.',
            'Rosewood São Paulo' => 'Роскошный отель в районе Итаим Биби.',
            'JW Marriott Hotel Rio de Janeiro' => 'Современный отель на пляже Копакабана.',
            'Fairmont Rio de Janeiro Copacabana' => 'Элегантный отель с видом на океан.',
            'Hotel Fasano Boa Vista' => 'Эксклюзивный загородный отель.',

            // Canada
            'Fairmont Chateau Lake Louise' => 'Замок в Канадских Скалистых горах.',
            'Four Seasons Hotel Toronto' => 'Роскошный отель в центре Торонто.',
            'The Ritz-Carlton Toronto' => 'Элегантный отель в финансовом районе.',
            'Fairmont Royal York Toronto' => 'Исторический отель рядом с вокзалом.',
            'Rosewood Hotel Georgia Vancouver' => 'Отель в стиле ар-деко в центре Ванкувера.',
            'Four Seasons Hotel Vancouver' => 'Современный отель с видом на горы.',
            'Fairmont Hotel Vancouver' => 'Замок в центре города с богатой историей.',
            'Château Frontenac Quebec City' => 'Легендарный замок-отель над рекой Святого Лаврентия.',
            'Four Seasons Hotel Montreal' => 'Элегантный отель в Золотой квадратной миле.',
            'The Ritz-Carlton Montreal' => 'Роскошный отель в самом сердце города.',

            // Australia
            'Park Hyatt Sydney' => 'Отель с лучшим видом на Сиднейскую гавань.',
            'Four Seasons Hotel Sydney' => 'Роскошный отель в центре делового района.',
            'The Langham Sydney' => 'Элегантный отель в историческом здании обсерватории.',
            'Shangri-La Hotel Sydney' => 'Отель с панорамными видами на гавань.',
            'Crown Towers Melbourne' => 'Роскошный отель в развлекательном комплексе.',
            'Park Hyatt Melbourne' => 'Современный отель на берегу реки Ярра.',
            'The Langham Melbourne' => 'Элегантный отель в районе Саутбэнк.',
            'COMO The Treasury Perth' => 'Бутик-отель в здании бывшего казначейства.',
            'Emporium Hotel South Bank Brisbane' => 'Современный отель с видом на реку.',
            'The Calile Hotel Brisbane' => 'Стильный отель в районе Джеймс-стрит.',

            // Mexico
            'Four Seasons Hotel Mexico City' => 'Элегантный отель на Пасео де ла Реформа.',
            'The St. Regis Mexico City' => 'Роскошный отель в районе Полanco.',
            'JW Marriott Hotel Mexico City' => 'Современный отель в деловом центре.',
            'Grand Fiesta Americana Chapultepec' => 'Отель рядом с парком Чапультепек.',
            'Hotel Presidente InterContinental' => 'Классический отель в районе Полanco.',
            'Rosewood San Miguel de Allende' => 'Бутик-отель в колониальном городе.',
            'Belmond Casa de Sierra Nevada' => 'Отель в особняке XVI века.',
            'Grand Velas Riviera Maya' => 'Роскошный курорт "все включено" на Ривьере Майя.',
            'Rosewood Mayakoba' => 'Эко-курорт в лагуне среди мангровых зарослей.',
            'Banyan Tree Mayakoba' => 'Виллы над водой в природном заповеднике.',

            // Turkey
            'Four Seasons Hotel Istanbul at Sultanahmet' => 'Отель в бывшей тюрьме рядом с Айя-Софией.',
            'Çırağan Palace Kempinski Istanbul' => 'Дворец XIX века на берегу Босфора.',
            'The Ritz-Carlton Istanbul' => 'Роскошный отель с видом на Босфор.',
            'Shangri-La Bosphorus Istanbul' => 'Современный отель на европейской стороне Босфора.',
            'Park Hyatt Istanbul Macka Palas' => 'Элегантный отель в районе Нишанташи.',
            'Six Senses Kaplankaya' => 'Эко-курорт на Эгейском побережье.',
            'Mandarin Oriental Bodrum' => 'Роскошный курорт в бухте Рай.',
            'The Edition Bodrum' => 'Современный отель в марине Ялыкавак.',
            'Swissotel The Bosphorus Istanbul' => 'Отель с панорамными видами на Босфор.',
            'Conrad Istanbul Bosphorus' => 'Роскошный отель на азиатской стороне города.',

            // Thailand
            'The Oriental Bangkok' => 'Легендарный отель на реке Чао Прайя.',
            'Four Seasons Hotel Bangkok' => 'Элегантный отель в центре города.',
            'The St. Regis Bangkok' => 'Роскошный отель на улице Раджадамри.',
            'Park Hyatt Bangkok' => 'Современный отель в деловом районе.',
            'Shangri-La Hotel Bangkok' => 'Отель с тропическими садами у реки.',
            'Rosewood Bangkok' => 'Вертикальный курорт в центре города.',
            'Four Seasons Resort Chiang Mai' => 'Курорт в рисовых террасах северного Таиланда.',
            'Anantara Golden Triangle' => 'Роскошный курорт на границе трех стран.',
            'Six Senses Yao Noi' => 'Эко-курорт на острове в заливе Пханг Нга.',
            'Amanpuri Phuket' => 'Легендарный курорт на пляже Пансеа.',

            // South Korea
            'Park Hyatt Seoul' => 'Роскошный отель в районе Каннам.',
            'Four Seasons Hotel Seoul' => 'Элегантный отель рядом с дворцом Кёнбоккун.',
            'The Shilla Seoul' => 'Отель с традиционными корейскими садами.',
            'Grand Hyatt Seoul' => 'Отель на холме Намсан с видом на город.',
            'JW Marriott Hotel Seoul' => 'Современный отель в районе Сочо.',
            'Lotte Hotel Seoul' => 'Отель в центре города с торговым центром.',
            'Conrad Seoul' => 'Роскошный отель на острове Ёыйдо.',
            'Banyan Tree Club & Spa Seoul' => 'Городской курорт с спа-центром.',
            'Signiel Seoul' => 'Отель на верхних этажах Lotte World Tower.',
            'The Westin Chosun Seoul' => 'Исторический отель в центре города.',

            // UAE
            'Burj Al Arab Jumeirah' => 'Легендарный парусообразный отель на искусственном острове.',
            'Four Seasons Resort Dubai at Jumeirah Beach' => 'Курорт на частном пляже Джумейра.',
            'The Ritz-Carlton Dubai' => 'Роскошный отель в финансовом центре.',
            'Park Hyatt Dubai' => 'Элегантный отель у крика Дубай.',
            'Atlantis The Palm' => 'Тематический курорт на искусственном острове.',
            'Emirates Palace Abu Dhabi' => 'Дворец с частным пляжем и золотыми куполами.',
            'The St. Regis Abu Dhabi' => 'Роскошный отель в башнях Nation Towers.',
            'Four Seasons Hotel Abu Dhabi' => 'Современный отель на острове Аль-Марьях.',
            'Rosewood Abu Dhabi' => 'Элегантный отель с видом на Персидский залив.',
            'Shangri-La Hotel Qaryat Al Beri' => 'Отель в стиле арабского форта.',

            // Singapore
            'Marina Bay Sands' => 'Икона Сингапура с бассейном на крыше.',
            'The Ritz-Carlton Millenia Singapore' => 'Роскошный отель с коллекцией современного искусства.',
            'Four Seasons Hotel Singapore' => 'Элегантный отель на Орчард Роуд.',
            'Shangri-La Hotel Singapore' => 'Отель-сад в центре города.',
            'The St. Regis Singapore' => 'Роскошный отель в районе посольств.',
            'Capella Singapore' => 'Курорт на острове Сентоза в колониальном стиле.',
            'Raffles Hotel Singapore' => 'Легендарный отель, родина коктейля Сингапур Слинг.',
            'Park Hyatt Singapore' => 'Современный отель в торговом районе.',
            'Conrad Centennial Singapore' => 'Отель в центре делового района.',
            'Mandarin Oriental Singapore' => 'Элегантный отель в Marina Bay.',
        ];

        foreach ($hotels as $hotel) {
            $description = $descriptions[$hotel['title']] ?? 'Роскошный отель с исключительным сервисом и удобствами.';

            $hotelRecord = Hotel::updateOrCreate(
                ['title' => $hotel['title'], 'country' => $hotel['country']],
                [
                    'description' => $description,
                    'poster_url' => null,
                    'address' => $hotel['address'],
                    'rating' => rand(40, 50) / 10,
                ]
            );

            $roomTypes = [
                ['Стандарт', 3000, 5000, [18, 25]],
                ['Комфорт', 5000, 8000, [25, 35]],
                ['Полулюкс', 8000, 12000, [35, 45]],
                ['Люкс', 12000, 20000, [45, 60]],
                ['Президентский люкс', 20000, 35000, [60, 80]],
            ];

            foreach ($roomTypes as $index => $roomType) {
                $price = rand($roomType[1], $roomType[2]);
                $floorArea = rand($roomType[3][0], $roomType[3][1]);

                $room = Room::updateOrCreate(
                    [
                        'hotel_id' => $hotelRecord->id,
                        'type' => $roomType[0]
                    ],
                    [
                        'title' => "Номер {$roomType[0]}",
                        'description' => "Комфортабельный номер типа {$roomType[0]} с всеми удобствами.",
                        'price' => $price,
                        'floor_area' => $floorArea,
                        'poster_url' => null,
                    ]
                );

                $this->assignFacilitiesToRoom($room);
            }
        }
    }

    private function generateSlug($title)
    {
        return strtolower(str_replace([' ', '-', '.', ','], '_', $title));
    }

    private function assignFacilitiesToRoom($room)
    {
        $basicFacilityNames = ['Кондиционер', 'Чайник/кофеварка', 'Рабочий стол', 'Халат'];
        $luxuryFacilityNames = ['Кондиционер', 'Чайник/кофеварка', 'Рабочий стол', 'Халат', 'Мини-бар', 'Сейф', 'Балкон'];

        $basicFacilities = DB::table('facilities')->whereIn('title', $basicFacilityNames)->pluck('id')->toArray();
        $luxuryFacilities = DB::table('facilities')->whereIn('title', $luxuryFacilityNames)->pluck('id')->toArray();

        $facilitySet = match (true) {
            $room->price >= 12000 => $luxuryFacilities, 
            default => $basicFacilities 
        };

        if (!empty($facilitySet)) {
            $room->facilities()->sync($facilitySet);
        }
    }
}