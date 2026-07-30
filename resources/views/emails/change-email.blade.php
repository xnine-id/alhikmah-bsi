<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('emails.verify_otp_email_subject') }}</title>
</head>

<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
        <h2 style="color: #333; text-align: center;">{{ __('emails.verify_otp_email_subject') }}</h2>

        <p>{{ __('emails.hi') }} {{ $name }},</p>

        <p>{{ __('emails.verify_otp_email_opening') }}:</p>

        <div
            style="background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px; margin: 20px 0;">
            <h1 style="margin: 0; font-size: 36px; letter-spacing: 10px;">{{ $otp }}</h1>
        </div>

        <p><strong>{{ __('emails.verify_otp_email_duration') }}</strong></p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #666; text-align: center;">
            {{ __('emails.verify_otp_email_dont_reply') }}
        </p>
    </div>
</body>

</html>