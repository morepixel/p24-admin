<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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

        .fonts {
            font-family: 'PT Mono';
            font-size: 16px;
            line-height: 24px;
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

        .footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
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
        
        <h2>Unterlassungsaufforderung</h2>
        
        <p>
            {{ $haltergeschlecht }} {{ $report->halterName ?: 'Max Mustermann' }},<br/>
            <br/>
            in vorbezeichneter Angelegenheit zeige ich an, dass ich 
            <strong>{{ $report->companyName ? $report->companyName . ', vertreten durch ' . $user->firstname . ' ' . $user->lastname : $report->mandant }}, 
            {{ $user->street }}, {{ $user->zip }} {{ $user->city }}</strong> vertrete.<br/>
            Eine Vollmachtsurkunde ist diesem Schreiben beigefügt.
            <br/><br/>

            <!-- Rest of the content follows exactly as in your template -->
            
        </p>
    </div>
</body>
</html>
