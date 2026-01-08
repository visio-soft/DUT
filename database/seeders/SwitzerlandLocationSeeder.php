<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SwitzerlandLocationSeeder extends Seeder
{
    public function run(): void
    {
        $country = Location::firstOrCreate(
            ['type' => Location::TYPE_COUNTRY, 'slug' => 'switzerland'],
            ['name' => 'Switzerland', 'parent_id' => null]
        );

        $cantons = [
            'Zürich' => [
                'Zürich' => ['Zürich', 'Kilchberg', 'Rüschlikon', 'Thalwil', 'Zollikon'],
                'Affoltern' => ['Affoltern am Albis', 'Bonstetten', 'Hausen am Albis', 'Hedingen', 'Knonau', 'Mettmenstetten', 'Obfelden', 'Ottenbach', 'Rifferswil', 'Stallikon', 'Wettswil am Albis'],
                'Andelfingen' => ['Andelfingen', 'Benken', 'Berg am Irchel', 'Buch am Irchel', 'Dachsen', 'Dorf', 'Feuerthalen', 'Flaach', 'Flurlingen', 'Henggart', 'Humlikon', 'Kleinandelfingen', 'Laufen-Uhwiesen', 'Marthalen', 'Ossingen', 'Rheinau', 'Stammheim', 'Thalheim an der Thur', 'Trüllikon', 'Truttikon', 'Volken'],
                'Bülach' => ['Bachenbülach', 'Bassersdorf', 'Bülach', 'Dietlikon', 'Eglisau', 'Embrach', 'Freienstein-Teufen', 'Glattfelden', 'Hochfelden', 'Höri', 'Hüntwangen', 'Kloten', 'Lufingen', 'Nürensdorf', 'Oberembrach', 'Opfikon', 'Rafz', 'Rorbas', 'Wallisellen', 'Wasterkingen', 'Wil', 'Winkel'],
                'Dielsdorf' => ['Bachs', 'Boppelsen', 'Buchs', 'Dällikon', 'Dänikon', 'Dielsdorf', 'Hüttikon', 'Neerach', 'Niederglatt', 'Niederhasli', 'Niederweningen', 'Oberglatt', 'Oberweningen', 'Otelfingen', 'Regensberg', 'Regensdorf', 'Rümlang', 'Schleinikon', 'Schöfflisdorf', 'Stadel', 'Steinmaur', 'Weiach'],
                'Dietikon' => ['Aesch', 'Birmensdorf', 'Dietikon', 'Geroldswil', 'Oberengstringen', 'Oetwil an der Limmat', 'Schlieren', 'Uitikon', 'Unterengstringen', 'Urdorf', 'Weiningen'],
                'Hinwil' => ['Bäretswil', 'Bubikon', 'Dürnten', 'Fischenthal', 'Gossau', 'Grüningen', 'Hinwil', 'Rüti', 'Seegräben', 'Wald', 'Wetzikon'],
                'Horgen' => ['Adliswil', 'Horgen', 'Kilchberg', 'Langnau am Albis', 'Oberrieden', 'Richterswil', 'Rüschlikon', 'Thalwil', 'Wädenswil'],
                'Meilen' => ['Erlenbach', 'Herrliberg', 'Hombrechtikon', 'Küsnacht', 'Männedorf', 'Meilen', 'Oetwil am See', 'Stäfa', 'Uetikon am See', 'Zollikon'],
                'Pfäffikon' => ['Bauma', 'Fehraltorf', 'Hittnau', 'Illnau-Effretikon', 'Lindau', 'Pfäffikon', 'Russikon', 'Weisslingen', 'Wila', 'Wildberg'],
                'Uster' => ['Dübendorf', 'Egg', 'Fällanden', 'Greifensee', 'Maur', 'Mönchaltorf', 'Schwerzenbach', 'Uster', 'Volketswil', 'Wangen-Brüttisellen'],
                'Winterthur' => ['Altikon', 'Brütten', 'Dägerlen', 'Dättlikon', 'Dinhard', 'Elgg', 'Ellikon an der Thur', 'Elsau', 'Hagenbuch', 'Hettlingen', 'Neftenbach', 'Pfungen', 'Rickenbach', 'Schlatt', 'Seuzach', 'Turbenthal', 'Wiesendangen', 'Winterthur', 'Zell'],
            ],
            'Bern' => [
                'Bern-Mittelland' => ['Bern', 'Bolligen', 'Bremgarten bei Bern', 'Ittigen', 'Kirchlindach', 'Köniz', 'Muri bei Bern', 'Oberbalm', 'Stettlen', 'Vechigen', 'Wohlen bei Bern', 'Zollikofen'],
                'Biel/Bienne' => ['Aegerten', 'Bellmund', 'Biel/Bienne', 'Brügg', 'Ipsach', 'Lengnau', 'Ligerz', 'Meinisberg', 'Mörigen', 'Nidau', 'Orpund', 'Pieterlen', 'Port', 'Safnern', 'Scheuren', 'Schwadernau', 'Sutz-Lattrigen', 'Twann-Tüscherz'],
                'Emmental' => ['Aefligen', 'Affoltern im Emmental', 'Alchenstorf', 'Bätterkinden', 'Burgdorf', 'Dürrenroth', 'Eggiwil', 'Eriswil', 'Hasle bei Burgdorf', 'Heimiswil', 'Hindelbank', 'Huttwil', 'Kirchberg', 'Koppigen', 'Krauchthal', 'Langnau im Emmental', 'Lauperswil', 'Lützelflüh', 'Lyssach', 'Mötschwil', 'Niederösch', 'Oberburg', 'Oberösch', 'Röthenbach im Emmental', 'Rüderswil', 'Rüdtligen-Alchenflüh', 'Ruegsau', 'Rumendingen', 'Rüti bei Lyssach', 'Schangnau', 'Signau', 'Sumiswald', 'Trachselwald', 'Trub', 'Trubschachen', 'Utzenstorf', 'Wiler bei Utzenstorf', 'Willadingen', 'Wynigen', 'Zielebach'],
                'Frutigen-Niedersimmental' => ['Adelboden', 'Aeschi bei Spiez', 'Därstetten', 'Diemtigen', 'Erlenbach im Simmental', 'Frutigen', 'Kandergrund', 'Kandersteg', 'Krattigen', 'Oberwil im Simmental', 'Reichenbach im Kandertal', 'Spiez', 'Wimmis'],
                'Interlaken-Oberhasli' => ['Beatenberg', 'Bönigen', 'Brienz', 'Brienzwiler', 'Därligen', 'Grindelwald', 'Gsteigwiler', 'Gündlischwand', 'Habkern', 'Hasliberg', 'Hofstetten bei Brienz', 'Interlaken', 'Iseltwald', 'Lauterbrunnen', 'Leissigen', 'Lütschental', 'Matten bei Interlaken', 'Meiringen', 'Niederried bei Interlaken', 'Oberried am Brienzersee', 'Ringgenberg', 'Saxeten', 'Schattenhalb', 'Schwanden bei Brienz', 'Unterseen', 'Wilderswil'],
                'Jura bernois' => ['Belprahon', 'Champoz', 'Corcelles', 'Corgémont', 'Cormoret', 'Cortébert', 'Court', 'Courtelary', 'Crémines', 'Eschert', 'Grandval', 'La Ferrière', 'La Neuveville', 'Loveresse', 'Mont-Tramelan', 'Moutier', 'Nods', 'Orvin', 'Perrefitte', 'Péry-La Heutte', 'Petit-Val', 'Plateau de Diesse', 'Reconvilier', 'Renan', 'Roches', 'Romont', 'Saicourt', 'Saint-Imier', 'Sauge', 'Saules', 'Schelten', 'Seehof', 'Sonceboz-Sombeval', 'Sonceboz', 'Sorvilier', 'Tavannes', 'Tramelan', 'Valbirse', 'Villeret'],
                'Oberaargau' => ['Aarwangen', 'Attiswil', 'Auswil', 'Bannwil', 'Berken', 'Bettenhausen', 'Bleienbach', 'Busswil bei Melchnau', 'Eriswil', 'Farnern', 'Gondiswil', 'Graben', 'Heimenhausen', 'Herzogenbuchsee', 'Huttwil', 'Inkwil', 'Langenthal', 'Lotzwil', 'Madiswil', 'Melchnau', 'Niederbipp', 'Niederönz', 'Oberbipp', 'Obersteckholz', 'Ochlenberg', 'Oeschenbach', 'Reisiswil', 'Roggwil', 'Rohrbach', 'Rohrbachgraben', 'Rumisberg', 'Rütschelen', 'Schwarzhäusern', 'Seeberg', 'Thörigen', 'Thunstetten', 'Ursenbach', 'Walliswil bei Niederbipp', 'Walliswil bei Wangen', 'Walterswil', 'Wangen an der Aare', 'Wangenried', 'Wiedlisbach', 'Wolfisberg', 'Wynau', 'Wyssachen'],
                'Obersimmental-Saanen' => ['Boltigen', 'Gsteig', 'Lauenen', 'Lenk', 'Saanen', 'St. Stephan', 'Zweisimmen'],
                'Seeland' => ['Aarberg', 'Arch', 'Bargen', 'Brüttelen', 'Bühl', 'Büren an der Aare', 'Diessbach bei Büren', 'Dotzigen', 'Epsach', 'Erlach', 'Finsterhennen', 'Gals', 'Gampelen', 'Grossaffoltern', 'Hagneck', 'Hermrigen', 'Ins', 'Jens', 'Kallnach', 'Kappelen', 'Leuzigen', 'Lüscherz', 'Lyss', 'Meienried', 'Merzligen', 'Müntschemier', 'Oberwil bei Büren', 'Radelfingen', 'Rapperswil', 'Rüti bei Büren', 'Schüpfen', 'Seedorf', 'Siselen', 'Studen', 'Täuffelen', 'Treiten', 'Tschugg', 'Vinelz', 'Walperswil', 'Wengi', 'Worben'],
                'Thun' => ['Amsoldingen', 'Blumenstein', 'Buchholterberg', 'Burgistein', 'Eriz', 'Fahrni', 'Forst-Längenbühl', 'Gurzelen', 'Heiligenschwendi', 'Heimberg', 'Hilterfingen', 'Homberg', 'Horrenbach-Buchen', 'Oberhofen am Thunersee', 'Oberlangenegg', 'Pohlern', 'Schwendibach', 'Seftigen', 'Sigriswil', 'Steffisburg', 'Teuffenthal', 'Thierachern', 'Thun', 'Uebeschi', 'Uetendorf', 'Unterlangenegg', 'Uttigen', 'Wachseldorn', 'Wattenwil', 'Zwieselberg'],
            ],
            'Luzern' => [
                'Luzern-Land' => ['Adligenswil', 'Buchrain', 'Dierikon', 'Ebikon', 'Gisikon', 'Greppen', 'Honau', 'Horw', 'Kriens', 'Malters', 'Meggen', 'Meierskappel', 'Root', 'Schwarzenberg', 'Udligenswil', 'Vitznau', 'Weggis'],
                'Luzern-Stadt' => ['Luzern'],
                'Hochdorf' => ['Aesch', 'Altwis', 'Ballwil', 'Emmen', 'Ermensee', 'Eschenbach', 'Hitzkirch', 'Hochdorf', 'Hohenrain', 'Inwil', 'Rain', 'Römerswil', 'Rothenburg', 'Schongau'],
                'Sursee' => ['Beromünster', 'Büron', 'Buttisholz', 'Eich', 'Geuensee', 'Grosswangen', 'Hildisrieden', 'Knutwil', 'Mauensee', 'Neuenkirch', 'Nottwil', 'Oberkirch', 'Rickenbach', 'Ruswil', 'Schenkon', 'Schlierbach', 'Sempach', 'Sursee', 'Triengen', 'Wolhusen'],
                'Willisau' => ['Alberswil', 'Altbüron', 'Altishofen', 'Dagmersellen', 'Ebersecken', 'Egolzwil', 'Ettiswil', 'Fischbach', 'Grossdietwil', 'Hergiswil bei Willisau', 'Luthern', 'Menznau', 'Nebikon', 'Pfaffnau', 'Reiden', 'Roggliswil', 'Schötz', 'Ufhusen', 'Wauwil', 'Wikon', 'Willisau', 'Zell'],
                'Entlebuch' => ['Doppleschwand', 'Entlebuch', 'Escholzmatt-Marbach', 'Flühli', 'Hasle', 'Romoos', 'Schüpfheim', 'Werthenstein'],
            ],
            'Vaud' => [
                'Aigle' => ['Aigle', 'Bex', 'Chessel', 'Corbeyrier', 'Gryon', 'Lavey-Morcles', 'Leysin', 'Noville', 'Ollon', 'Ormont-Dessous', 'Ormont-Dessus', 'Rennaz', 'Roche', 'Villeneuve', 'Yvorne'],
                'Broye-Vully' => ['Avenches', 'Bussy-sur-Moudon', 'Champtauroz', 'Chavannes-sur-Moudon', 'Chevroux', 'Corcelles-le-Jorat', 'Corcelles-près-Payerne', 'Cudrefin', 'Curtilles', 'Dompierre', 'Faoug', 'Grandcour', 'Henniez', 'Hermenches', 'Lovatens', 'Lucens', 'Missy', 'Moudon', 'Payerne', 'Prévonloup', 'Ropraz', 'Rossenges', 'Syens', 'Trey', 'Treytorrens', 'Valbroye', 'Villars-le-Comte', 'Villarzel', 'Vulliens', 'Vully-les-Lacs'],
                'Gros-de-Vaud' => ['Assens', 'Bercher', 'Bettens', 'Bioley-Orjulaz', 'Bottens', 'Boulens', 'Bournens', 'Boussens', 'Bretigny-sur-Morrens', 'Cugy', 'Daillens', 'Echallens', 'Essertines-sur-Yverdon', 'Etagnières', 'Fey', 'Froideville', 'Goumoëns', 'Jorat-Menthue', 'Lussery-Villars', 'Mex', 'Montanaire', 'Montilliez', 'Morrens', 'Oulens-sous-Echallens', 'Pailly', 'Penthalaz', 'Penthaz', 'Penthéréaz', 'Poliez-Pittet', 'Rueyres', 'Saint-Barthélemy', 'Sullens', 'Villars-le-Terroir', 'Vuarrens', 'Vufflens-la-Ville'],
                'Jura-Nord vaudois' => ['Agiez', 'Arnex-sur-Orbe', 'Ballaigues', 'Baulmes', 'Bavois', 'Belmont-sur-Yverdon', 'Bioley-Magnoux', 'Bofflens', 'Bonvillars', 'Bretonnières', 'Bullet', 'Chamblon', 'Champagne', 'Champvent', 'Chavannes-le-Chêne', 'Chavornay', 'Chêne-Pâquier', 'Cheseaux-Noréaz', 'Concise', 'Corcelles-près-Concise', 'Cronay', 'Croy', 'Cuarny', 'Démoret', 'Donneloye', 'Ependes', 'Fiez', 'Fontaines-sur-Grandson', 'Giez', 'Grandevent', 'Grandson', 'Juriens', 'L\'Abbaye', 'L\'Abergement', 'La Praz', 'Le Chenit', 'Le Lieu', 'Les Clées', 'Lignerolle', 'Mathod', 'Mauborget', 'Molondin', 'Montagny-près-Yverdon', 'Montcherand', 'Mutrux', 'Novalles', 'Onnens', 'Orbe', 'Orges', 'Orzens', 'Pomy', 'Premier', 'Provence', 'Rances', 'Romainmôtier-Envy', 'Rovray', 'Sainte-Croix', 'Sergey', 'Suchy', 'Suscévin', 'Tévenon', 'Treycovagnes', 'Ursins', 'Valeyres-sous-Montagny', 'Valeyres-sous-Rances', 'Valeyres-sous-Ursins', 'Vallorbe', 'Vaulion', 'Villars-Epeney', 'Vugelles-La Mothe', 'Vuiteboeuf', 'Yverdon-les-Bains', 'Yvonand'],
                'Lausanne' => ['Cheseaux-sur-Lausanne', 'Epalinges', 'Jouxtens-Mézery', 'Lausanne', 'Le Mont-sur-Lausanne', 'Romanel-sur-Lausanne'],
                'Lavaux-Oron' => ['Belmont-sur-Lausanne', 'Bourg-en-Lavaux', 'Chexbres', 'Essertes', 'Forel', 'Jorat-Mézières', 'Lutry', 'Maracon', 'Montpreveyres', 'Oron', 'Paudex', 'Puidoux', 'Pully', 'Rivaz', 'Saint-Saphorin', 'Savigny', 'Servion'],
                'Morges' => ['Aclens', 'Allaman', 'Apples', 'Aubonne', 'Ballens', 'Berolle', 'Bière', 'Bougy-Villars', 'Bremblens', 'Buchillon', 'Bussy-Chardonney', 'Chavannes-le-Veyron', 'Chevilly', 'Chigny', 'Clarmont', 'Cossonay', 'Cottens', 'Cuarnens', 'Denens', 'Denges', 'Dizy', 'Echandens', 'Echichens', 'Eclépens', 'Etoy', 'Féchy', 'Ferreyres', 'Gimel', 'Gollion', 'Grancy', 'Hautemorges', 'La Chaux', 'La Sarraz', 'Lavigny', 'L\'Isle', 'Lonay', 'Lully', 'Lussy-sur-Morges', 'Mauraz', 'Moiry', 'Mollens', 'Mont-la-Ville', 'Montricher', 'Morges', 'Orny', 'Pampigny', 'Pompaples', 'Préverenges', 'Romanel-sur-Morges', 'Saint-Livres', 'Saint-Oyens', 'Saint-Prex', 'Saubraz', 'Senarclens', 'Tolochenaz', 'Vaux-sur-Morges', 'Villars-sous-Yens', 'Vufflens-le-Château', 'Vullierens', 'Yens'],
                'Nyon' => ['Arnex-sur-Nyon', 'Arzier-Le Muids', 'Bassins', 'Begnins', 'Bogis-Bossey', 'Borex', 'Boursins', 'Burtigny', 'Chavannes-de-Bogis', 'Chavannes-des-Bois', 'Chéserex', 'Coinsins', 'Commugny', 'Coppet', 'Crans', 'Crassier', 'Duillier', 'Dully', 'Essertines-sur-Rolle', 'Eysins', 'Founex', 'Genolier', 'Gilly', 'Gingins', 'Givrins', 'Gland', 'Grens', 'La Rippe', 'Le Vaud', 'Longirod', 'Luins', 'Marchissy', 'Mies', 'Mont-sur-Rolle', 'Nyon', 'Perroy', 'Prangins', 'Rolle', 'Saint-Cergue', 'Saint-George', 'Signy-Avenex', 'Tartegnin', 'Treycovagnes', 'Trélex', 'Vich', 'Vinzel'],
                'Ouest lausannois' => ['Bussigny', 'Chavannes-près-Renens', 'Crissier', 'Ecublens', 'Prilly', 'Renens', 'Saint-Sulpice', 'Villars-Sainte-Croix'],
                'Riviera-Pays-d\'Enhaut' => ['Blonay', 'Chardonne', 'Château-d\'Oex', 'Corseaux', 'Corsier-sur-Vevey', 'Jongny', 'La Tour-de-Peilz', 'Montreux', 'Rossinière', 'Rougemont', 'Saint-Légier-La Chiésaz', 'Vevey', 'Veytaux'],
            ],
            'Basel-Stadt' => [
                'Basel-Stadt' => ['Basel', 'Bettingen', 'Riehen']
            ],
             'Basel-Landschaft' => [
                'Arlesheim' => ['Aesch', 'Allschwil', 'Arlesheim', 'Biel-Benken', 'Binningen', 'Birsfelden', 'Bottmingen', 'Ettingen', 'Münchenstein', 'Muttenz', 'Oberwil', 'Pfeffingen', 'Reinach', 'Schönenbuch', 'Therwil'],
                'Laufen' => ['Blauen', 'Brislach', 'Burg', 'Dittingen', 'Duggingen', 'Grellingen', 'Laufen', 'Liesberg', 'Nenzlingen', 'Roggenburg', 'Röschenz', 'Wahlen', 'Zwingen'],
                'Liestal' => ['Arisdorf', 'Augst', 'Bubendorf', 'Frenkendorf', 'Füllinsdorf', 'Giebenach', 'Hersberg', 'Lausen', 'Liestal', 'Lupsingen', 'Pratteln', 'Ramlinsburg', 'Seltisberg', 'Ziefen'],
                'Sissach' => ['Böckten', 'Buckten', 'Buus', 'Diepflingen', 'Gelterkinden', 'Häfelfingen', 'Hemmiken', 'Itingen', 'Känerkinden', 'Kilchberg', 'Läufelfingen', 'Maisprach', 'Nusshof', 'Oltingen', 'Rickenbach', 'Rünenberg', 'Sissach', 'Tecknau', 'Tenniken', 'Thürnen', 'Wintersingen', 'Wittinsburg', 'Zeglingen', 'Zunzgen'],
                'Waldenburg' => ['Arboldswil', 'Bennwil', 'Bretzwil', 'Diegten', 'Eptingen', 'Hölstein', 'Lampenberg', 'Langenbruck', 'Lauwil', 'Liedertswil', 'Niederdorf', 'Oberdorf', 'Reigoldswil', 'Ritteringen', 'Titterten', 'Waldenburg'],
            ],
             'Geneva' => [
                'Geneva' => ['Aire-la-Ville', 'Anières', 'Avully', 'Avusy', 'Bardonnex', 'Bellevue', 'Bernex', 'Carouge', 'Cartigny', 'Céligny', 'Chancy', 'Chêne-Bougeries', 'Chêne-Bourg', 'Choulex', 'Collex-Bossy', 'Collonge-Bellerive', 'Cologny', 'Confignon', 'Corsier', 'Dardagny', 'Genève', 'Genthod', 'Gy', 'Hermance', 'Jussy', 'Lancy', 'Le Grand-Saconnex', 'Meinier', 'Meyrin', 'Onex', 'Perly-Certoux', 'Plan-les-Ouates', 'Pregny-Chambésy', 'Presinge', 'Puplinge', 'Russin', 'Satigny', 'Soral', 'Thônex', 'Troinex', 'Vandœuvres', 'Vernier', 'Versoix', 'Veyrier']
            ],
             'St. Gallen' => [
                'St. Gallen' => ['Andwil', 'Eggersriet', 'Gaiserwald', 'Gossau', 'Häggenschwil', 'Muolen', 'St. Gallen', 'Waldkirch', 'Wittenbach'],
                'Rorschach' => ['Berg', 'Goldach', 'Mörschwil', 'Rorschach', 'Rorschacherberg', 'Steinach', 'Thal', 'Tübach', 'Untereggen'],
                'Rheintal' => ['Altstätten', 'Au', 'Balgach', 'Berneck', 'Diepoldsau', 'Eichberg', 'Marbach', 'Oberriet', 'Rebstein', 'Rheineck', 'Rüthi', 'St. Margrethen', 'Widnau'],
                'Werdenberg' => ['Buchs', 'Gams', 'Grabs', 'Sennwald', 'Sevelen', 'Wartau'],
                'Sarganserland' => ['Bad Ragaz', 'Flums', 'Mels', 'Pfäfers', 'Quarten', 'Sargans', 'Vilters-Wangs', 'Walenstadt'],
                'See-Gaster' => ['Amden', 'Benken', 'Eschenbach', 'Gommiswald', 'Kaltbrunn', 'Rapperswil-Jona', 'Schänis', 'Schmerikon', 'Uznach', 'Weesen'],
                'Toggenburg' => ['Bütschwil-Ganterschwil', 'Ebnat-Kappel', 'Hemberg', 'Kirchberg', 'Lichtensteig', 'Lütisburg', 'Mosnang', 'Neckertal', 'Nesslau', 'Oberhelfenschwil', 'Wattwil', 'Wildhaus-Alt St. Johann'],
                'Wil' => ['Degersheim', 'Flawil', 'Jonschwil', 'Niederbüren', 'Niederhelfenschwil', 'Oberbüren', 'Oberuzwil', 'Uzwil', 'Wil', 'Zuzwil'],
            ],
        ];

        // Additional Cantons (Sampled)
        $cantons['Aargau'] = [
            'Aarau' => ['Aarau', 'Biberstein', 'Buchs', 'Densbüren', 'Erlinsbach', 'Gränichen', 'Hirschthal', 'Küttigen', 'Muhen', 'Oberentfelden', 'Suhr', 'Unterentfelden'],
            'Baden' => ['Baden', 'Bellikon', 'Bergdietikon', 'Birmenstorf', 'Ehrendingen', 'Ennetbaden', 'Fislisbach', 'Freienwil', 'Gebenstorf', 'Killwangen', 'Künten', 'Mägenwil', 'Mellingen', 'Neuenhof', 'Niederrohrdorf', 'Oberrohrdorf', 'Obersiggenthal', 'Remetschwil', 'Spreitenbach', 'Stetten', 'Turgi', 'Untersiggenthal', 'Wettingen', 'Wohlenschwil', 'Würenlingen', 'Würenlos'],
            'Bremgarten' => ['Arni', 'Berikon', 'Bremgarten', 'Büttikon', 'Dottikon', 'Eggenwil', 'Fischbach-Göslikon', 'Hägglingen', 'Islisberg', 'Jonen', 'Niederwil', 'Oberlunkhofen', 'Oberwil-Lieli', 'Rudolfstetten-Friedlisberg', 'Sarmenstorf', 'Tägerig', 'Uezwil', 'Unterlunkhofen', 'Villmergen', 'Widen', 'Wohlen', 'Zufikon'],
             // ... more districts can be added
        ];
        
        $cantons['Ticino'] = [
            'Bellinzona' => ['Arbedo-Castione', 'Bellinzona', 'Cadenazzo', 'Isone', 'Lumino', 'Sant\'Antonino'],
            'Blenio' => ['Acquarossa', 'Blenio', 'Serravalle'],
            'Leventina' => ['Airolo', 'Bedretto', 'Bodio', 'Dalpe', 'Faido', 'Giornico', 'Personico', 'Pollegio', 'Prato Leventina', 'Quinto'],
            'Locarno' => ['Ascona', 'Brissago', 'Centovalli', 'Cugnasco-Gerra', 'Gordola', 'Lavertezzo', 'Locarno', 'Losone', 'Mergoscia', 'Minusio', 'Muralto', 'Onsernone', 'Orselina', 'Ronco sopra Ascona', 'Tenero-Contra', 'Terre di Pedemonte', 'Verzasca'],
            'Lugano' => ['Agno', 'Alto Malcantone', 'Aranno', 'Arogno', 'Astano', 'Bedano', 'Bedigliora', 'Bioggio', 'Bissone', 'Breggia', 'Brusino Arsizio', 'Cademario', 'Cadempino', 'Cadro', 'Canobbio', 'Capriasca', 'Caslano', 'Collina d\'Oro', 'Comano', 'Croglio', 'Cureglia', 'Curio', 'Grancia', 'Gravesano', 'Lamone', 'Lugano', 'Magliaso', 'Manno', 'Maroggia', 'Massagno', 'Melide', 'Mezzovico-Vira', 'Miglieglia', 'Monteborggio', 'Montebra', 'Monteggio', 'Morcote', 'Muzzano', 'Neggio', 'Novaggio', 'Origlio', 'Paradiso', 'Ponte Capriasca', 'Ponte Tresa', 'Porza', 'Pura', 'Riva San Vitale', 'Rovio', 'Savosa', 'Sessa', 'Sorengo', 'Stabio', 'Torricella-Taverne', 'Tresa', 'Vancy', 'Vezia', 'Vico Morcote'],
             'Mendrisio' => ['Balerna', 'Breggia', 'Castel San Pietro', 'Chiasso', 'Coldrerio', 'Mendrisio', 'Morbio Inferiore', 'Novazzano', 'Riva San Vitale', 'Stabio', 'Vacallo'],
             'Riviera' => ['Biasca', 'Riviera'],
             'Vallemaggia' => ['AveGno Gordevio', 'Bosco/Gurin', 'Campo', 'Cerentino', 'Cevio', 'Linescio', 'Maggia', 'Lavizzara']
        ];
        
        $cantons['Fribourg'] = [
            'Broye' => ['Belmont-Broye', 'Châtillon', 'Cheiry', 'Cheyres-Châbles', 'Cugy', 'Delley-Portalban', 'Estavayer', 'Fétigny', 'Gletterens', 'Lully', 'Ménières', 'Montagny', 'Les Montets', 'Nuvilly', 'Prévondavaux', 'Saint-Aubin', 'Sévaz', 'Surpierre', 'Vallon'],
            'Glâne' => ['Auboranges', 'Billens-Hennens', 'Chapelle', 'Châtonnaye', 'Ecublens', 'Grangettes', 'Massonnens', 'Mézières', 'Montet', 'Romont', 'Rue', 'Siviriez', 'Torny', 'Ursy', 'Villaz-Saint-Pierre', 'Vuisternens-devant-Romont'],
            'Gruyère' => ['Bas-Intyamon', 'Botterens', 'Broc', 'Bulle', 'Châtel-sur-Montsalvens', 'Corbières', 'Crésuz', 'Echarlens', 'Grandvillard', 'Gruyères', 'Hauteville', 'Haut-Intyamon', 'Jaun', 'Marsens', 'Morlon', 'Le Pâquier', 'Pont-en-Ogoz', 'Pont-la-Ville', 'Riaz', 'La Roche', 'Sâles', 'Sorens', 'Val-de-Charmey', 'Vaulruz', 'Vuadens'],
            'Sarine' => ['Autigny', 'Avry', 'Belfaux', 'Chénens', 'Corminboeuf', 'Corserey', 'Cottens', 'Ferpicloz', 'Fribourg', 'Gibloux', 'Givisiez', 'Granges-Paccot', 'Grolley', 'Hauterive', 'La Brillaz', 'La Sonnaz', 'Le Mouret', 'Marly', 'Matran', 'Neyruz', 'Noréaz', 'Pierrafortscha', 'Ponthaux', 'Prez', 'Treyvaux', 'Villars-sur-Glâne', 'Villarsel-sur-Marly'],
             // ... more
        ];

        // Ensure we cover the 26 Cantons with at least their main city as district/municipality if not fully listed
        $otherCantons = [
            'Solothurn' => ['Solothurn', 'Olten', 'Grenchen'],
            'Schaffhausen' => ['Schaffhausen', 'Neuhausen am Rheinfall'],
            'Appenzell Ausserrhoden' => ['Herisau'],
            'Appenzell Innerrhoden' => ['Appenzell'],
            'Glarus' => ['Glarus', 'Glarus Nord', 'Glarus Süd'],
            'Zug' => ['Zug', 'Baar', 'Cham', 'Risch'],
            'Thurgau' => ['Frauenfeld', 'Kreuzlingen', 'Arbon', 'Weinfelden'],
            'Graubünden' => ['Chur', 'Davos', 'Landquart', 'Domat/Ems'],
            'Jura' => ['Delémont', 'Porrentruy', 'Saignelégier'],
            'Neuchâtel' => ['Neuchâtel', 'La Chaux-de-Fonds', 'Le Locle'],
            'Valais' => ['Sion', 'Martigny', 'Monthey', 'Brig-Glis', 'Sierre'],
            'Uri' => ['Altdorf'],
            'Schwyz' => ['Schwyz', 'Freienbach', 'Küssnacht'],
            'Obwalden' => ['Sarnen'],
            'Nidwalden' => ['Stans'],
            'Lucerne' => ['Lucerne', 'Emmen', 'Kriens'], // already covered partially but good to ensure english names? User asked for "isviçre" (tr). I used German/French names mostly.
        ];

        foreach ($cantons as $cantonName => $districts) {
            $city = Location::firstOrCreate(
                ['type' => Location::TYPE_CITY, 'parent_id' => $country->id, 'name' => $cantonName]
            );

            foreach ($districts as $districtName => $municipalities) {
                // If the canton structure is flat (like in the otherCantons array)
                if (is_int($districtName)) {
                     // logic for flat array if I mix types, but here $cantons has 'District' => ['Municipality']
                     continue; 
                }

                $district = Location::firstOrCreate(
                    ['type' => Location::TYPE_DISTRICT, 'parent_id' => $city->id, 'name' => $districtName]
                );

                foreach ($municipalities as $municipalityName) {
                    Location::firstOrCreate(
                        ['type' => Location::TYPE_NEIGHBORHOOD, 'parent_id' => $district->id, 'name' => $municipalityName]
                    );
                }
            }
        }

        // Handle the simplified otherCantons
        foreach ($otherCantons as $cantonName => $municipalities) {
             $city = Location::firstOrCreate(
                ['type' => Location::TYPE_CITY, 'parent_id' => $country->id, 'name' => $cantonName]
            );
            
            // For these, we use the Canton name as the District name (or "Merkez")
            $district = Location::firstOrCreate(
                ['type' => Location::TYPE_DISTRICT, 'parent_id' => $city->id, 'name' => $cantonName . ' District']
            );

             foreach ($municipalities as $municipalityName) {
                    Location::firstOrCreate(
                        ['type' => Location::TYPE_NEIGHBORHOOD, 'parent_id' => $district->id, 'name' => $municipalityName]
                    );
                }
        }
    }
}
