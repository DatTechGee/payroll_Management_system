<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayrollMS - Payroll Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        @keyframes shine {
            from { transform: translateX(-100%); }
            to { transform: translateX(200%); }
        }
        .animate-shine {
            animation: shine 2s infinite linear;
        }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        /* Modern Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Table Styles */
        .table-auto td, .table-fixed td {
            @apply px-4 py-3 text-sm text-gray-900 border-x border-gray-200;
        }
        .table-auto th, .table-fixed th {
            @apply px-4 py-4 text-left text-sm font-semibold text-gray-900 border-x border-gray-200 bg-gradient-to-br from-blue-50 to-white;
        }
        table {
            @apply border border-gray-300 shadow-sm;
        }
        .action-button-delete {
            @apply inline-flex items-center gap-1 px-2 py-1 text-sm font-medium text-red-600 hover:text-red-900 hover:bg-red-50 rounded;
        }
        .action-button-view {
            @apply inline-flex items-center gap-1 px-2 py-1 text-sm font-medium text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded;
        }
        .action-button-edit {
            @apply inline-flex items-center gap-1 px-2 py-1 text-sm font-medium text-green-600 hover:text-green-900 hover:bg-green-50 rounded;
        }
        /* Navigation Styles */
        .nav-item-active {
            @apply relative text-indigo-600 dark:text-indigo-400;
        }
        .nav-item-active::before {
            content: '';
            @apply absolute -left-3 top-1/2 w-1 h-5 bg-indigo-600 dark:bg-indigo-400 rounded-r-full -translate-y-1/2;
        }
        /* Animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-gray-50 to-blue-50">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm lg:hidden" 
             @click="sidebarOpen = false; sidebarCollapsed = true"
             x-cloak></div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" 
             class="fixed inset-y-0 left-0 z-50 w-full overflow-y-auto bg-white/95 backdrop-blur-xl px-6 py-6 lg:hidden transform transition-transform duration-300 ease-out"
             @click.away="sidebarOpen = false">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-2 group hover:opacity-80 transition-opacity">
                        <div class="p-2 bg-gradient-to-br from-indigo-600 via-blue-600 to-blue-700 rounded-xl shadow-lg shadow-blue-200/50 transition-all duration-300 group-hover:scale-110 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 animate-shine"></div>
                            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.5 4H5.5C4.11929 4 3 5.11929 3 6.5V17.5C3 18.8807 4.11929 20 5.5 20H18.5C19.8807 20 21 18.8807 21 17.5V6.5C21 5.11929 19.8807 4 18.5 4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 8H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 16H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="16" cy="16" r="2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">PayrollMS</span>
                            <span class="text-xs text-gray-500">Management System</span>
                        </div>
                    </a>
                </div>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="sidebarOpen = false; sidebarCollapsed = true">
                    <span class="sr-only">Close menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <nav class="space-y-6">
                @if(auth()->user()->isAdmin())
                    <div class="space-y-1">
                        <div class="px-2 py-2">
                            <h3 class="text-sm font-semibold text-gray-500">Dashboard</h3>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="mt-2 block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                Overview
                            </a>
                        </div>
                        <div class="px-2 py-2">
                            <h3 class="text-sm font-semibold text-gray-500">Management</h3>
                            <div class="space-y-1 mt-2">
                                <a href="{{ route('admin.employees.index') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 transition-all duration-300 ease-out transform {{ request()->routeIs('admin.employees.*') ? 'bg-blue-50 translate-x-2' : 'hover:bg-blue-50 hover:translate-x-2' }}">
                                    Employees
                                </a>
                                <a href="{{ route('admin.departments.index') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 transition-all duration-300 ease-out transform {{ request()->routeIs('admin.departments.*') ? 'bg-blue-50 translate-x-2' : 'hover:bg-blue-50 hover:translate-x-2' }}">
                                    Departments
                                </a>
                                <a href="{{ route('admin.attendance') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('admin.attendance*') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                    Attendance
                                </a>
                                <a href="{{ route('admin.payroll.index') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('admin.payroll.*') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                    Payroll
                                </a>
                                <a href="{{ route('admin.deductions.index') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('admin.deductions.*') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                    Deductions
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-1">
                        <div class="px-2 py-2">
                            <h3 class="text-sm font-semibold text-gray-500">Employee Menu</h3>
                            <div class="space-y-1 mt-2">
                                <a href="{{ route('employee.dashboard') }}" 
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('employee.dashboard') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('employee.payroll.index') }}"
                                   class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 {{ request()->routeIs('employee.payroll.*') ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                    Payslip
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mobile Profile Menu -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="px-2 py-2">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200/50">
                                <span class="text-sm font-medium uppercase">{{ substr(auth()->user()->username ?? auth()->user()->email, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->username ?? explode('@', auth()->user()->email)[0] }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="mt-6 space-y-1">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.profile.edit') }}" class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">
                                    Edit Profile
                                </a>
                            @else
                                <a href="{{ route('employee.profile.edit') }}" class="block rounded-lg px-4 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">
                                    Edit Profile
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left rounded-lg px-4 py-2 text-base font-semibold text-red-600 hover:bg-red-50">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col min-h-screen" :class="{'w-72': !sidebarCollapsed, 'w-20': sidebarCollapsed}">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto h-full border-r border-blue-200/40 bg-gradient-to-b from-white via-blue-50/10 to-indigo-50/20 backdrop-blur-xl shadow-lg shadow-blue-100/20 transition-all duration-300" :class="{'px-6': !sidebarCollapsed, 'px-2': sidebarCollapsed}">
                <!-- Logo and brand -->
                <div class="flex h-16 shrink-0 items-center justify-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 group hover:opacity-80 transition-opacity" :class="{'justify-center': sidebarCollapsed}">
                        <div class="p-2 bg-gradient-to-br from-indigo-600 via-blue-600 to-blue-700 rounded-xl shadow-lg shadow-blue-200/50 transition-all duration-300 group-hover:scale-110 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 animate-shine"></div>
                            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.5 4H5.5C4.11929 4 3 5.11929 3 6.5V17.5C3 18.8807 4.11929 20 5.5 20H18.5C19.8807 20 21 18.8807 21 17.5V6.5C21 5.11929 19.8807 4 18.5 4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 8H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 16H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="16" cy="16" r="2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div x-cloak x-show="!sidebarCollapsed" class="flex flex-col">
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent transition-all duration-300 group-hover:tracking-wider">PayrollMS</span>
                            <span class="text-xs text-gray-500">Management System</span>
                        </div>
                    </a>
                </div>
                <nav class="flex flex-1 flex-col">
                    @if(auth()->user()->isAdmin())
                        <div class="space-y-8" :class="{'px-4': !sidebarCollapsed, 'px-1': sidebarCollapsed}">
                            <!-- Dashboard Group -->
                            <div>
                                <p x-cloak x-show="!sidebarCollapsed" class="px-2 text-xs font-semibold text-blue-600 uppercase tracking-wider">Dashboard</p>
                                <ul role="list" class="mt-2 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="group flex items-center gap-x-3 rounded-lg py-2 text-sm font-semibold transition-all duration-300 ease-out {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50 shadow-md' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50 hover:shadow-md' }}" :class="{'px-3 justify-start': !sidebarCollapsed, 'px-2 justify-center': sidebarCollapsed}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                                </svg>
                                            </div>
                                            <span x-cloak x-show="!sidebarCollapsed">Overview</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Management Group -->
                            <div>
                                <p x-show="!sidebarCollapsed" class="px-2 text-xs font-semibold text-blue-600 uppercase tracking-wider transition-opacity">Management</p>
                                <ul role="list" class="mt-2 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.employees.index') }}" 
                                           class="group flex gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.employees.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                            </div>
                                            <span x-show="!sidebarCollapsed">Employees</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.departments.index') }}" 
                                           class="group flex gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.departments.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" />
                                                </svg>
                                            </div>
                                            <span x-show="!sidebarCollapsed">Departments</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.attendance') }}" 
                                           class="group flex gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.attendance*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                                </svg>
                                            </div>
                                            <span x-show="!sidebarCollapsed">Attendance</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.payroll.index') }}" 
                                           class="group flex gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.payroll.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                </svg>
                                            </div>
                                            <span x-show="!sidebarCollapsed">Payroll</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.deductions.index') }}" 
                                           class="group flex gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.deductions.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <span x-show="!sidebarCollapsed">Deductions</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="space-y-8" :class="{'px-4': !sidebarCollapsed, 'px-1': sidebarCollapsed}">
                            <!-- Employee Menu Group -->
                            <div>
                                <p x-cloak x-show="!sidebarCollapsed" class="px-2 text-xs font-semibold text-blue-600 uppercase tracking-wider">Overview</p>
                                <ul role="list" class="mt-2 space-y-1">
                                    <li>
                                        <a href="{{ route('employee.dashboard') }}" 
                                           class="group flex items-center gap-x-3 rounded-lg py-2 text-sm font-semibold transition-all duration-300 ease-out {{ request()->routeIs('employee.dashboard') ? 'text-blue-600 bg-blue-50 shadow-md' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50 hover:shadow-md' }}" :class="{'px-3 justify-start': !sidebarCollapsed, 'px-2 justify-center': sidebarCollapsed}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                                </svg>
                                            </div>
                                            <span x-cloak x-show="!sidebarCollapsed">Dashboard</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Employee Documents -->
                            <div>
                                <p x-cloak x-show="!sidebarCollapsed" class="px-2 text-xs font-semibold text-blue-600 uppercase tracking-wider">Documents</p>
                                <ul role="list" class="mt-2 space-y-1">
                                    <li>
                                        <a href="{{ route('employee.payroll.index') }}" 
                                           class="group flex items-center gap-x-3 rounded-lg py-2 text-sm font-semibold transition-all duration-300 ease-out {{ request()->routeIs('employee.payroll.*') ? 'text-blue-600 bg-blue-50 shadow-md' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50 hover:shadow-md' }}" :class="{'px-3 justify-start': !sidebarCollapsed, 'px-2 justify-center': sidebarCollapsed}">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                </svg>
                                            </div>
                                            <span x-cloak x-show="!sidebarCollapsed">Payslips</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Profile section -->
                    <div class="mt-auto">
                        <div class="space-y-1" :class="{'px-2': sidebarCollapsed}">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" 
                                        class="flex items-center gap-x-4 w-full px-2 py-2 text-sm font-semibold text-gray-700 rounded-lg hover:bg-blue-50 group transition-all duration-300 transform hover:scale-105" :class="{'justify-center': sidebarCollapsed}">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200/50">
                                        <span class="text-sm font-medium uppercase">{{ substr(auth()->user()->username ?? auth()->user()->email, 0, 1) }}</span>
                                    </div>
                                    <div x-cloak x-show="!sidebarCollapsed" class="flex-1 truncate">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->username ?? explode('@', auth()->user()->email)[0] }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <svg x-cloak x-show="!sidebarCollapsed" class="h-5 w-5 text-gray-400" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Profile Dropdown -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="open = false"
                                     :class="{'!left-full ml-2': sidebarCollapsed}"
                                     class="absolute bottom-full left-0 mb-2 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none transform transition-all duration-300 origin-bottom z-50">
                                    <div class="py-1">
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.profile.edit') }}" 
                                               class="flex items-center gap-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit Profile</span>
                                            </a>
                                        @else
                                            <a href="{{ route('employee.profile.edit') }}" 
                                               class="flex items-center gap-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit Profile</span>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center gap-x-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                <span>Sign out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="transition-all duration-300 ease-in-out min-h-screen" :class="{'lg:pl-72': !sidebarCollapsed, 'lg:pl-20': sidebarCollapsed}">
            <div class="sticky top-0 z-40 max-w-7xl mx-auto backdrop-blur-lg bg-white/80 border-b border-gray-200/50">
                <div class="flex h-16 items-center gap-x-4 border-b border-blue-100 bg-white/95 backdrop-blur-sm px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-all duration-300" :class="{'lg:pl-8': sidebarCollapsed}">
                    <!-- Mobile menu button -->
                    <button type="button" class="-m-2.5 p-2.5 text-gray-700 hover:text-blue-600 transition-colors duration-200 lg:hidden" @click="sidebarOpen = !sidebarOpen; sidebarCollapsed = !sidebarOpen">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Desktop sidebar toggle -->
                    <button type="button" class="hidden lg:flex items-center justify-center -m-2.5 p-2.5 text-gray-700 hover:text-blue-600 transition-colors duration-200 rounded-lg hover:bg-gray-100" @click="sidebarCollapsed = !sidebarCollapsed">
                        <span class="sr-only">Toggle sidebar</span>
                        <svg x-show="!sidebarCollapsed" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                        </svg>
                        <svg x-show="sidebarCollapsed" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <!-- Page heading -->
                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <div class="flex items-center space-x-3">
                                <span class="p-2 bg-blue-50 rounded-xl">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                </span>
                                <div>
                                    <h1 class="text-xl font-semibold leading-6 text-gray-900">@yield('title', 'Dashboard')</h1>
                                    <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->first_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6 ml-auto">
                            <div class="flex items-center">
                                <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-8">
                <div class="px-4 sm:px-6 lg:px-8 mx-auto max-w-7xl transition-all duration-300" :class="{'lg:pl-8': sidebarCollapsed}">
                    <!-- Flash Messages -->
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul role="list" class="list-disc space-y-1 pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}", "Warning");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}", "Information");
        @endif
    </script>

    <!-- Page Level Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add active class to current navigation item
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>