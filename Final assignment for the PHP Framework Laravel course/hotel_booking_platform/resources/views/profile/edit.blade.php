@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    👤 {{ __('profile.my_profile') }}</h1>
                <p class="text-slate-600 text-lg mt-2">{{ __('profile.manage_personal_info') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Info -->
                <div class="lg:col-span-2 space-y-8">
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                        <h2 class="text-2xl font-bold text-slate-800 mb-8 flex items-center">
                            <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-4"></div>
                            {{ __('profile.personal_information') }}
                        </h2>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="flex items-start space-x-8">
                                <div class="shrink-0">
                                    @if($user->avatar)
                                        <img class="h-20 w-20 object-cover rounded-full"
                                            src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                                    @else
                                        <div class="h-20 w-20 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-gray-600 text-2xl">{{ substr($user->full_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.profile_photo') }}</label>

                                    <!-- Кастомная кнопка выбора файла -->
                                    <div class="relative">
                                        <input type="file" id="avatar-input" name="avatar" accept="image/*"
                                            onchange="showCropper(this); updateFileName(this);"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                            class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-full text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                                            {{ __('profile.choose_file') }}
                                        </button>
                                        <span id="file-name"
                                            class="ml-3 text-sm text-gray-500">{{ __('profile.no_file_chosen') }}</span>
                                    </div>

                                    <input type="hidden" id="cropped-avatar" name="cropped_avatar">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('profile.max_file_size') }}</p>
                                </div>
                            </div>

                            <!-- Модальное окно для обрезки -->
                            <div id="crop-modal"
                                class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                                <div class="flex items-center justify-center min-h-screen w-full">
                                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                                        <h3 class="text-lg font-semibold mb-4">{{ __('profile.crop_photo') }}</h3>
                                        <div class="crop-container mb-4">
                                            <img id="crop-image" style="max-width: 100%; height: 300px; object-fit: contain;">
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="closeCropper()"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">{{ __('profile.cancel') }}</button>
                                            <button type="button" onclick="cropAndSave()"
                                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('profile.save') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
                            <link rel="stylesheet"
                                href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

                            <script>
                                let cropper;

                                function showCropper(input) {
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function (e) {
                                            const cropImage = document.getElementById('crop-image');
                                            cropImage.src = e.target.result;
                                            document.getElementById('crop-modal').classList.remove('hidden');

                                            if (cropper) {
                                                cropper.destroy();
                                            }

                                            cropper = new Cropper(cropImage, {
                                                aspectRatio: 1,
                                                viewMode: 2,
                                                autoCropArea: 0.8,
                                                responsive: true,
                                                background: false
                                            });
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }

                                function closeCropper() {
                                    document.getElementById('crop-modal').classList.add('hidden');
                                    document.getElementById('avatar-input').value = '';
                                    if (cropper) {
                                        cropper.destroy();
                                    }
                                }

                                function cropAndSave() {
                                    if (cropper) {
                                        const canvas = cropper.getCroppedCanvas({
                                            width: 200,
                                            height: 200
                                        });

                                        const croppedDataURL = canvas.toDataURL('image/jpeg', 0.8);
                                        document.getElementById('cropped-avatar').value = croppedDataURL;

                                        // Обновляем превью
                                        const preview = document.querySelector('.h-20.w-20');
                                        if (preview.tagName === 'IMG') {
                                            preview.src = croppedDataURL;
                                        } else {
                                            const img = document.createElement('img');
                                            img.className = 'h-20 w-20 object-cover rounded-full';
                                            img.src = croppedDataURL;
                                            preview.replaceWith(img);
                                        }

                                        closeCropper();
                                    }
                                }

                                function updateFileName(input) {
                                    const fileNameSpan = document.getElementById('file-name');
                                    if (input.files && input.files[0]) {
                                        fileNameSpan.textContent = input.files[0].name;
                                    } else {
                                        fileNameSpan.textContent = '{{ __('profile.no_file_chosen') }}';
                                    }
                                }
                            </script>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="full_name"
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.full_name') }}</label>
                                    <input id="full_name" type="text" name="full_name"
                                        value="{{ old('full_name', $user->full_name) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.phone') }}</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="country"
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.country') }}</label>
                                    <input id="country" type="text" name="country"
                                        value="{{ old('country', $user->country) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.city') }}</label>
                                    <input id="city" type="text" name="city" value="{{ old('city', $user->city) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.address') }}</label>
                                    <input id="address" type="text" name="address"
                                        value="{{ old('address', $user->address) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                    {{ __('profile.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Bookings -->
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                        <h2 class="text-2xl font-bold text-slate-800 mb-8 flex items-center">
                            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-4"></div>
                            {{ __('profile.my_bookings') }}
                            <span
                                class="ml-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm px-3 py-1 rounded-full font-medium">{{ $user->bookings->count() }}</span>
                        </h2>

                        @if($user->bookings->count() > 0)
                            <div class="space-y-6">
                                @foreach($user->bookings as $booking)
                                    <div
                                        class="bg-gradient-to-r from-white to-slate-50 border border-slate-200/50 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-3">
                                                    <div
                                                        class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-3">
                                                    </div>
                                                    <h3 class="text-xl font-bold text-slate-800">{{ $booking->room->hotel->title }}
                                                    </h3>
                                                </div>
                                                <div class="space-y-2 ml-6">
                                                    <p class="text-slate-600 font-medium flex items-center">
                                                        <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span>
                                                        {{ __('profile.room') }}:
                                                        {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->title) }}
                                                    </p>
                                                    <p class="text-slate-600 flex items-center">
                                                        <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span>
                                                        {{ __('profile.dates') }}: {{ $booking->started_at->format('d.m.Y') }} -
                                                        {{ $booking->finished_at->format('d.m.Y') }}
                                                    </p>
                                                    <p
                                                        class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent flex items-center">
                                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                                        {{ __('profile.price') }}:
                                                        {{ \App\Http\Controllers\TranslationHelper::formatPrice($booking->price) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col space-y-3 ml-6">
                                                <a href="{{ route('bookings.show', $booking) }}"
                                                    class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-2 rounded-xl font-medium hover:shadow-lg transition-all duration-300 text-center">{{ __('profile.details') }}</a>
                                                <a href="#"
                                                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-xl font-medium hover:shadow-lg transition-all duration-300 text-center">{{ __('profile.edit') }}</a>
                                                <form action="{{ route('profile.cancel-booking', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-2 rounded-xl font-medium hover:shadow-lg transition-all duration-300"
                                                        onclick="return confirm('{{ __('profile.cancel_booking_confirm') }}')">{{ __('profile.cancel') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="w-24 h-24 bg-gradient-to-r from-slate-200 to-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-4xl">📋</span>
                                </div>
                                <p class="text-slate-500 text-lg">{{ __('profile.no_bookings') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-8">
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                        <h2 class="text-2xl font-bold text-slate-800 mb-8 flex items-center">
                            <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-4"></div>
                            {{ __('profile.security_settings') }}
                        </h2>

                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-2xl p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">🔒 {{ __('profile.2fa_authentication') }}
                                    </h3>
                                    <p class="text-slate-600 mt-1">{{ __('profile.additional_account_protection') }}</p>
                                </div>
                                <form action="{{ route('profile.toggle-2fa') }}" method="POST">
                                    @csrf
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ $user->two_factor_enabled ? 'checked' : '' }} onchange="this.form.submit()">
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- User Reviews Section -->
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                        <h2 class="text-2xl font-bold text-slate-800 mb-8 flex items-center">
                            <div class="w-2 h-8 bg-gradient-to-b from-yellow-500 to-orange-500 rounded-full mr-4"></div>
                            ⭐ {{ __('reviews.my_reviews') }}
                        </h2>

                        @if($user->reviews->count() > 0)
                            <div class="space-y-6">
                                @foreach($user->reviews as $review)
                                    <div class="bg-gradient-to-r from-white to-slate-50 border border-slate-200/50 rounded-2xl p-6 hover:shadow-lg transition-all duration-300">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-3 h-3 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full mr-3"></div>
                                                    <h3 class="text-lg font-bold text-slate-800">
                                                        @if($review->reviewable_type === 'App\Models\Hotel')
                                                            🏨 {{ $review->reviewable->title }}
                                                        @else
                                                            🛏️ {{ $review->reviewable->title }} ({{ $review->reviewable->hotel->title }})
                                                        @endif
                                                    </h3>
                                                </div>

                                                @if($review->rating)
                                                    <div class="flex items-center mb-3">
                                                        <span class="text-yellow-500 text-lg mr-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <span class="text-slate-600 font-medium">{{ $review->rating }}/5</span>
                                                    </div>
                                                @endif

                                                <div class="text-slate-700 leading-relaxed">
                                                    {{ $review->content }}
                                                </div>
                                            </div>

                                            <div class="text-right ml-4">
                                                <div class="flex items-center mb-2">
                                                    @if($review->is_approved)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✓ {{ __('reviews.approved') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ⏳ {{ __('reviews.pending') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-slate-500 text-sm">
                                                    {{ $review->created_at->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">📝</div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('reviews.no_reviews_yet') }}</h3>
                                <p class="text-slate-600">{{ __('reviews.start_leaving_reviews') }}</p>
                            </div>
                        @endif
                    </div>

                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                        <h2 class="text-2xl font-bold text-slate-800 mb-8 flex items-center">
                            <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-4"></div>
                            {{ __('profile.statistics') }}
                        </h2>
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-600 font-medium">📋 {{ __('profile.total_bookings') }}</span>
                                    <span
                                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $user->bookings->count() }}</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-600 font-medium">💰 {{ __('profile.total_amount') }}</span>
                                    <span
                                        class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ \App\Http\Controllers\TranslationHelper::formatPrice($user->bookings->sum('price')) }}</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-600 font-medium">📅 {{ __('profile.registration') }}</span>
                                    <span
                                        class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $user->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection