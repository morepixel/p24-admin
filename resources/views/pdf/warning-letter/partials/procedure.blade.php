Bevor meine Mandantschaft Unterlassungsklage gegen Sie erhebt, gebe ich Ihnen die Möglichkeit zur außergerichtlichen Erledigung der Angelegenheit.
<br/><br/>
<strong>1.</strong><br/>
Sie können die Begehungs- und Wiederholungsgefahr sowie das Rechtsschutzbedürfnis zur Erhebung einer gerichtlichen Klage allerdings nur dadurch beseitigen, dass Sie eine <u>strafbewehrte</u> Unterlassungs- und Verpflichtungserklärung abgeben.
<br/><br/>
Strafbewehrt bedeutet, dass sie für den Fall, dass sie ihr Fahrzeug doch noch einmal dort abstellen, die Zahlung einer Vertragsstrafe an meine Mandantschaft versprechen.
Eine Erklärung ohne diese Passage ist nach herrschender Rechtsprechung nicht ausreichend (siehe BGH, Urt. v. 21.09.2021 – V ZR 230/11; AG Essen, Urt. v. 18.02.2022 – 20 C 154/21; AG Bad Oeynhausen, Urt. v. 18.03.2022 – 24 C 524/21).
<br/><br/>
Es versteht sich insofern von selbst, dass es in gar keinem Fall genügt, dass Fahrzeug nicht erneut dort abzustellen, sondern es zwingend einer ausreichend strafbewehrten Unterlassungserklärung bedarf.
<br/><br/>
Diesem Schreiben ist eine entsprechende Unterlassungserklärung beigefügt. Diese können Sie gerne verwenden.
<br/><br/>
Sollten nicht Sie, sondern ein Dritter das Fahrzeug auf dem Parkplatz meiner Mandantschaft abgestellt haben, steht es Ihnen selbstverständlich frei, den verantwortlichen Fahrer mit ladungsfähiger Anschrift hierher zu benennen.<br/>
Die Ansprüche würden sodann gegen den Fahrer geltend gemacht werden.
Die Benennung steht sogar ausdrücklich im Interesse meiner Mandantschaft, da diese von dem verantwortlichen Fahrer, neben den bezifferten Rechtsanwaltskosten, auch noch die Kosten für die Halterermittlung in Höhe von 5,10 € erstattet bekommen würde.
<br/><br/>
Für die Abgabe der strafbewehrten Unterlassungserklärung oder die textliche Benennung des verantwortlichen Fahrers zum streitgegenständlichen Zeitpunkt, setze ich Ihnen eine Frist bis
<br/><br/>
<strong class="text-center">{{ $deadline }}, 12:00 Uhr</strong>
<br/><br/>
Sollte ich innerhalb der gesetzten Frist <u>keinen Eingang</u> verzeichnen können, wird meine Mandantschaft ihre Ansprüche gerichtlich gegen Sie durchsetzen.
<br/><br/>
Die Benennung des verantwortlichen Fahrers per E-Mail ist ausreichend. Eine Mitteilung per Telefon ist allerdings nicht möglich.
<br/><br/>
<strong>2.</strong><br/>
Über den Unterlassungsanspruch hinaus, stehen meiner Mandantschaft auch Schadensersatzansprüche gemäß §§ 683, 677, 670 BGB bzw. §§ 823 Abs. 2, 858 BGB gegen Sie zu. (BGH, a.a.O.)
<br/><br/>
Im Rahmen dieser Ansprüche kann meine Mandantschaft von Ihnen die Erstattung der Kosten meiner Inanspruchnahme verlangen.
<br/><br/>

Diese Kosten beziffere ich wie folgt:
<br/>

<table>
    <tr>
        <td class="tleft">Gegenstandswert:</td>
        <td class="tright">1500,00 €</td>
    </tr>
    <tr>
        <td>1,3 Geschäftsgebühr gem. Nr. 2300 VV RVG</td>
        <td class="tright">165,10 €</td>
    </tr>
    <tr>
        <td>Auslagenpauschale gem. Nr. 7002 VV RVG</td>
        <td class="tright">20,00 €</td>
    </tr>
    <tr class="underline">
        <td>Nettobetrag @if($taxInfo == '1') / Gesamtsumme@endif</td>
        <td class="tright">185,10 €</td>
    </tr>
    @if($taxInfo != '1')
    <tr>
        <td>19 % Umsatzsteuer gem. Nr. 7008 VV RVG</td>
        <td class="tright">35,17 €</td>
    </tr>	
    <tr class="underline">
        <td><strong>Gesamtsumme </strong></td>
        <td class="tright"><strong>220,27 €</strong></td>
    </tr>
    @endif
</table>

<br/><br/>
@php
$betrag = $taxInfo == '1' ? "185.10" : "220.27";
$usage = "P24-". $report->id;
$url = "https://dev.matthiasschaffer.com/bezahlcode/api.php?iban=DE31403510600074371139&bic=WELADED1STF&name=Jan Broecker&usage=".$usage."&amount=".$betrag;
@endphp
<img src="{{ $url }}" alt="Bezahlcode" width="150px" style="float:right"/>

Den Eingang dieses Betrages erwarte ich spätestens bis zum <strong>{{ $deadline }}</strong>
<br/>
auf meine angegebene Kontoverbindung <strong>(Kreissparkasse Steinfurt, IBAN DE31 4035 1060 0074 3711 39)</strong>, falls Sie den <br/>verantwortlichen Fahrer nicht innerhalb der ersten Frist benannt haben.
<br/><br/>
Als Verwendungszweck geben Sie bitte an: <strong>P24-{{ $report->id }}<br/></strong>
<br/>
