<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('email.check_in_reminder') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .btn {
            background: #f5576c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin: 20px 0;
        }

        .highlight {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏨 {{ __('email.check_in_reminder') }}</h1>
        </div>
        <div class="content">
            <p>{{ __('email.hello') }}, {{ $booking->user->full_name }}!</p>

            <div class="highlight">
                <h3>⏰ {{ __('email.your_check_in_is_tomorrow') }}</h3>
                <p>{{ __('email.reminder_check_in_date', ['date' => \Carbon\Carbon::parse($booking->started_at)->format('d.m.Y')]) }}</p>
            </div>

            <div class="booking-details">
                <h3>📋 {{ __('email.booking_details') }}</h3>
                <div class="detail-row">
                    <strong>{{ __('email.hotel') }}:</strong>
                    <span>{{ $booking->room->hotel->title }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.address') }}:</strong>
                    <span>{{ $booking->room->hotel->address }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.room') }}:</strong>
                    <span>{{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.check_in') }}:</strong>
                    <span>{{ \Carbon\Carbon::parse($booking->started_at)->format('d.m.Y') }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.check_out') }}:</strong>
                    <span>{{ \Carbon\Carbon::parse($booking->finished_at)->format('d.m.Y') }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.number_of_nights') }}:</strong>
                    <span>{{ $booking->days }}</span>
                </div>
            </div>

            <div class="highlight">
                <h4>📝 {{ __('email.recommendations') }}:</h4>
                <ul>
                    <li>{{ __('email.bring_id') }}</li>
                    <li>{{ __('email.standard_check_in_time') }}</li>
                    <li>{{ __('email.contact_hotel_for_early_check_in') }}</li>
                </ul>
            </div>

            <p>{{ __('email.wish_you_a_pleasant_stay') }} {{ __('email.contact_us_if_questions') }}</p>

            <a href="{{ route('bookings.show', $booking) }}" class="btn">{{ __('email.view_booking') }}</a>
        </div>
    </div>
</body>

</html>