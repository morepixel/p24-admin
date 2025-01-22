<div>
    <span class="logo"><strong>Jan Bröcker</strong> <br/> Rechtsanwalt</span>
    <span class="para"></span>
    <span class="firma">
        <br/><br/><br/>
        Wiesenstr. 15 <br/>
        49205 Hasbergen<br/><br/>
        Tel. 0 54 05 / 89 52 22 8<br/>
        myparkplatz24@ra-broecker.de<br/>
        www.ra-broecker.de
    </span>
    <br/><br/><br/><br/><br/>
    <span class="absender">Rechtsanwalt Jan Bröcker, Wiesenstr. 15, 49205 Hasbergen</span><br/>
    
    {{ $report->halterName ?: 'Max Mustermann' }}<br/>
    {{ $report->halterStrasse ?: 'Musterstrasse' }}<br/>
    {{ $report->halterPLZ ?: '12345' }} {{ $report->halterOrt ?: 'Musterdorf' }}<br/>
    Deutschland<br/>
    <br/><br/>

    <br/><br/>
    
    <span class="datum">
        {{ $date }}
    </span>
    
    <br/>
    Aktenzeichen: {{ $report->mandant }} ./. {{ $report->halterName ?: 'Max Mustermann' }}<br/>
    Unser Zeichen: P24-{{ $report->id }}<br/>
</div>
