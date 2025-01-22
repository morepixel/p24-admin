@if($courtDecisions)
<div class="smallfont" style="font-size: 12px;">
    {{ $courtDecisions }}
</div>
@endif

<div class="fonts">
    <br/><br/>
    Das Amtsgericht Detmold bemisst den Streitwert in solchen Angelegenheiten mit 2.000,00 € (<strong>AG Detmold</strong>, Urt. v. 14.09.2022 – 40 C 62/22).
    <br/><br/>
    Das Amtsgericht Mönchengladbach-Rheydt, Urt. v. 06.02.2019 – 10 C 417/18 und das Amtsgericht Tempelhof-Kreuzberg, Urt. v. 16.11.2020 – 7 C 104/20 haben den Streitwert sogar auf 3.000,00 € festgesetzt und daraus eine 1,3 Geschäftsgebühr zugesprochen.
    <br/><br/>
    Es ist zu berücksichtigen, dass das rechtliche Interesse nicht auf die Eintreibung von eventuellen Parkgebühren oder Mietzinsen gerichtet ist, sondern auf die Sicherstellung künftiger Unterlassung weiterer Besitzstörungen.<br/> 
    Dieses Interesse bemesse sich der Höhe nach dann nicht allein an möglichen Parkgebühren oder möglichen Kosten für die Beseitigung einer einmaligen künftigen Störung, sondern an dem Interesse der dauerhaften Sicherung des eigenen Besitzes. <br/>
    Vor diesem Hintergrund sei ein Kostenansatz von 1.500,00 €, insbesondere mit Blick darauf, dass der Auffangstreitwert gemäß § 23 Abs. 3 RVG sogar 5.000,00 € betrage, moderat und angemessen (AG Gifhorn, a.a.O.; AG Ansbach, a.a.O., AG Koblenz, a.a.O.; AG Pforzheim, a.a.O.; AG Braunschweig a.a.O.).
    <br/><br/>

    Einige der oben zitierten Gerichtsentscheidungen können Sie hier nachlesen: <br/><br/><strong>https://www.myparkplatz24.de/urteile/</strong>

    <br/><br/>
    Die abweichende Höhe der hier geltend gemachten Rechtsanwaltskosten ergibt sich daraus, dass zum einen seit dem 01.01.2021 wieder eine Umsatzsteuer von 19% gilt und die Rechtsanwaltsgebühren zum 01.01.2021 gesetzlich um ca. 10 % erhöht wurden.<br/>
    Dies können Sie auch jeder aktuellen Gebührentabelle zum RVG 2021 im Internet entnehmen.
</div>

<h2>Strafbewehrte Unterlassungs- und Verpflichtungserklärung</h2>
<br/>
<p>
Von<br/>
{{ $report->halterName ?: 'Max Mustermann' }}<br/>
{{ $report->halterStrasse ?: 'Musterstrasse' }}<br/>
{{ $report->halterPLZ ?: '12345' }} {{ $report->halterOrt ?: 'Musterdorf' }}<br/>
Deutschland
<br/><br/>

{{ "- ". $unterlassungsschuldner. " -" }}<br/><br/>

gegenüber<br/>
<br/>
@if($report->companyName)
    @if(in_array($user->companyForm, ['E.K.', 'GbR']))
        {{ $report->companyName }}, vertreten durch {{ $user->firstname }} {{ $user->lastname }}
    @else
        {{ $report->companyName }}
    @endif
@else
    {{ $report->mandant }}
@endif
<br/>
{{ $report->street }}, <br/>{{ $report->zip }} {{ $report->city }}
<br/>
vertreten durch den Prozessbevollmächtigten,<br/>
Rechtsanwalt Jan Bröcker,  Wiesenstr. 15, 49205 Hasbergen<br/>
<br/>

{{ "- ". $unterlassungsglaubiger. " -" }}<br/>
<br/>

1. {{ $derdiedas_gross }} {{ $schuldner }} verpflichtet sich gegenüber {{ $demder_klein }} {{ $glaubiger }}, es bei Meidung einer für jeden Fall der Zuwiderhandlung fällig werdenden Vertragsstrafe, deren Höhe von {{ $demder_klein }} {{ $glaubiger }} nach billigem Ermessen bestimmt wird und im Streitfall vom zuständigen Gericht überprüft werden kann, zu unterlassen, ihr Fahrzeug auf dem Parkplatz {{ $demder_klein }} {{ $glaubiger }}, <strong>{{ $address->street }}, {{ $address->zip }} {{ $address->city }}</strong> abzustellen oder durch Dritte abstellen zu lassen, es sei denn, diese hat der Benutzung vorher ausdrücklich zugestimmt.
<br/><br/>

2. {{ $derdiedas_gross }} {{ $schuldner }} verpflichtet sich gegenüber {{ $demder_klein }} {{ $glaubiger }}, {{ $diesemdieser }} die Kosten der Inanspruchnahme des Rechtsanwalts Jan Bröcker, in Höhe von 
@if($taxInfo != '1')220,27 €@else 185,10 €@endif zu erstatten.

<br/><br/>
Diese Erklärung ist zu unterschreiben und im Original an folgende Adresse zu senden:
Rechtsanwalt Jan Bröcker, Wiesenstr. 15, 49205 Hasbergen.
<br/><br/>
Zur Wahrung der Frist ist es ausreichend, dass diese Erklärung vorab per Fax an: 
0 54 05 / 89 52 22 29 oder per E-Mail an myparkplatz24@ra-broecker.de gesendet wird, sofern das Original zeitnah per Post folgt.
</p>
<br/><br/>

<br/><br/>
<br/><br/><br/>

_____________________________<br/>
Unterschrift / Datum

@if($images)
<br/><br/>
Bilder: <br/><br/>
@foreach($images as $img)
    <img src="{{ $img->url }}" alt="" width="350" style="float:left; margin-right:10px;filter: grayscale(1);"/><br/>
@endforeach
@endif
