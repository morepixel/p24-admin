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
    @yield('content')
</body>
</html>
