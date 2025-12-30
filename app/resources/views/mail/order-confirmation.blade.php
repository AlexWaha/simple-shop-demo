@extends('mail.layouts.base', ['title' => 'Order Confirmation'])

@section('content')
    <h2 style="margin: 0 0 8px; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
        Thank you for your order!
    </h2>
    <p style="margin: 0 0 32px; color: #64748b; font-size: 15px; text-align: center;">
        Your order has been received and is being processed.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 24px;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%">
                            <p style="margin: 0; color: #64748b; font-size: 13px;">Order Number</p>
                            <p style="margin: 4px 0 0; color: #1e293b; font-size: 16px; font-weight: 600;">#{{ $order->order_number }}</p>
                        </td>
                        <td width="50%" align="right">
                            <p style="margin: 0; color: #64748b; font-size: 13px;">Order Date</p>
                            <p style="margin: 4px 0 0; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $order->created_at->format('M d, Y') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h3 style="margin: 0 0 16px; color: #1e293b; font-size: 16px; font-weight: 600; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px;">
        Order Items
    </h3>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
        @foreach($order->items as $item)
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p style="margin: 0; color: #1e293b; font-size: 15px; font-weight: 500;">{{ $item->product_name }}</p>
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Qty: {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                        </td>
                        <td align="right" style="vertical-align: middle;">
                            <p style="margin: 0; color: #1e293b; font-size: 15px; font-weight: 600;">${{ number_format($item->total, 2) }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #1e293b; border-radius: 8px;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p style="margin: 0; color: #94a3b8; font-size: 14px;">Order Total</p>
                        </td>
                        <td align="right">
                            <p style="margin: 0; color: white; font-size: 24px; font-weight: 700;">${{ number_format($order->total, 2) }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ url('/orders/' . $order->order_number) }}" style="display: inline-block; background-color: #3b82f6; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
            View Order Details
        </a>
    </div>
@endsection
