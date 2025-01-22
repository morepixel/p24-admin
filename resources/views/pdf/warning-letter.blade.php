@extends('pdf.warning-letter.layout')

@section('content')
    @include('pdf.warning-letter.header')
    
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

            @include('pdf.warning-letter.content')
            @include('pdf.warning-letter.attachments')
        </p>
    </div>
@endsection
