<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page
        {

        }
        body
        {
            font-family: sans-serif;
            font-size: 12px;
        }

        table
        {
            width: 100%;
        }

        table.receipts th, table.receipts td
        {
            padding: 3px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: top;
            line-height: 24px;
        }

        table.receipts
        {
            border-collapse: collapse;
        }

        table.receipts tfoot
        {
            margin-top: 10px;
        }

        .text-muted {
            color: #6c757d !important;
        }

    </style>
</head>
<body style="position: relative;">
    <!--mpdf
        <htmlpageheader name="page-header">

            <table width="100%" autosize="1" style="margin: 0px 0;">
                <tr>
                    <td width="100%">
                        <img src="{{ Storage::disk('public')->url('receipt-top.png') }}" width="100%" border="1">
                    </td>
                </tr>
            </table>

            <table width="100%" autosize="1">
                <tbody>
                    <tr>
                        <td width="70%" style="vertical-align: top;" autosize="1">
                            <table width="100%" autosize="1">
                                <tr>
                                    <td style="font-size: 9px; margin-bottom: 10px;">Cardo Gesundheit | Westorfer Straße 11 | 32689 Kalletal</td>
                                </tr>
                                <tr>
                                    <td style="line-height: 1.5; margin-top: 10px;">{!! nl2br(e($receipt->address)) !!}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="30%">

                            <table width="100%" autosize="1">
                                <thead>
                                    <tr>
                                        <th colspan="2" width="80%" style="text-align: left;"><strong>Rechnung {{ $receipt->name }}</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Datum</td>
                                        <td style="text-align: right;">{{ $receipt->date->format('d.m.Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fällig</td>
                                        <td style="text-align: right;">{{ $receipt->date_due->format('d.m.Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Seite</td>
                                        <td style="text-align: right;">{PAGENO} von {nb}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                </tbody>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="page-footer">
            <table width="100%" style="border-top: 1px solid #EAEAEA; color: #fff; background-color: #000; margin-top: 20px; padding: 10px 0; font-size: 8px;">
                <thead>
                    <tr style="font-weight: 700;">
                        <td align="center" width="22%">Cardo Gesundheit</td>
                        <td align="center" width="43%" rowspan="5"><img src="{{ Storage::disk('public')->url('receipt-logo.png') }}" width="75" /></td>
                        <td align="center" width="22%">Kontakt & Infos:</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">Juliette Rolf</td>

                        <td align="center">01515/2175674</td>
                    </tr>
                    <tr>
                        <td align="center">Westorfer Straße 11</td>

                        <td align="center">Festnetz</td>
                    </tr>
                    <tr>
                        <td align="center">32689 Kalletal</td>

                        <td align="center">info@cardo-gesundheit.de</td>
                    </tr>
                    <tr>
                        <td align="center">329/5084/3453</td>

                        <td align="center">www.cardo-gesundheit.de</td>
                    </tr>
                </tbody>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="page-footer" value="on" />

        <sethtmlpageheader name="page-header" value="on" show-this-page="1" />
    mpdf-->

    @if ($receipt->subject)
        <p><b>{{ $receipt->subject }}</b></p>
    @endif

    @if($receipt->text_above)
        <p style="min-height: 10px;">{!! $receipt->text_above !!}</p>
    @endif

    @if(count($receipt->lines))
        <table class="receipts" width="100%" style="" autosize="1">
            <thead style="">
                <tr style="">
                    <th width="25%" style="text-align: left;">Beschreibung</th>
                    <th width="15%" style="text-align: right;">Menge</th>
                    <th width="15%" style="text-align: left;">Einheit</th>
                    <th width="15%" style="text-align: right;">Preis</th>
                    @if($show_tax)
                        <th width="15%" style="text-align: right;">USt.</th>
                    @endif
                    <th width="15%" style="text-align: right;">Betrag</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->lines as $line)
                    <tr style="{{ $line->description ? 'border: none;' : '' }}">
                        <td style="">{{ $line->name }}</td>
                        <td style="text-align: right;">{{ number_format($line->quantity, 2, ',', '.') }}</td>
                        <td style="">{{ $line->unit_id ? $line->unit->name : '' }}</td>
                        <td style="text-align: right;">{{ number_format($line->unit_price, 2, ',', '.') }} €</td>
                        @if($show_tax)
                            <td style="text-align: right;">{{ number_format($line->tax * 100, 2, ',', '.') }}%</td>
                        @endif
                        <td style="text-align: right;">{{ number_format($line->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                    @if($line->description)
                        <tr>
                            <td colspan="{{ $show_tax ? '6' : '5' }}"><div class="text-muted">{!! nl2br(e($line->description)) !!}</div></td>
                        </tr>
                    @endif
                @endforeach

            </tbody>
            <tfoot>
                <tr style="height: 10px;">
                    <td colspan="{{ $show_tax ? '6' : '5' }}"></td>
                </tr>
                @if($show_tax)
                    <tr>
                        <td style="">Zwischensumme</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                    @foreach($receipt->tax as $tax)
                        <tr style="">
                            <td>Ust.</td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">{{ number_format($tax['net'] / 100, 2, ',', '.') }} €</td>
                            <td style="text-align: right;">{{ $tax['tax'] * 100 }} %</td>
                            <td style="text-align: right;">{{ number_format($tax['value'] / 100, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                    <tr style="">
                        <td style="">Bruttosumme</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->gross / 100, 2, ',', '.') }} €</td>
                    </tr>
                @else
                    <tr style="">
                        <td style="">Gesamt</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    @endif

    @if($receipt->text)
        <p>{!! $receipt->text !!}</p>
    @endif

    @if($receipt->text_below)
        <p>{!! $receipt->text_below !!}</p>
    @endif
</body>
</html>