<strong><u>I. Sachverhalt und Tatvorwurf</u></strong><br/>
Laut Auskunft der zuständigen KFZ-Zulassungsstelle, sind Sie {{ $derdiedas_klein }} {{ $halter }} des Fahrzeugs,
mit dem amtlichen Kennzeichen <strong>{{ $report->plateCode1 }}-{{ $report->plateCode2 }}-{{ $report->plateCode3 }}</strong>.
<br/><br/>
Dieses Fahrzeug war am, <strong>{{ date("j.n.Y", strtotime($report->date)) }} um {{ date("H:i:s", strtotime($report->date)) }} Uhr</strong> unberechtigt auf dem Parkplatz meiner
Mandantschaft, <strong>{{ $address->street }}, {{ $address->zip }} {{ $address->city }}</strong>, abgestellt. @if($images) (Siehe Fotos der Anlage)@endif
<br/>
Darüber hinaus steht noch ein Zeuge für den Parkverstoß zur Verfügung.<br/><br/>
Der Parkplatz ist eindeutig als Privatparkplatz gekennzeichnet. @if($images) (Siehe Fotos der Anlage)@endif
<br/><br/>
Meine Mandantschaft ist <strong>{{ $address->ownershipRelation ?: 'Nutzungsberechigt' }}</strong> des Parkplatzes.
<br/><br/>

<strong><u>II. Rechtslage</u></strong><br/>
@include('pdf.warning-letter.partials.legal-text')

<strong><u>III. weiterer Verfahrensablauf</u></strong><br/>
@include('pdf.warning-letter.partials.procedure')

<strong><u>IV. Abschließende Hinweise</u></strong><br/>
@include('pdf.warning-letter.partials.final-notes')

<br/><br/>
Mit freundlichen Grüßen<br/>
<img src="images/broecker.jpeg" alt="" style="width: 250px;" />
<br/>

<span class="footer">Jan Bröcker <br/>-  Rechtsanwalt -</span>
<br/><br/>
Bankverbindung: <br/>
Kreissparkasse Steinfurt<br/> 
IBAN DE31 4035 1060 0074 3711 39 - BIC WELADED1STF
