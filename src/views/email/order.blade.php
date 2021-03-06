<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Đơn Đặt Hàng</title>
    <meta name="viewport" content="width=device-width"/>
    <style type="text/css">
        @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .buttonwrapper { background-color: transparent !important; }
            body[yahoo] .button { padding: 0 !important; }
            body[yahoo] .button a { background-color: #1ec1b8; padding: 15px 25px !important; }
        }

        @media only screen and (min-device-width: 601px) {
            .content { width: 600px !important; }
            .col387 { width: 387px !important; }
        }
    </style>
</head>
<body bgcolor="#32323a" style="margin: 0; padding: 0;" yahoo="fix">
<!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
<![endif]-->
<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 800px;" class="content">
  <tr>
      <td align="center" colspan="2" bgcolor="#dddddd" style="padding: 15px 10px 15px 10px; color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 18px;">
          <b>ĐƠN ĐẶT HÀNG</b>
      </td>
  </tr>
<tr>
    <td colspan="2" bgcolor="#ffffff" style="padding: 10px 10px 20px 10px; color: #555555; font-family: Arial, sans-serif; font-size: 16px; line-height: 30px; width: 150px;">
        <b>Thông tin đơn hàng</b>
        <table>
            <tbody>
                <tr>
                    <td>Mã đơn hàng:</td>
                    <td>#{{ $order->order_id }}</td>
                </tr>
                <tr>
                    <td>Mã giảm giá:</td>
                    <td>{{ $order->discount_code }}</td>
                </tr>
                <tr>
                    <td>Giảm giá:</td>
                    <td>{{ number_format($order->discount_amount, 0, '.', ',' ) }}</td>
                </tr>
                <tr>
                    <td>Giá trị đơn hàng:</td>
                    <td>{{ number_format($order->total, 0, '.', ',' ) }}</td>
                </tr>
            </tbody>
        </table>
    </td>

</tr>
<tr>
    <td bgcolor="#ffffff" colspan="2" style="padding: 10px 10px 20px 10px; color: #555555; font-family: Arial, sans-serif; font-size: 16px; line-height: 30px;">
        <b>Địa chỉ giao hàng</b>
        <table>
            <tbody>
                <tr>
                    <td style="width: 20%">Họ và tên:</td>
                    <td>{{ $order->customer->name }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Số điện thoại:</td>
                    <td>{{ $order->shipping->phone }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Địa chỉ:</td>
                    <td>
                        {{ $order->shipping->address . ', '.$order->shipping->wards->name .', '. $order->shipping->districts->name . ', '. $order->shipping->provinces->name }}
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
    <tr>
        <td align="center" colspan="2" bgcolor="#dddddd" style="padding: 15px 10px 15px 10px; color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 18px;">
            <b>Ruounhapkhau.com - Mua rượu online - Rượu vang và quà tặng giao đến tận nhà</b>
        </td>
    </tr>
</table>
<!--[if (gte mso 9)|(IE)]>
</td>
</tr>
</table>
<![endif]-->
</body>
</html>
