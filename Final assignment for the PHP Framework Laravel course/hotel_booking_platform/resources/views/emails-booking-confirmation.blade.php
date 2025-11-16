<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('email.booking_confirmation') }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: black;
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
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏨 {{ $booking->wasRecentlyCreated ? __('email.booking_confirmation') : __('email.booking_update') }}
            </h1>
        </div>
        <div class="content">
            <p>{{ __('email.hello') }}, {{ $booking->user->full_name }}!</p>
            <p>{{ $booking->wasRecentlyCreated ? __('email.your_booking_is_confirmed') : __('email.your_booking_is_updated') }}
                {{ __('email.booking_details_below') }}
            </p>

            <div class="booking-details">
                <h3>📋 {{ __('email.booking_details') }}</h3>
                <div class="detail-row">
                    <strong>{{ __('email.hotel') }}:</strong>
                    <span>{{ $booking->room->hotel->title }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.room') }}:</strong>
                    <span>{{ \App\Helpers\TranslationHelper::translateRoomTitle($booking->room->title) }}</span>
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
                    <strong>{{ __('email.nights') }}:</strong>
                    <span>{{ $booking->days }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.guests') }}:</strong>
                    <span>{{ $booking->adults }} {{ __('email.adults') }}, {{ $booking->children }}
                        {{ __('email.children') }}</span>
                </div>
                <div class="detail-row">
                    <strong>{{ __('email.total_cost') }}:</strong>
                    <span><strong>{{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}</strong></span>
                </div>
            </div>

            <p>{{ __('email.we_are_waiting_for_you') }}
                {{ \Carbon\Carbon::parse($booking->started_at)->format('d.m.Y') }}!
                {{ __('email.contact_us_if_questions') }}
            </p>

            <a href="{{ route('bookings.show', $booking) }}" class="btn">{{ __('email.view_booking') }}</a>
        </div>
    </div>
</body>

</html>