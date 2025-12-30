<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f5;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">

                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 30px 40px; border-radius: 12px 12px 0 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <h1 style="margin: 0; color: white; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">ToyShop</h1>
                                        <p style="margin: 4px 0 0; color: rgba(255,255,255,0.85); font-size: 13px;">Best toys for your kids</p>
                                    </td>
                                    <td align="right" style="vertical-align: middle;">
                                        <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 13px;">{{ now()->format('M d, Y') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #1e293b; padding: 30px 40px; border-radius: 0 0 12px 12px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <p style="margin: 0; color: #94a3b8; font-size: 14px;">
                                            Questions? Contact us at
                                            <a href="mailto:support@toyshop.local" style="color: #60a5fa; text-decoration: none;">support@toyshop.local</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0; color: #64748b; font-size: 12px;">
                                            {{ date('Y') }} ToyShop. All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
