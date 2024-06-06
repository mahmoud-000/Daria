<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test</title>

    <style>
        .wrapper-pdf {
            padding: 10px;
            /* font-weight: bold; */
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
            border: 2px solid #000;
        }

        .br-10 {
            border-radius: 10px;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="wrapper-pdf">
        {{-- Date & Serial  --}}
        <div class="w-100 mt-2">
            <div class="left w-80 m-auto">
                <div class="pb-1">{{ $certificate->date }}</div>
                <div class="pb-1">شهادة تركيب كاميرات مراقبة</div>
                <div class="pb-1">{{ $certificate->serial_number }}</div>
            </div>
        </div>

        {{-- Headet --}}
        <div class="w-100 mt-2">
            <div class="center m-auto w-80">
                <div class="pb-1">تشهد مؤسسة منزل الشبكات للإتصالات وتقنية المعلومات والمرخص لها بمزاولة نشاط</div>
                <div class="pb-1">تركيب وبيع الأنظمة الأمنية لموجب ترخيص الهيئة العليا للأمن الصناعى رقم</div>
                <div class="pb-1">23506092878 - 23505093273</div>
                <div class="pb-1">بأنها قامت بتركيبوتفعيل نظام كاميرات مراقبة للمنشأة أدناه وبالمواصفات التالية:
                </div>
            </div>
        </div>

        {{-- Facility Table --}}
        <div class="w-100 mt-2">
            <div class="b-3 br-10 w-80 m-auto pa-1 left">
                <span>معلومات المنشأة </span>
            </div>
            <div class="b-3 br-10 right m-auto w-80 pa-1">
                <table class="w-100">
                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">أسم المنشأة: </span><span>{{ $certificate->facility_name }}</span>
                        </td>
                    </tr>
                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">نشاط المنشأة: </span>{{ $certificate->facility_activity }}
                        </td>
                        <td class="pb-1">
                            <span class="bold">رقم الجوال: </span>{{ $certificate->mobile }}
                        </td>
                    </tr>
                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">السجل التجارى:
                            </span><span>{{ $certificate->commercial_register }}</span>
                        </td>
                    </tr>

                    <tr class="w-100">
                        <td>
                            <span class="bold">
                                عنوان المنشأة:
                            </span>
                            <span>
                                {{ $certificate->no_civil_registry }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Cameras Table --}}
        <div class="w-100 mt-2">
            <div class="b-3 br-10 w-80 m-auto pa-1 left">
                <span>معلومات الكاميرات </span>
            </div>

            <div class="b-3 br-10 right m-auto w-80 pa-1">
                <table class="w-100">
                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">الكاميرات الداخلية: </span>
                            <span>{{ $certificate->internal_cameras }}</span>
                        </td>
                    </tr>

                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">الكاميرات الخارجية: </span>
                            <span>{{ $certificate->external_cameras }}</span>
                        </td>
                    </tr>

                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">جهاز التسجيل: </span>{{ $certificate->recording_device }}
                        </td>
                        <td class="pb-1">
                            <span class="bold">قرص التخزين: </span>{{ $certificate->storage_disk }}
                        </td>
                    </tr>

                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">مدة التسجيل: </span>{{ $certificate->recording_duration }}
                        </td>
                        <td class="pb-1">
                            <span class="bold">شاشة العرض: </span>{{ $certificate->display }}
                        </td>
                    </tr>

                    <tr class="w-100">
                        <td class="pb-1">
                            <span class="bold">مواصفات أخرى: </span>
                            <span>{{ $certificate->other_specifications }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="w-100 mt-3">
            <div class="left w-70 m-auto">
                <p>الختم والتوقيع </p>
            </div>
        </div>
    </div>
</body>

</html>
