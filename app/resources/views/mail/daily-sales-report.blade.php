@extends('mail.layouts.base', ['title' => 'Daily Sales Report'])

@section('content')
    <h2 style="margin: 0 0 8px; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
        Daily Sales Report
    </h2>
    <p style="margin: 0 0 32px; color: #64748b; font-size: 15px; text-align: center;">
        {{ $date->format('l, F d, Y') }}
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
        <tr>
            <td width="50%" style="padding-right: 8px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f0fdf4; border-radius: 8px;">
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <p style="margin: 0; color: #16a34a; font-size: 32px; font-weight: 700;">${{ number_format($totalRevenue, 2) }}</p>
                            <p style="margin: 8px 0 0; color: #15803d; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Revenue</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="padding-left: 8px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #eff6ff; border-radius: 8px;">
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <p style="margin: 0; color: #2563eb; font-size: 32px; font-weight: 700;">{{ $totalOrders }}</p>
                            <p style="margin: 8px 0 0; color: #1d4ed8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Orders</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($productsSold->isNotEmpty())
    <h3 style="margin: 0 0 16px; color: #1e293b; font-size: 16px; font-weight: 600; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px;">
        Products Sold Today
    </h3>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
        <tr>
            <td style="padding: 12px 0; border-bottom: 2px solid #e2e8f0;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Product</td>
                        <td align="center" style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Qty</td>
                        <td align="right" style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Revenue</td>
                    </tr>
                </table>
            </td>
        </tr>
        @foreach($productsSold as $product)
        <tr>
            <td style="padding: 14px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="color: #1e293b; font-size: 15px; font-weight: 500;">{{ $product['name'] }}</td>
                        <td align="center" style="color: #64748b; font-size: 15px;">{{ $product['quantity'] }}</td>
                        <td align="right" style="color: #16a34a; font-size: 15px; font-weight: 600;">${{ number_format($product['revenue'], 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
    </table>
    @else
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 24px;">
        <tr>
            <td style="padding: 32px; text-align: center;">
                <p style="margin: 0; color: #64748b; font-size: 15px;">No products were sold today.</p>
            </td>
        </tr>
    </table>
    @endif

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ url('/dashboard') }}" style="display: inline-block; background-color: #3b82f6; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
            View Dashboard
        </a>
    </div>
@endsection
