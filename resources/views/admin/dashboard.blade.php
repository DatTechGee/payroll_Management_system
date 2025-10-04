@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8 bg-gradient-to-br from-white via-blue-50/20 to-blue-100/20 rounded-2xl shadow-sm p-8 backdrop-blur-xl border border-blue-100/50 relative overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
            <div class="md:flex md:items-center md:justify-between relative">
                <div class="min-w-0 flex-1" x-data="{ greeting: '' }" x-init="
                    hour = new Date().getHours();
                    greeting = hour < 12 ? 'Good Morning' : hour < 17 ? 'Good Afternoon' : 'Good Evening';
                ">
                    <h2 class="text-3xl font-bold leading-7 sm:text-4xl sm:tracking-tight flex flex-col gap-3">
                        <div class="relative">
                            <span x-text="greeting" class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent"></span>
                        </div>
                        <span class="bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                            {{ auth()->user()->first_name }}
                        </span>
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">Welcome to your payroll management dashboard.</p>
                </div>
                <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:mt-0 sm:ml-4">
                    <a href="{{ route('admin.payroll.process') }}" 
                       class="group relative inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium transition-all duration-300">
                        <!-- Primary Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl"></div>
                        
                        <!-- Content -->
                        <div class="relative flex items-center">
                            <div class="mr-2 p-1 bg-blue-500/20 rounded-lg">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Process Payroll</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Employee Management -->
            <a href="{{ route('admin.employees.index') }}" class="group relative bg-white/90 backdrop-blur-xl p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 ease-out transform hover:scale-[1.02] border border-gray-100/50 hover:border-blue-200 overflow-hidden">
                <div class="flex items-center relative">
                    <div class="inline-flex p-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-all duration-300">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-gray-900">Employee Management</p>
                        <p class="text-sm text-gray-500">Manage employee records</p>
                    </div>
                </div>
            </a>

            <!-- Attendance Management -->
            <a href="{{ route('admin.attendance') }}" class="group relative bg-white/90 backdrop-blur-xl p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 ease-out transform hover:scale-[1.02] border border-gray-100/50 hover:border-green-200 overflow-hidden">
                <div class="flex items-center relative">
                    <div class="inline-flex p-3 bg-gradient-to-br from-green-100 to-green-200 rounded-xl group-hover:scale-110 transition-all duration-300">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-gray-900">Attendance</p>
                        <p class="text-sm text-gray-500">Track attendance records</p>
                    </div>
                </div>
            </a>

            <!-- Payroll Management -->
            <a href="{{ route('admin.payroll.index') }}" class="group relative bg-white/90 backdrop-blur-xl p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 ease-out transform hover:scale-[1.02] border border-gray-100/50 hover:border-purple-200 overflow-hidden">
                <div class="flex items-center relative">
                    <div class="inline-flex p-3 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl group-hover:scale-110 transition-all duration-300">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-gray-900">Payroll</p>
                        <p class="text-sm text-gray-500">Manage payroll processing</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Shortcuts Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200/50">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.employees.create') }}" class="flex items-center p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Add New Employee</p>
                    </div>
                </a>

                <a href="{{ route('admin.payroll.process') }}" class="flex items-center p-3 rounded-xl hover:bg-green-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Process New Payroll</p>
                    </div>
                </a>

                <a href="{{ route('admin.deductions.index') }}" class="flex items-center p-3 rounded-xl hover:bg-red-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Manage Deductions</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
