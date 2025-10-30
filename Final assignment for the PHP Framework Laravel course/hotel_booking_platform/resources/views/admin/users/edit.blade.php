@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">👤 {{ __('admin.edit_user') }}</h1>
            <p class="text-gray-600">{{ __('admin.update_user_info', ['user_name' => $user->full_name]) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.full_name') }}</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('full_name')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.phone') }}</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.role') }}</label>
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('admin.user') }}</option>
                            <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>{{ __('admin.manager') }}</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('admin.administrator') }}</option>
                        </select>
                        @error('role')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-800">← {{ __('admin.back_to_user') }}</a>
                    <div class="space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            {{ __('admin.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection