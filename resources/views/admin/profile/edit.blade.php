@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <!-- Profile Update Form -->
    <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
        @csrf
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h3>
            <div class="grid gap-6">
                <div>
                    <label class="block mb-1 font-semibold">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $admin->first_name) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('first_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $admin->last_name) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('last_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Profile</button>
            </div>
        </div>
    </form>

    <!-- Password Change Form -->
    <form method="POST" action="{{ route('admin.password.update') }}" class="mt-10 space-y-6">
        @csrf
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
            <div class="grid gap-6">
                <div>
                    <label class="block mb-1 font-semibold">Current Password</label>
                    <input type="password" name="current_password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('current_password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">New Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    @error('password_confirmation')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Password</button>
            </div>
        </div>
    </form>
</div>
@endsection
