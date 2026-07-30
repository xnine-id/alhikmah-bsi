<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('emails.reset_password_subject') }}</title>
</head>

<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
        <h2 style="color: #333; text-align: center;">{{ __('emails.reset_password_subject') }}</h2>

        <p>{{ __('emails.hi') }} {{ $name }},</p>

        <p>{{ __('emails.reset_password_opening') }}:</p>

        <a href="{{ $url }}?token={{ $token }}" target="_blank" style="margin-inline: auto; display: block; background-color: #007bff; color: white; border: none; max-width:fit-content; text-decoration: none; padding: 10px 20px; border-radius: 5px;">
            {{__('emails.reset_password_button')}}
        </a>

        <p><strong>{{__('emails.reset_password_duration')}}</strong></p>

        <p>{{ __('emails.reset_password_not_request') }}</p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #666; text-align: center;">
            {{ __('emails.reset_password_dont_reply') }}
        </p>
    </div>
</body>

</html>