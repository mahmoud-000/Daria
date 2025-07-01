<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'REPAIR') }}</title>
    <style>
        body,
        html {
            background-color: black;
            color: whitesmoke
        }
    </style>
</head>
<table>
    <tbody>
        @foreach ($tests as $test)
            @php
                $details = json_decode(json_encode($test->details))
            @endphp
            
            <b>Item : {{ $details->item->name }}<br />
            <b>Variant : {{ !empty($details->variant) ? $details->variant->name : '' }}<br />
            <hr>
        @endforeach
    </tbody>
</table>

<body>



</body>

</html>
