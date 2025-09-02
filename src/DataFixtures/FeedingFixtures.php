<?php

namespace App\DataFixtures;

use App\Entity\{Brand, Color, Energy, Model};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FeedingFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['feed'];
    }

    public function load(ObjectManager $manager): void
    {
        // ----- Ajout des couleurs ----------
        $colors = [
            "Blanc",
            "Bleu",
            "Gris",
            "Jaune",
            "Marron",
            "Noir",
            "Orange",
            "Rose",
            "Rouge",
            "Vert",
            "Violet",
            "Autre",
        ];
        foreach ($colors as $colorName) {
            $color = new Color();
            $color->setColor($colorName);
            $manager->persist($color);
        }

        // ----- Ajout des énergies ----------
        $energies = [
            "Diesel",
            "Electrique",
            "Essence",
            "GPL",
            "Hybride",
            "Hydrogène",
            "A pédale !",
            "Autre",
        ];
        foreach ($energies as $energieName) {
            $energy = new Energy();
            $energy->setEnergy($energieName);
            $manager->persist($energy);
        }

        // ----- Ajout des marques et modèles ----------
        // exemple // $brandsData = [
        // exemple //     'Peugeot' => ['108', '208', '308'],
        // exemple //     'Renault' => ['Twingo', 'Clio', 'Mégane'],
        // exemple //     'Citroën' => ['C1', 'C2', 'C3']
        // exemple // ];

        $brandsData = [
            'Autre marque' => [ 'Autre modèle' ],
            'Alfa Romeo' => [ 'Autre modèle', '145', '146', '147', '155', '156', '159', '166', '164', '4C', '8C Spider', 'Brera', 'ES 30 / SZ', 'Giulia', 'Giulietta', 'GT', 'GTV', 'Junior', 'Mito', 'RZ', 'Spider', 'Stelvio', 'Tonale' ],
            'Audi' => [ 'Autre modèle', '100', '200', '80', '90', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Allroad', 'Coupe Quattro', 'E-Tron', 'Q2', 'Q3', 'Q4', 'Q5', 'Q7', 'Quattro', 'S3', 'S4', 'S5', 'S6', 'S8', 'TT', 'V8' ],
            'BMW' => [ 'Autre modèle', '1800 Ti', '325', '330', '525', '530', '545', '550', '645', '650', '745', '750', '760', 'Alpina B7', 'E1', 'i3', 'M Roadster', 'M3', 'M5', 'M6', 'Megacity', 'serie 1', 'serie 2', 'serie 3', 'serie 4', 'serie 5', 'serie 6', 'serie 7', 'serie 8', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'X8', 'Z1', 'Z3', 'Z4', 'Z8' ],
            'Chevrolet' => [ 'Autre modèle', '1500', '2500', '3500', 'Alero', 'APV', 'Astro', 'Avalanche', 'Aveo', 'Beretta', 'Blazer', 'Camaro', 'Caprice', 'Captiva', 'Cavalier', 'Classic', 'Cobalt', 'Colorado', 'Corsica', 'Corvette', 'Cruze', 'Epica', 'Equinox', 'Express', 'G-Series 1500', 'G-Series 2500', 'G-Series 3500', 'G-Series G10', 'G-Series G20', 'G-Series G30', 'HHR', 'Impala', 'iX', 'iX2', 'K5 Blazer', 'Lacetti', 'Lumina', 'Malibu', 'Matiz', 'Menlo', 'Metro', 'Monte Carlo', 'Nubira SW', 'Orlando', 'Prizm', 'S10', 'Silverado', 'Sonic', 'Spark', 'Sportvan G10', 'Sportvan G20', 'Sportvan G30', 'SSR', 'Suburban', 'Tahoe', 'Tracker', 'TrailBlazer', 'Trans Sport', 'Traverse', 'Trax', 'Uplander', 'Venture', 'Volt' ],
            'Chrysler' => [ 'Autre modèle', '200C EV', '300', 'Aspen', 'Cirrus', 'Concorde', 'Crossfire', 'EPIC', 'Fifth Ave', 'Grand Voyager', 'Imperial', 'LeBaron', 'LHS', 'Neon', 'New Yorker', 'Pacifica', 'Prowler', 'PT Cruiser', 'Saratoga', 'Sebring', 'Stratus', 'Town & Country', 'Vision', 'Voyager' ],
            'Citroën' => [ 'Autre modèle', 'AX', 'Berlingo', 'BX', 'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C8', 'Citerla', 'DS2', 'DS3', 'DS5', 'Evasion', 'Grand Picasso', 'Jumper', 'Nemo Combi', 'Saxo', 'Survolt', 'Xantia', 'XM', 'Xsara', 'ZX' ],
            'Cupra' => [ 'Autre modèle', 'Ateca', 'Born', 'Formentor', 'Raval', 'Tavascan', 'Terramar' ],
            'Dacia' => [ 'Autre modèle', 'Bigster', 'Dokker', 'Duster', 'Jogger', 'Lodgy', 'Logan MCV', 'Sandero', 'Spring' ],
            'Daewoo' => [ 'Autre modèle', 'Bucrane', 'Espero', 'Evanda', 'Kalos', 'Korando', 'Lanos', 'Leganza', 'Musso', 'Nexia/Cielo', 'Nubira', 'Rexton', 'Rezzo' ],
            'Daihatsu' => [ 'Autre modèle', 'Charade', 'Mira', 'Rocky' ],
            'Fiat' => [ 'Autre modèle', '500', '500L', '500X', 'Barchetta', 'Brava', 'Cinquecento', 'Croma SW', 'Dino Spider', 'Doblo', 'Fiorino Fourgon', 'Freemont', 'Fullback', 'Grande Panda', 'Grande Punto', 'Idea', 'Marea', 'Multipla', 'Palio', 'Panda', 'Punto', 'Seicento', 'Stilo', 'Talento', 'Tempra', 'Tipo', 'Trekking', 'Ulysse', 'Uno' ],
            'Ford' => [ 'Autre modèle', 'Aerostar', 'Aspire', 'B-Max', 'Bronco', 'C-Max', 'Club Wagon', 'Contour', 'Cougar', 'Courrier Combi', 'Crown Victoria', 'E150', 'E250', 'E350', 'EcoSport', 'Edge', 'Escape', 'Escort', 'Excursion', 'Expedition', 'Explorer', 'F150', 'F250', 'F350', 'F450', 'Festiva', 'Fiesta', 'Five Hundred', 'Flex', 'Focus', 'Focus ST', 'Freestar', 'Freestyle', 'Fusion', 'Galaxy', 'GT500', 'Ka', 'Ka+', 'Kuga', 'Lightning', 'Maverick', 'Mondeo', 'Orion', 'Probe', 'Puma', 'Ranger', 'S-Max', 'Scorpio Clipper', 'Sierra', 'Taurus', 'Tempo', 'Th!nk', 'Thunderbird', 'Tourneo', 'Transit Connect', 'Windstar', 'ZX2' ],
            'Honda' => [ 'Autre modèle', 'Accord', 'Civic', 'Concerto', 'CR-V', 'CR-Z', 'Crosstour', 'CRX', 'Del Sol', 'E', 'Element', 'EV', 'FCX', 'Fit', 'FR-V', 'HR-V', 'Insight', 'Integra', 'Jazz', 'Legend', 'Logo', 'NSX', 'Odyssey', 'Passport', 'Pilot', 'Prelude', 'Prologue', 'Ridgeline', 'S 2000', 'Shuttle', 'Stream', 'ZR-V' ],
            'Hyundai' => [ 'Autre modèle', 'Accent', 'Atos', 'Azera', 'Bayon', 'BlueOn', 'Casper', 'Elantra', 'Entourage', 'Equus', 'Excel', 'Galloper', 'Genesis', 'Getz', 'HED-5', 'i-Blue concept', 'i10', 'i20', 'i30', 'i40', 'Ioniq', 'iX55', 'Kona', 'Lantra', 'Matrix', 'Nexo', 'Palisade', 'Pony/Accent', 'Santa Fe', 'Satellite', 'Scoupe', 'Sonata', 'Staria', 'Terracan', 'Tiburon', 'Trajet', 'Tucson', 'Veloster', 'Veracruz', 'XG' ],
            'Infiniti' => [ 'Autre modèle', 'EX', 'FX', 'G25', 'G35', 'G37', 'I', 'IPL G', 'J', 'JX', 'M', 'Q', 'Q30', 'Q50S', 'Q60', 'QX', 'QX30', 'QX55', 'QX60', 'QX80' ],
            'Isuzu' => [ 'Autre modèle', 'Amigo', 'Ascender', 'Axiom', 'D-MAX', 'Hombre', 'Hombre Space', 'i-280', 'i-290', 'i-350', 'i-370', 'Impulse', 'MU-X', 'Oasis', 'Rodeo', 'Space', 'Stylus', 'Trooper', 'VehiCROSS' ],
            'Jeep' => [ 'Autre modèle', 'Cherokee', 'Comanche', 'Commander', 'Compass', 'EV6', 'EV9', 'Grand Cherokee', 'Liberty', 'Patriot', 'ProCeed', 'Renegade', 'Stonic', 'Treo', 'Wrangler', 'XCeed' ],
            'Kia' => [ 'Autre modèle', 'Amanti', 'Besta', 'Borrego', 'Cadenza', 'Carens', 'Carnival', 'Ceed', 'Cerato', 'Clarus/Credos', 'Cross GT', 'Cub', 'EV6 GT', 'Forte', 'K3', 'Magentis', 'Mentor', 'Mohave/Borrego', 'Niro', 'Opirus', 'Optima', 'Picanto', 'Pop', 'Pride', 'Provo', 'Quoris', 'Rio', 'Rio5', 'Rocsta', 'Rondo', 'Sedona', 'Sephia', 'Shuma', 'Sorento', 'Soul', 'Spectra', 'Sportage', 'Venga' ],
            'Lancia' => [ 'Autre modèle', 'Dedra', 'Delta', 'Kappa', 'Lybra', 'Musa', 'Phedra', 'Thema', 'Thesis', 'Y/Ipsilon', 'Zeta' ],
            'Land Rover' => [ 'Autre modèle', 'Defender', 'Discovery', 'Evoque', 'Freelander', 'Sport', 'Velar' ],
            'Lexus' => [ 'Autre modèle', 'CT', 'CT 200h', 'CX-3', 'CX-30', 'CX-50', 'CX-60', 'CX-90', 'ES', 'GS', 'GS 300', 'GS 430', 'GS 450h', 'GX', 'HS', 'HS 250h', 'IS', 'IS 200', 'IS 220', 'IS 250 C', 'IS 250 F Sport', 'IS 300', 'IS 300h', 'IS 300h F Sport', 'IS 350', 'IS 350 C', 'IS 350 F Sport', 'IS F', 'IS-F', 'LFA', 'LS', 'LS 250h', 'LS 400', 'LS 430', 'LS 460', 'LS 600h', 'LS Hybrid', 'LX', 'MX-30', 'RC 350 F Sport', 'RC F Track Edition', 'RX', 'RX 300', 'RX 400h', 'RX 450h', 'SC', 'SC 430', 'UX' ],
            'Mazda' => [ 'Autre modèle', '121', '323', '626', '929', '323', '6  105', '626', '929', 'B-2500', 'B-Series', 'B-Series Plus', 'B2500', 'CX-5', 'CX-7', 'CX-70', 'CX-80', 'CX-9', 'Demio', 'Familia', 'Mazda2', 'Mazda3', 'Mazda5', 'Mazda6', 'Miata MX-5', 'Millenia', 'MPV', 'MX-3', 'MX-5', 'MX-6', 'Navajo', 'Premacy', 'Protege', 'RX-7', 'RX-8', 'Tribute', 'Xedos 6', 'Xedos 9' ],
            'Mercedes' => [ 'Autre modèle', '190', '250', '300', '260', '320', '400', '500', '600', 'B-Class', 'Blue Zero FCell', 'CL65 AMG', 'CLA', 'Classe A', 'Classe B', 'Classe C', 'Classe E', 'Classe GL', 'Classe R', 'Classe S', 'Classe V', 'CLC', 'EcoVoyager', 'EQA', 'EQC', 'G-Class', 'G55 AMG', 'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'Sprinter', 'Vaneo', 'Vision SLR', 'W201' ],
            'Mini' => [ 'Autre modèle', 'Cooper' ],
            'Mitsubishi' => [ 'Autre modèle', '3000GT', 'ASX', 'Carisma', 'Challenger', 'Chariot', 'Colt', 'Destinator', 'Diamante', 'Eclipse', 'eK Space', 'Endeavor', 'Expo', 'EZ-MIEV', 'Galant', 'Grandis', 'GTO', 'i-Miev', 'L200', 'L300', 'Lancer', 'Mighty Max', 'Mirage', 'Mirage G4', 'Montero', 'Outlander', 'Pajero', 'Precis', 'Raider', 'RVR', 'Sigma', 'Space Runner', 'Space Star', 'Space Wagon', 'Truck' ],
            'Nissan' => [ 'Autre modèle', '100 NX', '200 SX', '240 SX', '300 ZX', '350 Z', '370 Z', 'Almera', 'Altima', 'Armada', 'Bluebird', 'Cube', 'FEV', 'Frontier', 'GT-R', 'Juke', 'Kicks', 'Leaf', 'Maxima', 'Micra', 'Murano', 'Navara', 'Note', 'NV1500', 'NV2500', 'NV3500', 'NX', 'Pathfinder', 'Patrol', 'Pick-Up', 'Pixo', 'Prairie', 'Primera', 'Qashqai', 'Quest', 'R’nessa', 'R390', 'Rogue', 'Sentra', 'Serena', 'Stanza', 'Sunny', 'Terrano', 'Titan', 'Vanette', 'Versa', 'X-Trail', 'Xterra', 'Z' ],
            'Opel' => [ 'Autre modèle', 'Agila', 'Ampera', 'Antara', 'Astra', 'Campo', 'Combo Tour', 'Corsa', 'Crossland', 'Frontera', 'Grandland', 'Insignia', 'Kadett', 'Meriva', 'Mokka', 'Monterey', 'Omega', 'Rocks-e', 'Senator', 'Signum', 'Sintra', 'Speedster', 'Tigra', 'Trixx', 'Vectra', 'Zafira' ],
            'Peugeot' => [ 'Autre modèle', '106', '107', '108', '206', '207', '208', '307', '308', '309', '405', '406', '407', '508', '605', '607', '807', '1007', '2008', '3008', '4008', '5008', 'Asphalte', 'E-2008', 'E-208', 'E-3008', 'E-408', 'E-5008', 'Expert', 'Ion', 'Partner', 'RCZ', 'Rifter', 'Traveller' ],
            'Renault' => [ 'Autre modèle', '19', '21', '25', 'Arkana', 'Austral', 'Avantime', 'Captur', 'Clio', 'Espace', 'Fluence', 'Kadjar', 'Kangoo', 'Koleos', 'Laguna', 'Latitude', 'Megane', 'Modus', 'R5 E-Tech', 'Rafale', 'Safrane', 'Scenic', 'Scenic E-Tech', 'Trafic', 'Twingo', 'Twizy', 'Vel Satis', 'Wind', 'Zoe', 'Zoom' ],
            'Rover' => [ 'Autre modèle', '25', '45', '75', 'CityRover', 'Mini', 'Serie 100', 'Serie 200', 'Serie 400', 'Serie 600', 'Serie 800', 'Streetwise' ],
            'Saab' => [ 'Autre modèle', '9-2X', '9-3', '9-4X', '9-5', '9-7X', '900', '9000' ],
            'Seat' => [ 'Autre modèle', 'Alhambra', 'Altea', 'Arona', 'Arosa', 'Cordoba', 'El Born', 'Exeo', 'Ibiza', 'Leon', 'Malaga', 'Mii', 'Tarraco', 'Terra tole', 'Toledo' ],
            'Skoda' => [ 'Autre modèle', 'Break Marathon', 'Citigo', 'Enyaq', 'Fabia', 'Favorit Break', 'Felicia', 'Kamiq', 'Karoq', 'Kodiaq', 'Octavia', 'Rapid Spaceback', 'Roomster', 'Scala', 'Superb' ],
            'Smart' => [ 'Autre modèle', 'Cabrio', 'Crossblade', 'Forfour', 'Fortwo' ],
            'Suzuki' => [ 'Autre modèle', 'Aerio', 'Alto', 'Baleno', 'Celerio', 'Covie', 'Equator', 'Esteem', 'Forenza', 'Grand Vitara', 'Ignis', 'Jimny', 'Kizashi', 'Liana', 'Mobile Terrace', 'MR Wagon FCV', 'Reno', 'S-Cross', 'Samurai', 'Sidekick', 'SJ', 'Splash', 'Swift', 'SX 4', 'Verona', 'Vitara', 'Wagon R FCV', 'X-90', 'XL-7' ],
            'Toyota' => [ 'Autre modèle', '4 Runner', 'Arteon', 'Auris', 'Avalon', 'Avensis', 'Aygo', 'bZ4X', 'C-HR', 'Camry', 'Carina E', 'Celica', 'Corolla', 'Cressida', 'Echo', 'FCHV', 'FJ Cruiser', 'Fun Cruiser', 'GR86', 'GT86', 'Hiace', 'Highlander', 'Hilux', 'Ipsum', 'iQ', 'Land Cruiser', 'MR', 'Paseo', 'PicNic/Ipsum', 'Previa', 'Prius', 'Proace', 'Rav 4', 'Sequoia', 'Sienna', 'Solara', 'Supra', 'T-Roc', 'T100', 'Tacoma', 'Tercel', 'Tundra', 'Urban Cruiser', 'Venza', 'Verso', 'Xtra', 'Yaris' ],
            'Volkswagen' => [ 'Autre modèle', 'Bora', 'Cabriolet', 'Caddy Life', 'CC', 'Chico', 'Coccinelle', 'Corrado', 'E-Up!', 'Eos', 'Eurovan', 'Fox', 'GLI', 'Golf', 'HyMotion', 'HyPower', 'ID. Buzz', 'ID.3', 'ID.4', 'ID.5', 'ID.7', 'Jetta', 'Lupo', 'Multivan', 'New Beetle', 'Nivus', 'Passat', 'Phaeton', 'Polo', 'Rabbit', 'rio', 'riolet', 'Routan', 'Scirocco', 'Sharan', 'Space Up Blue', 'T-Cross', 'T-Roc Cabriolet', 'Taigo', 'Tiguan', 'Touareg', 'Touran', 'Type 2', 'Up!' ],
            'Volvo' => [ 'Autre modèle', '240', '340', '440', '460', '480', '740', '850', '240', '740', '850', '940', '960', 'C30', 'C40', 'C70', 'Cross Country', 'EX30', 'EX90', 'Recharge', 'S40', 'S60', 'S70', 'S80', 'S80 ', 'S90', 'V40', 'V50', 'V60', 'V70', 'V90', 'XC40', 'XC60', 'XC70', 'XC90' ],
        ];

        foreach ($brandsData as $brandName => $models) {
            $brand = new Brand();
            $brand->setBrand($brandName);
            $manager->persist($brand);
            foreach ($models as $modelName) {
                $model = new Model();
                $model->setModel($modelName);
                $model->setBrand($brand);
                $manager->persist($model);
            }
        }

        $manager->flush();
    }
}
