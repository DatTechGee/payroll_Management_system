<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PayrollMS - Payroll Management System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/welcome-animations.css') }}" rel="stylesheet">
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #374151 0%, #1f2937 50%, #111827 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .hero-pattern {
            background-color: #fafafa;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23111827' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
            overflow: hidden;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            // Observe feature cards
            document.querySelectorAll('.feature-card').forEach(card => {
                observer.observe(card);
            });

            // Observe testimonial cards
            document.querySelectorAll('.testimonial-card').forEach(card => {
                observer.observe(card);
            });

            // Animate stats when in view
            document.querySelectorAll('.stat-counter').forEach(stat => {
                observer.observe(stat);
            });

            // Add reveal classes
            document.querySelectorAll('.reveal').forEach(el => {
                observer.observe(el);
                el.classList.add('active');
            });
        });
    </script>
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 to-blue-50">
        @if (session('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg" role="alert">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Header/Navigation -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
                    <div class="flex justify-start lg:w-0 lg:flex-1">
                        <span class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">PayrollMS</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Dashboard</a>
                                @else
                                    <a href="{{ route('employee.dashboard') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Dashboard</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Login</a>
                                {{-- Register route removed because it does not exist --}}
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="relative overflow-hidden hero-pattern">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 py-16 sm:py-20 md:py-24 lg:py-32 lg:max-w-2xl">
                    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="text-center lg:text-left hero-content fade-in-up">
                            <h1 class="text-4xl tracking-tight font-extrabold sm:text-5xl md:text-6xl">
                                <span class="block gradient-text">PayrollMS</span>
                                <span class="block text-gray-900">Simplified HR Solutions</span>
                            </h1>
                            <p class="mt-6 text-lg text-gray-600 max-w-lg mx-auto lg:mx-0 fade-in-right">
                                Transform your payroll management with our advanced system. Streamline employee data, automate calculations, and ensure accurate payroll processing. Our comprehensive solution helps businesses of all sizes manage their workforce efficiently while ensuring compliance with tax regulations.
                            </p>
                            <p class="mt-4 text-md text-gray-500 max-w-lg mx-auto lg:mx-0">
                                ✓ Automated Salary Calculations<br>
                                ✓ Tax Management & Compliance<br>
                                ✓ Employee Portal Access<br>
                                ✓ Real-time Attendance Tracking<br>
                                ✓ Custom Report Generation
                            </p>
                            <div class="mt-10 flex gap-6 justify-center lg:justify-start">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 rounded-xl shadow-lg text-base font-medium text-white bg-gradient-to-r from-gray-700 to-gray-900 hover:from-gray-800 hover:to-black transition-all duration-200 transform hover:-translate-y-0.5">
                                    Get Started
                                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                <a href="#features" class="inline-flex items-center px-8 py-4 rounded-xl border-2 border-blue-200 text-base font-medium text-blue-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <div class="relative h-64 sm:h-72 md:h-96 lg:h-full">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-white">
                        <div class="absolute inset-0 bg-white/50 backdrop-blur-sm"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-1/2 h-1/2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl lg:text-5xl">
                        Smart Payroll Management
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        All the tools you need to manage your workforce efficiently
                    </p>
                </div>

                <div class="mt-20">
                    <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div class="relative group reveal reveal-up">
                            <div class="feature-card h-full bg-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-black/5">
                                <div class="p-8">
                                    <div class="inline-flex items-center justify-center p-3 mb-5 bg-blue-100 rounded-lg text-blue-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800">Employee Management</h3>
                                    <p class="mt-4 text-gray-500 leading-relaxed">
                                        Comprehensive employee database with detailed profiles, documents, and history tracking. Easily manage employee information and access records.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="relative group">
                            <div class="feature-card h-full bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-black/5">
                                <div class="p-8">
                                    <div class="inline-flex items-center justify-center p-3 mb-5 bg-blue-100 rounded-lg text-blue-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Attendance Tracking</h3>
                                    <p class="mt-4 text-gray-600 leading-relaxed">
                                        Modern attendance system with automated tracking, leave management, and overtime calculations. Generate detailed reports instantly.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="relative group">
                            <div class="feature-card h-full bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-black/5">
                                <div class="p-8">
                                    <div class="inline-flex items-center justify-center p-3 mb-5 bg-blue-100 rounded-lg text-blue-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Payroll Processing</h3>
                                    <p class="mt-4 text-gray-600 leading-relaxed">
                                        Automated salary calculations, tax deductions, and payslip generation. Process payroll quickly and accurately with our advanced system.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-gradient-to-br from-blue-800 to-blue-900 py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-3 text-center">
                                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-10 border border-white/10 hover:bg-white/20 transition-colors duration-300">
                        <div class="text-5xl font-bold text-white mb-1 stat-counter">99.9%</div>
                        <div class="text-lg font-medium text-blue-100">Accuracy Rate</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-10 border border-white/10 hover:bg-white/20 transition-colors duration-300">
                        <div class="text-5xl font-bold text-white mb-1 stat-counter">50%</div>
                        <div class="text-lg font-medium text-blue-100">Time Saved</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-10 border border-white/10 hover:bg-white/20 transition-colors duration-300">
                        <div class="text-5xl font-bold text-white mb-1 stat-counter">24/7</div>
                        <div class="text-lg font-medium text-blue-100">System Availability</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Testimonials</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl lg:text-5xl">Trusted by Businesses</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-blue-400" fill="currentColor" viewBox="0 0 48 48">
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Sarah Johnson</h3>
                                <p class="text-sm text-gray-600">HR Manager</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"This payroll system has revolutionized how we manage our employee payments. The automated calculations have saved us countless hours."</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-blue-400" fill="currentColor" viewBox="0 0 48 48">
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Michael Chen</h3>
                                <p class="text-sm text-gray-600">Finance Director</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"The tax compliance features and reporting capabilities have made our end-of-year processes much smoother and more efficient."</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-blue-400" fill="currentColor" viewBox="0 0 48 48">
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                    <path d="M12 14l9-5-9-4-9 4 9 5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Amanda Torres</h3>
                                <p class="text-sm text-gray-600">Small Business Owner</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Perfect for small businesses like mine. The interface is intuitive, and the customer support has been exceptional."</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Features Section -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Why Choose Us</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">Advanced Features for Modern Businesses</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Compliance & Security</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Tax law compliance updates</li>
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Data encryption & security</li>
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Regular backups</li>
                        </ul>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Employee Self-Service</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Access payslips online</li>
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Update personal information</li>
                            <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Leave management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="relative bg-gradient-to-br from-gray-800 via-gray-900 to-black py-24">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 100 L0 100 Z" fill="white"/>
                </svg>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="text-center lg:text-left">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">
                            <span class="block">Transform Your Payroll Process Today</span>
                        </h2>
                        <p class="mt-4 text-xl text-blue-50/90">
                            Join thousands of businesses already using our platform. Start your journey to efficient payroll management with a 30-day free trial.
                        </p>
                        <ul class="mt-4 space-y-2 text-blue-50/80">
                            <li class="flex items-center"><svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>No credit card required</li>
                            <li class="flex items-center"><svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Full feature access</li>
                            <li class="flex items-center"><svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>24/7 support</li>
                        </ul>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center justify-center px-8 py-4 text-lg font-medium rounded-xl text-white bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-500 hover:to-blue-600 shadow-lg hover:shadow-xl transition-all duration-200 border border-white/10">
                            Sign In
                            <svg class="ml-2 -mr-1 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-800">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-white">PayrollMS</span>
                    <p class="mt-4 text-gray-400 text-sm text-center">
                        Simplifying payroll management for businesses of all sizes.
                    </p>
                    <p class="mt-4 text-gray-500 text-sm">
                        &copy; {{ date('Y') }} PayrollMS. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
