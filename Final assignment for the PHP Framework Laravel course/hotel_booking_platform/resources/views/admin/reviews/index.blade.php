@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('admin.back_to_dashboard') }}
                </a>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    {{ __('reviews.reviews_management') }}
                </h1>
            </div>
        </div>



        @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: auto; min-width: 200px;">
                                {{ __('reviews.user') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('reviews.reviewed_object') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('reviews.rating') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('reviews.review_content') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('reviews.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('reviews.created_at') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reviews as $review)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap" style="width: auto; min-width: 200px;">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="flex-shrink-0 h-10 w-10 mb-2">
                                            @if($review->user->avatar)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ asset('storage/' . $review->user->avatar) }}"
                                                     referrerpolicy="no-referrer"
                                                     alt="{{ $review->user->full_name }}">
                                            @else
                                                <img class="h-10 w-10 rounded-full"
                                                     src="https://ui-avatars.com/api/?name={{ urlencode($review->user->full_name) }}&color=7F9CF5&background=EBF4FF"
                                                     referrerpolicy="no-referrer"
                                                     alt="{{ $review->user->full_name }}">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 mb-1">
                                                {{ $review->user->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 break-all">
                                                {{ $review->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($review->reviewable_type === 'App\\Models\\Hotel')
                                            <i class="fas fa-hotel text-blue-500 mr-1"></i>
                                            {{ $review->reviewable->title }}
                                        @elseif($review->reviewable_type === 'App\\Models\\Room')
                                            <i class="fas fa-bed text-green-500 mr-1"></i>
                                            {{ $review->reviewable->hotel->title }} - {{ $review->reviewable->title }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($review->rating)
                                        <div class="flex items-center">
                                            <div class="text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">{{ __('reviews.no_rating') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        <div class="review-content-{{ $review->id }}">
                                            @if(strlen($review->content) > 100)
                                                <span class="short-content">{{ Str::limit($review->content, 100) }}</span>
                                                <span class="full-content hidden">{{ $review->content }}</span>
                                                <button class="text-blue-600 hover:text-blue-800 text-xs ml-1 show-more-btn" 
                                                        onclick="toggleContent({{ $review->id }})">
                                                    {{ __('reviews.show_more') }}
                                                </button>
                                            @else
                                                {{ $review->content }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($review->is_approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ __('reviews.approved') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ __('reviews.pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $review->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col space-y-1">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded text-xs text-left">
                                                    <i class="fas fa-check mr-1"></i>
                                                    {{ __('reviews.approve') }}
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline"
                                              onsubmit="return confirm('{{ __('reviews.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded text-xs text-left">
                                                <i class="fas fa-trash mr-1"></i>
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-comments text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('reviews.no_reviews') }}</h3>
                <p class="text-gray-500">{{ __('reviews.no_reviews_yet') }}</p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleContent(reviewId) {
    const container = document.querySelector(`.review-content-${reviewId}`);
    const shortContent = container.querySelector('.short-content');
    const fullContent = container.querySelector('.full-content');
    const button = container.querySelector('.show-more-btn');
    
    if (fullContent.classList.contains('hidden')) {
        shortContent.classList.add('hidden');
        fullContent.classList.remove('hidden');
        button.textContent = '{{ __("reviews.show_less") }}';
    } else {
        shortContent.classList.remove('hidden');
        fullContent.classList.add('hidden');
        button.textContent = '{{ __("reviews.show_more") }}';
    }
}
</script>
@endsection
