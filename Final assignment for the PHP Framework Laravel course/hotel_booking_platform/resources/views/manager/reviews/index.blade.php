@extends('layouts.app')

@section('title', __('reviews.my_hotels_reviews'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                            <h3 class="card-title mb-2 mb-sm-0">
                                <i class="fas fa-star mr-2"></i>
                                {{ __('reviews.my_hotels_reviews') }}
                            </h3>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    {{ __('admin.back_to_dashboard') }}
                                </a>
                                <span class="badge bg-info">
                                    {{ __('reviews.total_reviews') }}: {{ $reviews->total() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($hotels->count() > 0)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>{{ __('reviews.my_hotels') }}:</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($hotels as $hotel)
                                            <span class="badge bg-primary p-2">
                                                <i class="fas fa-hotel me-1"></i>
                                                {{ $hotel->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($reviews->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ __('reviews.user') }}</th>
                                            <th>{{ __('reviews.reviewed_object') }}</th>
                                            <th>{{ __('reviews.rating') }}</th>
                                            <th>{{ __('reviews.review_content') }}</th>
                                            <th>{{ __('reviews.status') }}</th>
                                            <th>{{ __('reviews.created_at') }}</th>
                                            <th>{{ __('reviews.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reviews as $review)
                                            <tr class="{{ $review->is_approved ? 'table-success' : 'table-warning' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($review->user->avatar)
                                                            <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                                                alt="{{ $review->user->full_name }}" class="rounded-circle me-2"
                                                                referrerpolicy="no-referrer"
                                                                width="32" height="32">
                                                        @else
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                                style="width: 32px; height: 32px;">
                                                                <span class="text-white fw-bold">
                                                                    {{ strtoupper(substr($review->user->full_name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ $review->user->full_name }}</div>
                                                            <small class="text-muted">{{ $review->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">
                                                        @if($review->reviewable_type === 'App\Models\Hotel')
                                                            <i class="fas fa-hotel text-primary me-1"></i>
                                                            {{ $review->reviewable->title }}
                                                        @else
                                                            <i class="fas fa-bed text-info me-1"></i>
                                                            {{ \App\Helpers\TranslationHelper::translateRoomType($review->reviewable->type) }}
                                                            <small class="text-muted d-block">
                                                                {{ $review->reviewable->hotel->title }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($review->rating)
                                                        <div class="d-flex align-items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                            <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">{{ __('reviews.no_rating') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="review-content" style="max-width: 300px;">
                                                        {{ Str::limit($review->content, 100) }}
                                                        @if(strlen($review->content) > 100)
                                                            <button class="btn btn-link btn-sm p-0 ms-1"
                                                                onclick="toggleFullContent(this)"
                                                                data-full-content="{{ $review->content }}">
                                                                {{ __('reviews.show_more') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($review->is_approved)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>
                                                            {{ __('reviews.approved') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ __('reviews.pending') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $review->created_at->format('d.m.Y') }}</div>
                                                    <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(!$review->is_approved)
                                                            <form action="{{ route('manager.reviews.approve', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                    title="{{ __('reviews.approve') }}">
                                                                    <i class="fas fa-check"></i>
                                                                    {{ __('reviews.approve') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('manager.reviews.destroy', $review->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('{{ __('reviews.confirm_delete') }}')"
                                                                title="{{ __('reviews.delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                                {{ __('reviews.delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $reviews->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">{{ __('reviews.no_reviews') }}</h4>
                                <p class="text-muted">{{ __('reviews.no_reviews_for_hotels') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFullContent(button) {
            const contentDiv = button.closest('.review-content');
            const fullContent = button.getAttribute('data-full-content');
            const isExpanded = button.textContent.trim() === '{{ __('reviews.show_less') }}';

            if (isExpanded) {
                contentDiv.innerHTML = fullContent.substring(0, 100) + '... <button class="btn btn-link btn-sm p-0 ms-1" onclick="toggleFullContent(this)" data-full-content="' + fullContent + '">{{ __('reviews.show_more') }}</button>';
            } else {
                contentDiv.innerHTML = fullContent + ' <button class="btn btn-link btn-sm p-0 ms-1" onclick="toggleFullContent(this)" data-full-content="' + fullContent + '">{{ __('reviews.show_less') }}</button>';
            }
        }
    </script>
@endsection