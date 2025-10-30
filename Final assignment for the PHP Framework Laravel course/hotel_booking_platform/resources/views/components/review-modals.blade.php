<!-- View Reviews Modal -->
<div class="modal fade" id="viewReviewsModal" tabindex="-1" aria-labelledby="viewReviewsModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-lg" style="max-width: 95vw; margin: 0.5rem auto; max-height: calc(100vh - 1rem);">
        <div class="modal-content"
            style="background-color: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); border-radius: 1rem; height: auto; max-height: calc(100vh - 1rem);">
            <div class="modal-header"
                style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;">
                <h5 class="modal-title" id="viewReviewsModalLabel" style="font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-eye me-2"></i>
                    {{ __('reviews.view_reviews') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="font-size: 1.2rem;"></button>
            </div>
            <div class="modal-body" style="padding: 1rem; max-height: calc(100vh - 8rem); overflow-y: auto; flex: 1;">
                <div id="reviewsContainer">
                    <div class="text-center" style="padding: 2rem;">
                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Review Modal -->
<div class="modal fade" id="leaveReviewModal" tabindex="-1" aria-labelledby="leaveReviewModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog" style="max-width: 95vw; margin: 0.5rem auto; max-height: calc(100vh - 1rem);">
        <div class="modal-content"
            style="background-color: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); border-radius: 1rem; height: auto; max-height: calc(100vh - 1rem); display: flex; flex-direction: column;">
            <div class="modal-header"
                style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;">
                <h5 class="modal-title" id="leaveReviewModalLabel" style="font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-star me-2"></i>
                    {{ __('reviews.leave_review') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="font-size: 1.2rem;"></button>
            </div>
            <div class="modal-body" style="padding: 1rem; overflow-y: auto; flex: 1;">
                <form id="reviewForm">
                    @csrf
                    <input type="hidden" id="reviewType" name="type">
                    <input type="hidden" id="reviewId" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold" id="reviewObjectLabel"></label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('reviews.rating') }}</label>
                        <div class="rating-input d-flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="rating-star" style="cursor: pointer; font-size: 1.5rem; color: #ddd;">
                                    <input type="radio" name="rating" value="{{ $i }}" style="display: none;">
                                    <span>★</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reviewContent" class="form-label">{{ __('reviews.review_content') }}</label>
                        <textarea class="form-control" id="reviewContent" name="content" rows="4"
                            placeholder="{{ __('reviews.write_review') }}" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer"
                style="padding: 1rem 1.5rem; border-top: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    {{ __('reviews.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="submitReviewBtn">
                    <i class="fas fa-paper-plane me-1"></i>
                    {{ __('reviews.submit') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .review-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-rating {
        color: #ffc107;
        font-size: 1.1rem;
    }

    .review-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .review-content {
        margin-top: 8px;
        line-height: 1.5;
    }

    .rating-star:hover,
    .rating-star:hover~.rating-star {
        color: #ffc107 !important;
    }

    .rating-star input:checked~span,
    .rating-star input:checked~.rating-star span {
        color: #ffc107 !important;
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

    /* Адаптивные стили для модальных окон */
    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 95vw !important;
            margin: 0.5rem auto !important;
        }

        .modal-content {
            border-radius: 0.75rem !important;
            min-height: 60vh !important;
        }

        .modal-header {
            padding: 0.75rem 1rem !important;
        }

        .modal-body {
            padding: 1rem !important;
            max-height: 75vh !important;
        }

        .modal-title {
            font-size: 1.1rem !important;
        }

        .review-item {
            padding: 10px 0 !important;
        }

        .review-rating {
            font-size: 1rem !important;
        }

        .review-date {
            font-size: 0.8rem !important;
        }

        .no-reviews {
            padding: 20px 10px !important;
        }

        .no-reviews i {
            font-size: 36px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // View Reviews functionality
        document.querySelectorAll('.review-view-btn').forEach(button => {
            button.addEventListener('click', function () {
                const type = this.dataset.type;
                const id = this.dataset.id;
                const title = this.dataset.title;

                document.getElementById('viewReviewsModalLabel').innerHTML =
                    '<i class="fas fa-eye me-2"></i>' +
                    '{{ __("reviews.view_reviews") }} - ' + title;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('viewReviewsModal'));
                modal.show();

                // Load reviews
                loadReviews(type, id);
            });
        });

        // Leave Review functionality
        document.querySelectorAll('.review-leave-btn').forEach(button => {
            button.addEventListener('click', function () {
                const type = this.dataset.type;
                const id = this.dataset.id;
                const title = this.dataset.title;

                document.getElementById('reviewType').value = type;
                document.getElementById('reviewId').value = id;
                document.getElementById('reviewObjectLabel').textContent = title;

                // Reset form
                document.getElementById('reviewForm').reset();
                document.querySelectorAll('.rating-input input[type="radio"]').forEach(input => {
                    input.checked = false;
                });
                document.querySelectorAll('.rating-star').forEach(star => {
                    star.style.color = '#ddd';
                });

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('leaveReviewModal'));
                modal.show();
            });
        });

        // Rating stars functionality
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            star.addEventListener('click', function () {
                const rating = index + 1;
                const input = this.querySelector('input');
                input.checked = true;

                // Update visual state
                document.querySelectorAll('.rating-star').forEach((s, i) => {
                    if (i < rating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });

        // Submit Review functionality
        document.getElementById('submitReviewBtn').addEventListener('click', function () {
            const form = document.getElementById('reviewForm');
            const formData = new FormData(form);
            const type = document.getElementById('reviewType').value;
            const id = document.getElementById('reviewId').value;

            // Disable button
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("reviews.submit") }}';

            // Determine the correct route based on type
            let url;
            const baseUrlMeta = document.querySelector('meta[name="base-url"]');
            const origin = baseUrlMeta ? baseUrlMeta.getAttribute('content') : window.location.origin;
            if (type === 'hotel') {
                url = `${origin}/hotels/${id}/reviews`;
            } else if (type === 'room') {
                url = `${origin}/rooms/${id}/reviews`;
            }

            console.log('Submitting review to:', url);
            console.log('Form data:', Object.fromEntries(formData));

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    console.log('Submit response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Submit response data:', data);
                    if (data.success) {
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('leaveReviewModal')).hide();

                        // Show success message
                        showAlert('success', data.message);
                    } else {
                        showAlert('danger', data.error || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error submitting review:', error);
                    showAlert('danger', 'An error occurred while submitting your review: ' + error.message);
                })
                .finally(() => {
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-paper-plane me-1"></i>{{ __("reviews.submit") }}';
                });
        });

        function loadReviews(type, id) {
            const container = document.getElementById('reviewsContainer');
            container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            // Determine the correct route based on type
            let url;
            const baseUrlMeta = document.querySelector('meta[name="base-url"]');
            const origin = baseUrlMeta ? baseUrlMeta.getAttribute('content') : window.location.origin;
            if (type === 'hotel') {
                url = `${origin}/hotels/${id}/reviews`;
            } else if (type === 'room') {
                url = `${origin}/rooms/${id}/reviews`;
            }

            console.log('Loading reviews from:', url);

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Reviews data:', data);
                    if (data.reviews && data.reviews.length > 0) {
                        let html = '';
                        data.reviews.forEach(review => {
                            html += `
                            <div class="review-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong>${review.user_name}</strong>
                                    <div class="text-end">
                                        ${review.rating ? `<div class="review-rating">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</div>` : ''}
                                        <div class="review-date">${review.created_at}</div>
                                    </div>
                                </div>
                                <div class="review-content">${review.content}</div>
                            </div>
                        `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                        <div class="no-reviews">
                            <i class="fas fa-comments"></i>
                            <h5>{{ __('reviews.no_reviews') }}</h5>
                            <p>{{ __('reviews.be_first_to_review') }}</p>
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error loading reviews: ' + error.message + '</div>';
                });
        }

        function showAlert(type, message) {
            // Create a fixed positioned alert that appears at the top center
            const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed"
                 style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                 role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            // Add to body
            const alertDiv = document.createElement('div');
            alertDiv.innerHTML = alertHtml;
            document.body.appendChild(alertDiv.firstElementChild);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert.position-fixed');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    });
</script>