<!DOCTYPE html>
<html class="no-js" lang="ja">
<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') . ' - ' . Util::langtext('SHIPMENT_T_003') }}</title>
    <meta name="theme-color" content="#fafafa" />
    <style>
    table, tr, th, td {
        border: solid 1px #000;
        text-align: left;
    }

    table {
        width: 100%;
    }

    table th {
        width: 150px;
    }

    table td {
        width: 500px;
    }

    h1 {
        text-align: center;
    }

    #print-wrapper {
        margin: 0 auto;
        width: 700px;
    }

    @media print {    
        .no-print, .no-print * {
            display: none !important;
        }
    }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print();">{{ Util::langtext('SHIPMENT_L_077') }}</button>
    </div>
    <div id="print-wrapper">
        <h1>{{ Util::langtext('SHIPMENT_T_003') }}</h1>
        <table>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_027') }}</th>
                <td>{{ $data['data_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_028') }}</th>
                <td>{{ $data['data_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_029') }}</th>
                <td>{{ $data['process_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_030') }}</th>
                <td>{{ $data['condition_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_031') }}</th>
                <td>{{ $data['data_create_date_time'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_032') }}</th>
                <td>{{ $data['operator_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_033') }}</th>
                <td>{{ $data['customer_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_034') }}</th>
                <td>{{ $data['customer_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_035') }}</th>
                <td>{{ $data['sale_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_036') }}</th>
                <td>{{ $data['sale_name_kana'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_037') }}</th>
                <td>{!! $data['sale_address'] !!}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_038') }}</th>
                <td>{{ $data['sale_tel'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_039') }}</th>
                <td>{{ $data['destination_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_040') }}</th>
                <td>{{ $data['destination_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_041') }}</th>
                <td>{{ $data['destination_name_kana'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_042') }}</th>
                <td>{!! $data['destination_address'] !!}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_043') }}</th>
                <td>{{ $data['destination_tel'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_044') }}</th>
                <td>{{ $data['kiduke_kanji'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_045') }}</th>
                <td>{{ $data['delivery_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_046') }}</th>
                <td>{{ $data['voucher_remark_a'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_047') }}</th>
                <td>{{ $data['voucher_remark_b'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_048') }}</th>
                <td>{{ $data['order_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_049') }}</th>
                <td>{{ $data['order_line_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_050') }}</th>
                <td>{{ $data['order_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_051') }}</th>
                <td>{{ $data['order_confirm_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_052') }}</th>
                <td>{{ $data['shipment_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_053') }}</th>
                <td>{{ $data['instruction_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_054') }}</th>
                <td>{{ $data['jan_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_055') }}</th>
                <td>{{ $data['item_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_056') }}</th>
                <td>{{ $data['packing_code'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_057') }}</th>
                <td>{{ $data['item_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_058') }}</th>
                <td>{{ $data['item_quantity'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_059') }}</th>
                <td>{{ $data['unit_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_060') }}</th>
                <td>{{ $data['order_price'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_061') }}</th>
                <td>{{ $data['order_amount'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_062') }}</th>
                <td>{{ $data['detail_remarks'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_063') }}</th>
                <td>{{ $data['delivery_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_064') }}</th>
                <td>{{ $data['delivery_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_065') }}</th>
                <td>{{ $data['delivery_type_name'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_066') }}</th>
                <td>{{ $data['answer_delivery'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_067') }}</th>
                <td>{{ $data['answer_delivery_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_068') }}</th>
                <td>{{ $data['answer_delivery_detail'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_069') }}</th>
                <td>{{ $data['price_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_070') }}</th>
                <td>{{ $data['shipping_no'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_071') }}</th>
                <td>{{ $data['item_lot'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_072') }}</th>
                <td>{{ $data['expire_date'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_073') }}</th>
                <td>{{ $data['price_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_074') }}</th>
                <td>{{ $data['reserve_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_075') }}</th>
                <td>{{ $data['voucher_type'] }}</td>
            </tr>
            <tr>
                <th>{{ Util::langtext('SHIPMENT_L_076') }}</th>
                <td>{{ $data['yobi'] }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
