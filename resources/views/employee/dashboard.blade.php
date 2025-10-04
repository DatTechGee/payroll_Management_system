@extends('layouts.app')

@section('title', 'Employee Dashboard')

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
                    <p class="mt-2 text-sm text-gray-500">Welcome to your employee dashboard.</p>
                </div>
            </div>
        </div>

        <!-- Employee Information Card -->
        <div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-blue-50 to-blue-100/20">
                <h3 class="text-lg font-semibold text-gray-900">Employee Information</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->employee_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->department->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Position</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->position }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Hired</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->date_hired->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->bank_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->account_number}}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- View Payslips -->
            <a href="{{ route('employee.payroll.index') }}" class="group relative bg-white/90 backdrop-blur-xl p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 ease-out transform hover:scale-[1.02] border border-gray-100/50 hover:border-blue-200 overflow-hidden">
                <div class="flex items-center relative">
                    <div class="inline-flex p-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-all duration-300">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-gray-900">Payslips</p>
                        <p class="text-sm text-gray-500">View and download your payslips</p>
                    </div>
                </div>
            </a>

            <!-- Edit Profile -->
            <a href="{{ route('employee.profile.edit') }}" class="group relative bg-white/90 backdrop-blur-xl p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 ease-out transform hover:scale-[1.02] border border-gray-100/50 hover:border-green-200 overflow-hidden">
                <div class="flex items-center relative">
                    <div class="inline-flex p-3 bg-gradient-to-br from-green-100 to-green-200 rounded-xl group-hover:scale-110 transition-all duration-300">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-gray-900">Profile Settings</p>
                        <p class="text-sm text-gray-500">Update your personal information</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Payslips -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-blue-50 to-blue-100/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Payslips</h3>
                    <a href="{{ route('employee.payroll.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payslips ?? [] as $payslip)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payslip->pay_month->format('F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₦{{ number_format($payslip->net_pay, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payslip->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($payslip->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('employee.payroll.download', $payslip) }}" class="text-blue-600 hover:text-blue-900">Download</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No payslips available yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
