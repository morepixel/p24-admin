<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unterlassungsaufforderung</title>
    <style>
        @font-face {
            font-family: 'PT Mono';
            font-style: normal;
            font-weight: 400;
            src: url('/fonts/pt-mono-v13-latin-regular.eot');
            src: local(''),
                url('/fonts/pt-mono-v13-latin-regular.eot?#iefix') format('embedded-opentype'),
                url('/fonts/pt-mono-v13-latin-regular.woff2') format('woff2'),
                url('/fonts/pt-mono-v13-latin-regular.woff') format('woff'),
                url('/fonts/pt-mono-v13-latin-regular.ttf') format('truetype'),
                url('/fonts/pt-mono-v13-latin-regular.svg#PTMono') format('svg');
        }
        body {
            font-family: 'PT Mono';
            font-size: 18px;
            margin-left: 50px;
            margin-right: 50px;
        }
        .datum {
            float: right;
            margin-right: 135px;
            font-size: 18px;
        }
        .tleft {
            width: 60%;
        }
        .tright {
            width: 40%;
            text-align: right;
            padding: 10px;
        }
        .underline td {
            border-bottom: 1px solid #000;
        }
        p {
            line-height: 24px;
            font-size: 16px;
        }
        .absender {
            font-size: 15px;
        }
        .firma {
            float: right;
            page-break-before: always;
        }
        .logo {
            text-align: center;
            width: 100%;
            display: block;
            font-size: 32px;
            padding-right: 50px;
        }
        .text-center {
            text-align: center;
            display: block;
        }
        .para {
            background: transparent url('./images/Text-Paragraph-icon.png') no-repeat left top / 380px;
            position: absolute;
            right: 140px;
            height: 380px;
            width: 280px;
            top: 20px;
            display: block;
            opacity: 0.1;
        }
        .smallfont {
            display: inline;
        }
    </style>
</head>
<body>
    <div>
        <span class="logo"><strong>Jan Bröcker</strong> <br/> Rechtsanwalt</span>
        <span class="para"></span>
        <span class="firma">
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
        {{ ($report->halterPLZ ?: '12345') . ' ' . ($report->halterOrt ?: 'Musterdorf') }}<br/>
        Deutschland<br/>
        <br/><br/>
        <br/><br/>
        
        <span class="datum">
            {{ $report->zahlungsziel ?: now()->format('d.m.Y') }}
        </span>
        
        <br/>
        Aktenzeichen: {{ ucwords($report->mandant) }} ./. {{ ucwords($report->halterName ?: 'Max Mustermann') }}<br/>
        Unser Zeichen: P24-{{ $report->id }}<br/>
        
        <h2>Unterlassungsaufforderung</h2>
        
        <p>
            {{ $report->halterGeschlecht == '1' ? 'Sehr geehrter Herr' : ($report->halterGeschlecht == '2' ? 'Sehr geehrte Frau' : ($report->halterGeschlecht == '3' ? 'Sehr geehrte Damen und Herren' : '')) }}
            {{ $report->halterName ?: 'Max Mustermann' }},<br/>
            <br/>
            in vorbezeichneter Angelegenheit zeige ich an, dass ich 
            <strong>{{ $report->companyName ? ucwords($report->companyName) . ', vertreten durch ' . ucwords($report->user->firstname) . ' ' . ucwords($report->user->lastname) : ucwords($report->mandant) }}</strong>
            vertrete.<br/>
            Eine Vollmachtsurkunde ist diesem Schreiben beigefügt.
        </p>

        <!-- Rest des Inhalts hier -->
        
    </div>
</body>
</html>
