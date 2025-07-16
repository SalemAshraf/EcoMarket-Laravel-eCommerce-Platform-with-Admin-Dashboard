<!DOCTYPE html>
<html lang="en" style="margin:0; padding:0;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #ffffff; color: #000000;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="padding: 40px 0; background-color: #ffffff;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
                    style="border: 1px solid #000000; border-radius: 8px;">
                    <tr>
                        <td style="background-color: #000000; color: #ffffff; padding: 20px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px;">🛒 Order Placed Successfully</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px;">Hello <strong>{{$order->user->name}}</strong>,</p>
                            <p style="font-size: 16px;">Thank you for your order! We're processing it now and will
                                notify you when it's shipped.</p>

                            <hr style="border: none; border-top: 1px solid #000000; margin: 20px 0;">

                            <h3 style="margin-bottom: 5px;">🧾 Order Details</h3>
                            <p style="font-size: 14px; margin: 5px 0;"><strong>Order Number:</strong>
                                {{"1" . str_pad($order->id, 4, "0", STR_PAD_LEFT)}}</p>
                            <p style="font-size: 14px; margin: 5px 0;"><strong>Order Date:</strong>
                                {{$order->created_at}}</p>
                            <p style="font-size: 14px; margin: 5px 0;"><strong>Total:</strong> {{$order->total}}</p>

                            <hr style="border: none; border-top: 1px solid #000000; margin: 20px 0;">

                            <p style="font-size: 16px;">You can view your order or track its status by clicking the
                                button below:</p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{route('user.acccount.order.details', ['order_id' => $order->id])}}"
                                    style="display: inline-block; padding: 12px 24px; background-color: #000000; color: #ffffff; text-decoration: none; border-radius: 4px;">View
                                    Order</a>
                            </p>

                            <p style="font-size: 14px; color: #666666;">If you have any questions, reply to this email
                                or contact our support team.</p>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background-color: #f6f6f6; text-align: center; padding: 20px; font-size: 12px; color: #888888;">
                            &copy; 2025 Ego Fashion. All rights reserved.<br>
                            123 Style St., Cairo, Egypt
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
