<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayrollMS - Login</title>
    @vite('resources/css/app.css')
    <style>
        .input-focus-effect {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            border-color: #4F46E5;
            box-shadow: 0 0 0 1px #4F46E5;
        }
        .input-group:focus-within svg {
            color: #4F46E5 !important;
        }
    </style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Payroll Management System</title>
    @vite('resources/css/app.css')
    <style>
        .input-focus-effect:focus-within {
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        .password-toggle:hover .eye-icon {
            color: #4F46E5;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-white to-indigo-200 min-h-screen flex items-center justify-center">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-2xl shadow-2xl border border-gray-100">
            <div class="flex flex-col items-center">
                <span class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-indigo-100 mb-4 shadow">
                    <svg class="h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.25a7.25 7.25 0 1115 0v.25a.75.75 0 01-.75.75h-13.5a.75.75 0 01-.75-.75v-.25z" />
                    </svg>
                </span>
                <h2 class="text-center text-3xl font-extrabold text-gray-900">
                    Sign in to your account
                </h2>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="relative">
                        <label for="email" class="text-sm font-medium text-gray-700 mb-1 block">Email address</label>
                        <div class="relative input-group rounded-lg border border-gray-300 transition-all duration-300">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" required 
                                class="appearance-none relative block w-full px-3 pl-10 py-3 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none bg-transparent sm:text-sm transition-colors" 
                                placeholder="Enter your email">
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label for="password" class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
                        <div class="relative input-group rounded-lg border border-gray-300 transition-all duration-300">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" name="password" type="password" required 
                                class="appearance-none relative block w-full px-3 pl-10 py-3 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none bg-transparent sm:text-sm transition-colors" 
                                placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="eye-icon" class="h-5 w-5 text-gray-400 hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="text-red-500 text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6">
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <svg class="h-5 w-5 text-indigo-200 group-hover:text-white mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Add input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('input-focus-effect');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('input-focus-effect');
            });
        });
    </script>
</body>
</html>