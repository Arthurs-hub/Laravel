@props(['type', 'id', 'title', 'inline' => false])

@if($inline)
    <!-- Inline buttons for cards -->
    <button type="button" class="btn btn-outline-primary btn-xs me-1 review-view-btn" data-type="{{ $type }}"
        data-id="{{ $id }}" data-title="{{ $title }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
        <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
        {{ __('reviews.view_reviews_short') }}
    </button>

    <button type="button" class="btn btn-outline-success btn-xs review-leave-btn" data-type="{{ $type }}"
        data-id="{{ $id }}" data-title="{{ $title }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
        <i class="fas fa-star" style="font-size: 0.7rem;"></i>
        {{ __('reviews.leave_review_short') }}
    </button>
@else
    <!-- Regular buttons -->
    <div class="review-buttons-container mt-3">
        <button type="button" class="btn btn-outline-primary btn-sm me-2 review-view-btn" data-type="{{ $type }}"
            data-id="{{ $id }}" data-title="{{ $title }}">
            <i class="fas fa-eye me-1"></i>
            {{ __('reviews.view_reviews') }}
        </button>

        <button type="button" class="btn btn-outline-success btn-sm review-leave-btn" data-type="{{ $type }}"
            data-id="{{ $id }}" data-title="{{ $title }}">
            <i class="fas fa-star me-1"></i>
            {{ __('reviews.leave_review') }}
        </button>
    </div>
@endif

<!-- View Reviews Modal -->
<div class="modal fade" id="viewReviewsModal" tabindex="-1" aria-labelledby="viewReviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReviewsModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    {{ __('reviews.view_reviews') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reviewsContainer">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Review Modal -->
<div class="modal fade" id="leaveReviewModal" tabindex="-1" aria-labelledby="leaveReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveReviewModalLabel">
                    <i class="fas fa-star me-2"></i>
                    {{ __('reviews.leave_review') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    @csrf
                    <input type="hidden" id="reviewType" name="type">
                    <input type="hidden" id="reviewId" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold" id="reviewObjectLabel"></label>
                    </div>

                    <div class="mb-3">
                        <label for="reviewRating" class="form-label">{{ __('reviews.rating') }}</label>
                        <div class="rating-input">
                            <input type="radio" name="rating" value="5" id="star5">
                            <label for="star5" title="5 stars">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4" title="4 stars">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3" title="3 stars">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2" title="2 stars">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1" title="1 star">★</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reviewContent" class="form-label">{{ __('reviews.review_content') }}</label>
                        <textarea class="form-control" id="reviewContent" name="content" rows="4"
                            placeholder="{{ __('reviews.write_review') }}" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('reviews.cancel') }}
                </button>
                <button type="button" class="btn btn-success" id="submitReviewBtn">
                    <i class="fas fa-paper-plane me-1"></i>
                    {{ __('reviews.submit') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .review-buttons-container {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .rating-input input[type="radio"] {
        display: none;
    }

    .rating-input label {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-input label:hover,
    .rating-input label:hover~label,
    .rating-input input[type="radio"]:checked~label {
        color: #ffc107;
    }

    .review-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-rating {
        color: #ffc107;
        font-size: 16px;
    }

    .review-date {
        color: #6c757d;
        font-size: 0.9em;
    }

    .review-content {
        margin-top: 8px;
        line-height: 1.5;
    }

    .no-reviews {
        text-align: center;
        color: #6c757d;
        padding: 40px 20px;
    }

    .no-reviews i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
</style>

<!-- JavaScript functionality is handled by review-modals.blade.php component -->