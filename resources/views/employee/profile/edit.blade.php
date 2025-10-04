@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800">
                <h2 class="text-2xl font-bold text-white">Edit Profile</h2>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.profile.update') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" 
                                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Banking Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('bank_name', $user->bank_name) }}" required>
                                @error('bank_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                <input type="text" name="account_number" id="account_number" 
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('account_number', $user->account_number) }}" required>
                                @error('account_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Change Password (Optional) -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password (Optional)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" id="password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-4">
                        <a href="{{ route('employee.dashboard') }}" 
                           class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection