<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test</title>
    <style>
        body,
        html {
            font-family: "tajwal";
            background-color: "#e6e6e6";
        }

        .wrapper-pdf {
            padding: 10px;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .m-auto {
            margin: auto;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .mt-3 {
            margin-top: 30px;
        }

        .w-100 {
            width: 100%;
        }

        .w-80 {
            width: 80%;
        }

        .w-70 {
            width: 70%;
        }

        .w-50 {
            width: 50%;
        }

        .w-20 {
            width: 20%;
        }

        .inline {
            display: inline-block;
        }

        .pa-1 {
            padding: 10px;
        }

        .pb-1 {
            padding-bottom: 10px;
        }

        .b-3 {
            border: 2px solid #9b9b9a;
        }

        .bb-3 {
            border-width: 2px 1px 0px 1px;
            border-color: #9b9b9a;
            border-style: solid;
        }

        .br-10 {
            border-radius: 15px;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        .bold {
            font-weight: bold;
        }

        .float-right {
            float: right
        }
    </style>
</head>

<body>
    <div class="wrapper-pdf">
        <div class="w-100 mt-2">
            <table class="w-100">
                <tr class="w-100">
                    <td class="pb-1">
                        <div class="left w-80">
                            <div class="pb-1 bold">Created at: {{ $ticket->created_at->format('Y-m-d') }}</div>
                            <div class="pb-1 bold">Closed at: {{ $ticket->closed_at }}</div>
                            <div class="pb-1 bold">Ticket created by {{ $ticket->creatable->username }} </div>
                            <div class="pb-1 bold">S.N: {{ $ticket->serial_number }}</div>
                        </div>

                    </td>
                    <td class="pb-1 right">
                        @if ($logo)
                        @php
                            $url = explode('/', $logo->getUrl('system_logo'));
                        @endphp 
                            <q-img class="" style="height: 100px; width: 100px;"
                                src="storage/{{ $logo->id . '/conversions/' . end($url) }}" />
                        @endif
                    </td>
                </tr>
            </table>
        </div>

    </div>

    {{-- Service Info --}}
    <div class="w-100 mt-2 b-3 br-10">
        <div class="w-100 m-auto pa-1 left">
            <span class="bold">Service Information </span>
        </div>
        <div class="bb-3 br-10 left m-auto w-100 pa-1">
            <table class="w-100">
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Name: </span>
                        <span>{{ $ticket->service->name }}</span>
                    </td>
                </tr>
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Price: </span>
                        <span>$ {{ $ticket->service_price }}</span>
                    </td>
                    <td class="pb-1">
                        <span class="bold">Deadline: </span>
                        <span>{{ $ticket->service_deadline }} Days</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Device Info --}}
    <div class="w-100 mt-2 b-3 br-10">
        <div class="w-100 m-auto pa-1 left">
            <span class="bold">Device Information </span>
        </div>
        <div class="bb-3 br-10 left m-auto w-100 pa-1">
            <table class="w-100">
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Serial Number: </span>
                        <span>{{ $ticket->device_serial_number }}</span>
                    </td>
                    <td class="pb-1">
                        <span class="bold">Code: </span>
                        <span>{{ $ticket->device_code }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Details Info --}}
    <div class="w-100 mt-2 b-3 br-10">
        <div class="w-100 m-auto pa-1 left">
            <span class="bold">Details </span>
        </div>
        <div class="bb-3 br-10 left m-auto w-100 pa-1">
            <table class="w-100">
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Deadline: </span>
                        <span>{{ $ticket->deadline }}</span>
                    </td>
                    <td class="pb-1">
                        <span class="bold">Tracking Number: </span>
                        <span>{{ $ticket->tracking_number }}</span>
                    </td>
                </tr>
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Description: </span>
                        <span>{!! $ticket->details !!}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Details RMA Info --}}
    <div class="w-100 mt-2 b-3 br-10">
        <div class="w-100 m-auto pa-1 left">
            <span class="bold">Details (RMA)</span>
        </div>
        <div class="bb-3 br-10 left m-auto w-100 pa-1">
            <table class="w-100">
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">RMA at: </span>
                        <span>{{ $ticket->rma_at }}</span>
                    </td>
                    <td class="pb-1">
                        <span class="bold">Deadline: </span>
                        <span>{{ $ticket->deadline_rma ?? 'N/D' }}</span>
                    </td>
                    <td class="pb-1">
                        <span class="bold">Tracking Number: </span>
                        <span>{{ $ticket->tracking_number_rma ?? 'N/D' }}</span>
                    </td>
                </tr>
                <tr class="w-100">
                    <td class="pb-1">
                        <span class="bold">Description: </span>
                        <span>{!! $ticket->details_rma ?? 'N/D' !!}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
