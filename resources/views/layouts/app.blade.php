<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @font-face {
            font-family: helvetica neue w20_45 light;
            src: url('fonts/e2b0b793-a7b7-4648-9fd2-e2c7dcef8b12.woff2') format("woff2");
        }

        @font-face {
            font-family: helvetica neue w20_55 roman;
            src: url('fonts/a3bbc98e-e5f0-45ec-af25-e05e49dc2b5b.woff2') format("woff2");
        }

        @font-face {
            font-family: helvetica neue w20_75 bold;
            src: url('fonts/33f381a3-597b-47c9-a038-ca7df1af0523.woff2') format("woff2");
        }
        
        body {
            font-family: helvetica neue w20_45 light!important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
</head>
<body>

<div class="flex flex-col h-screen justify-between">
@livewire('nav')

    {{ $slot }}

@livewire('footer')
</div>
@livewireScripts
</body>
</html>
