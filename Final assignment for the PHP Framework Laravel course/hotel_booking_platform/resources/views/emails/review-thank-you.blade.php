<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('reviews.email_subject') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .review-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        .rating {
            color: #ffc107;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('reviews.email_thank_you_title') }}</h1>
    </div>

    <div class="content">
        <p>{{ __('reviews.email_greeting', ['name' => $review->user->full_name ?? $review->user->name ?? __('reviews.dear_user')]) }}
        </p>

        <p>{{ __('reviews.email_thank_you_message') }}</p>

        <div class="review-box">
            <h3>{{ __('reviews.your_review') }}</h3>
            <p><strong>{{ __('reviews.reviewed_object') }}:</strong>
                @if($reviewable instanceof App\Models\Hotel)
                    {{ $reviewable->title ?? 'Hotel' }}
                @elseif($reviewable instanceof App\Models\Room)
                    {{ $reviewable->title ?? 'Room' }}
                    @if(isset($reviewable->hotel) && $reviewable->hotel)
                        ({{ $reviewable->hotel->title ?? 'Hotel' }})
                    @endif
                @else
                    {{ __('reviews.reviewed_item') }}
                @endif
            </p>

            @if($review->rating)
                <p><strong>{{ __('reviews.rating') }}:</strong>
                    <span class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    ({{ $review->rating }}/5)
                </p>
            @endif

            <p><strong>{{ __('reviews.review_content') }}:</strong></p>
            <p style="font-style: italic; background: #f1f3f4; padding: 15px; border-radius: 5px;">
                "{{ $review->content }}"
            </p>
        </div>

        @purchasedAt
        <div class="time-info">
            <p><strong>{{ __('email.review_date') }}:</strong>
                {{ \Carbon\Carbon::parse($review->created_at)->timezone(auth()->user()->timezone)->format('d.m.Y H:i') }}
            </p>
        </div>
        @endPurchasedAt
        <p>{{ __('reviews.email_moderation_notice') }}</p>

        <p>{{ __('reviews.email_closing') }}</p>
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} - {{ __('reviews.email_footer') }}</p>
    </div>
</body>

</html>