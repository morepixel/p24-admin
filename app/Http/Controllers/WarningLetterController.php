<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class WarningLetterController extends Controller
{
    public function generate(Report $report)
    {
        $user = auth()->user();
        
        // Geschlecht-spezifische Texte
        $haltergeschlecht = $report->halterGeschlecht === 'W' ? 'Sehr geehrte Frau' : 'Sehr geehrter Herr';
        $halter = $report->halterGeschlecht === 'W' ? 'Halterin' : 'Halter';
        $derdiedas_klein = $report->halterGeschlecht === 'W' ? 'die' : 'der';
        $derdiedas_gross = $report->halterGeschlecht === 'W' ? 'Die' : 'Der';
        $demder_klein = $report->halterGeschlecht === 'W' ? 'der' : 'dem';
        $schuldner = $report->halterGeschlecht === 'W' ? 'Schuldnerin' : 'Schuldner';
        $glaubiger = $report->halterGeschlecht === 'W' ? 'Gläubigerin' : 'Gläubiger';
        $diesemdieser = $report->halterGeschlecht === 'W' ? 'dieser' : 'diesem';
        
        // Gerichtsentscheidungen
        $courtDecisions = "(so auch AG Wismar, Urt. v. 14.12.2018 – 2 C 351/18 und 16.12.2019 – 8 C 125/19; AG Braunschweig, Beschl. 27.09.18 – 117 C 2199/18 sowie Beschl. v. 09.09.2019 – 118 C 1270/19; AG Düsseldorf, Urt. v. 04.12.2018 – 35 C 220/18; AG Mönchengladbach-Rheydt, Beschl. v. 30.08.2018; AG Reutlingen, Urt. v. 07.01.2019 – 14 C 1226/18; AG Hamburg-Bergedorf, Urt. v. 12.09.2018 – 410a C 62/18 sowie Urt. v. 10.10.2018 – 409 C 151/18; AG Wuppertal, Urt. v. 11.01.2019 – 391 C 208/18; AG Gifhorn, Urt. v. 17.01.2019 – 2 C 755/18, [mit sehr guter und ausführlicher Begründung] und Urt. v. 02.04.2019 – 2 C 582/18 (VI); AG München, Beschl. v. 28.01.2019 – 472 C 23220/18; AG Singen, Urt. v. 07.03.2019 – 10 C 21/19; AG Ansbach, Urt. v. 28.03.2019 – 5 C 1613/18 und Urt. v. 23.05.2019 – 2 C 223/19; AG Koblenz, Beschl. v. 15.02.2019 und Urt. v. 24.04.2019 – 161 C 2133/18 und Urt. v. 21.03.2019 – 161 C 2133/18; AG Pforzheim – Beschl. v. 11.04.2019 – 13 C 26/18; AG Hildesheim, Urt. v. 03.05.2019 – 43 C 40/19; AG Geislingen, Urt. v. 06.05.2019 – 6 C 79/19; AG Gladbeck, Urt. v. 04.07.2019 – 11 C 190/19; AG Hagen, Urt. v. 15.08.2019 – 10 C 225/19; AG Schwäbisch Gmünd, Urt. v. 22.08.2019 – 5 C 516/19; AG Siegburg, Urt. v. 06.09.2019 – 128 C 77/19; AG Backnang, Urt. v. 18.09.2019 – 5 C 481/19; AG Neuburg a.d. Donau, Urteil v. 24.09.2019 – 5 C 372/19, AG Erding, Urt. v. 26.09.20219 – 14 C 2348/19; AG Kirchhain, Beschl. v. 09.10.2019 – 7 C 277/19; LG Stuttgart, Beschl. v. 30.10.2019 – 13 S 132/19; AG Gladbeck, Beschl. v. 05.11.19 – 12 C 268/19; AG Cuxhaven, Urt. v. 03.12.19 – 5 C 377/19; LG Köln, Beschl. v. 20.12.2019 – 1 S 234/19; AG Schleswig, Beschl. v. 11.01.2020 – 31 C 177/18; LG Mainz, Urt. v. 07.01.2020 – 6 S 39/19; AG Köln, Urt. v. 15.01.2020 – 126 C 506/19; LG Landshut, Beschl. v. 24.01.2020 – 13 S 3407/19; AG Hannover, Urt. v. 04.03.2020 – 424 C 12325/19; AG Mainz, Urt. v. 02.04.2020 – 88 C 23/20; AG München, Beschl. v. 20.04.2020 – 432 C 21470/19; AG Sonthofen, Beschl. v. 07.05.2020 – 2 C 190/20); AG Heilbronn, Beschl. v. 11.05.2020 – 6 C 205/20; AG Gladbeck, Urt. v. 15.05.2020 – 11 C 337/19; AG Wismar, Urt. v. 19.05.2020 – 2 C 128/20; AG Paderborn, Beschl. v. 10.06.2020 – 53 C 8/29; AG Velbert, Urt. v. 17.06.2020 – 11 C 74/20; AG Calw, Urt. v. 22.06.2020 – 8 C 53/20; AG Deggendorf, Urt. v. 30.06.2020 – 2 C 59/20; AG Offenbach am Main, Urt. v. 10.07.2020 – 35 C 32/20; AG Karlsruhe, Urt. v. 07.08.2020 -  8 C 1349/20; AG Frankfurt a. M., Urt. v. 26.10.2020 – 385 C 343/20; AG Landsberg, Urt. v. 28.10.2020 – 4 C 598/20; AG Schöneberg, Urt. v. 29.10.2020 – 14 C 110/20; AG Bad Homburg, Urt. v. 02.11.2020 – 2 C 502/20; AG Fürth, Urt. v. 03.12.2020 – 370 C 1376/20; AG Kirchheim unter Teck, Urt. v. 12.01.2021 – 2 C 456/20; AG Münster, Urt. v. 22.01.2021 – 4 C 2691/20; LG Stuttgart, Urt. v. 26.01.2021 – 13 S 98/20; AG Reutlingen, Urt. v. 08.02.2021 – 11 C 2/21; AG Weiden, Beschl. v. 10.03.2021 – 2 C 820/20; AG Dortmund, Beschl. v. 26.03.2021 – 411 C 5512/20; AG Ulm, Urt. v. 06.04.2021 – 7 C 311/21; AG Hannover, Urt. v. 15.04.2021 – 425 C 12732/20; AG Norderstedt, Urt. v. 17.05.2021 – 44 C 118/21; AG Gummersbach, Urt. v. 21.05.2021 – 15 C 111/21; AG München, Urt. v. 08.06.2021 – 432 C 7565/21; AG Passau, Urt. v. 14.06.2021 – 18 C 781/21; AG Düsseldorf, Urt. v. 22.06.2021 – 18 C 159/21; AG Herne, Urt. v. 30.06.2021 – 9 C 63/21; AG Fürth, Urt. v. 30.06.2021 – 360 C 665/21; AG Würzburg, Urt. v. 06.07.2021 – 18 C 1070/21; AG Weilburg, Urt. v. 28.07.2021 – 5 C 210/21; AG Völklingen, Urt. v. 06.08.2021 – 16 C 210/21; AG Köln, Urt. v. 13.08.2021 – 121 C 286/21; AG Erding, Urt. v. 06.09.2021 – 114 C 3480/21; AG Düsseldorf, Urt. v . 28.10.2021 – 26 C 93/21; AG Hannover, Urt. v. 28.10.2021 – 552 C 7409/21; LG Hildesheim, Beschl. v. 10.11.2021 – 2 T 20/21; AG Passau, Urt. v. 22.11.2021 – 18 C 1071/21; AG Hagen, Beschl. v. 29.11.2021 – 17 C 46/21; AG Köln, Urt. v. 30.11.2021 – 154 C 225/21; AG Sonthofen, Urt. v. 01.12.2021 – 3 C 434/21; AG Leverkusen, Urt. v. 17.12.2021 – 22 C 149/21; AG Fritzlar, Urt. v. 11.01.2022 – 8 C 344/21; AG Fritzlar, Urt. v. 13.01.2022 – 8 C 469/21; AG Mühlheim, Urt. v. 08.02.2022 – 19 C 1292/21; AG München, Urt. v. 21.02.2022 – 423 C 294/22; AG Essen, Urt. v. 11.03.2022 – 20 C 154/21; AG Heidelberg, Urt. v. 22.03.2022 – 25 C 153/21; AG Unna, Urt. v. 08.04.2022 – 15 C 397/21; AG Günzburg, Urt. v. 20.04.2022 – 1 C 691/21; AG St. Wendel, Urt. v. 21.04.2022 – 13 C 221/22; AG St. Wendel, Urt. v. 30.05.2022 – 15 C 308/22; AG Kitzingen, Urt. v. 27.06.2022 – 3 C 485/21; AG Günzburg, Urt. v. 26.07.2022 – 1 C 351/22; AG Coburg, Urt. v. 29.07.2022 – 14 C 2624/22; AG Köln, Urt.v. 08.08.2022 – 133 C 34/22; AG Neuss, Urt. v. 08.08.2022 – 87 C 103/22; AG Hamburg-Harburg, Urt. v. 10.08.2022 – 647 C 110/22; AG Bretten, Urt. v. 30.08.2022 – 1 C 121/22; AG Reinbeck, Urt. v. 26.09.2022 – 15 C 385/22; AG Leipzig, Urt. v. 07.11.2022 – 108 C 2589/22)";

        // Deadline berechnen
        $deadline = Carbon::now()->addWeeks(2)->format('d.m.Y');

        // Aktuelles Datum
        $date = Carbon::now()->format('d.m.Y');

        // Adresse als Objekt erstellen
        $address = (object) [
            'street' => $report->street,
            'zip' => $report->zip,
            'city' => $report->city,
            'country' => $report->country,
            'ownershipRelation' => 'Nutzungsberechigt'
        ];

        // Bilder prüfen
        $images = $report->images()->count() > 0;

        // PDF generieren
        $pdf = PDF::loadView('pdf.warning-letter', compact(
            'report', 
            'user', 
            'address',
            'haltergeschlecht',
            'halter',
            'derdiedas_klein',
            'derdiedas_gross',
            'demder_klein',
            'schuldner',
            'glaubiger',
            'diesemdieser',
            'courtDecisions',
            'deadline',
            'date',
            'images'
        ));

        return $pdf->download('Abmahnung.pdf');
    }
}
