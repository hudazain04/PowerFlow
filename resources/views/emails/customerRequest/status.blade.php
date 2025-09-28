<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subscription Request Status</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; padding: 20px;">
                <tr>
                    <td align="center" style="padding: 10px 0;">
                        <h2 style="color: {{ $status === 'approved' ? '#28a745' : '#dc3545' }};">
                            {{ $status === 'approved' ? '✅ Customer Request Approved' : '❌ Customer Request Rejected' }}
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #333;">
                        @if($status === 'approved')
                            <p>
                                Your request for a counter for generator <strong>{{ $generator_name }}</strong> has been
                                <span style="color:#28a745;">approved</span>. 🎉
                            </p>
                            <p>You can now start using your counter services.</p>
                        @else
                            <p>
                                Your request for a counter for generator <strong>{{ $generator_name }}</strong> has been
                                <span style="color:#dc3545;">rejected</span>.
                            </p>
                            @if($admin_notes)
                                <p><strong>Reason:</strong> {{ $admin_notes }}</p>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px; font-size: 14px; color: #555;">
                        <p>Thanks,</p>
                        <p><strong>PowerFlow Team</strong></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
