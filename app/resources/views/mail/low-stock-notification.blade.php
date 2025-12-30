@extends('mail.layouts.base', ['title' => 'Low Stock Alert'])

@section('content')
    <h2 style="margin: 0 0 8px; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
        Low Stock Alert
    </h2>
    <p style="margin: 0 0 32px; color: #64748b; font-size: 15px; text-align: center;">
        {{ $products->count() }} product(s) in your inventory need attention.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
        <tr>
            <td style="padding: 12px 16px; background-color: #fef3c7; border-radius: 8px 8px 0 0;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Product</td>
                        <td align="center" width="80" style="color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Stock</td>
                        <td align="center" width="80" style="color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Status</td>
                    </tr>
                </table>
            </td>
        </tr>
        @foreach($products as $product)
        <tr>
            <td style="padding: 16px; border-bottom: 1px solid #f1f5f9; {{ $loop->last ? 'border-radius: 0 0 8px 8px;' : '' }} background-color: #fffbeb;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <a href="{{ url('/dashboard/products/' . $product->id . '/edit') }}" style="margin: 0; color: #2563eb; font-size: 15px; font-weight: 600; text-decoration: underline;">{{ $product->name }}</a>
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">${{ number_format($product->price, 2) }}</p>
                        </td>
                        <td align="center" width="80">
                            <span style="display: inline-block; padding: 4px 12px; background-color: #fde68a; color: #92400e; border-radius: 12px; font-size: 14px; font-weight: 700;">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td align="center" width="80">
                            <span style="color: #d97706; font-size: 12px; font-weight: 600;">LOW</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fef3c7; border-radius: 8px; margin-bottom: 24px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <p style="margin: 0; color: #d97706; font-size: 32px; font-weight: 700;">{{ $products->count() }}</p>
                <p style="margin: 4px 0 0; color: #92400e; font-size: 14px;">Product(s) Running Low</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 8px;">
        <tr>
            <td style="padding: 20px;">
                <p style="margin: 0; color: #1e293b; font-size: 14px;">
                    <strong>Recommendation:</strong> Please restock these products soon to avoid missing potential sales. The stock threshold is set to {{ config('shop.low_stock_threshold', 5) }} units.
                </p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ url('/dashboard/products') }}" style="display: inline-block; background-color: #f59e0b; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
            Manage Products
        </a>
    </div>
@endsection
