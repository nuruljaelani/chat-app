<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-900">
    <div class="flex items-center justify-center min-h-screen flex-col">
        @if ($errors->any())
            <div class="text-red-500 font-medium text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div
            class="bg-slate-900 rounded-lg flex flex-col max-w-md w-full p-4 gap-4 md:gap-6 lg:gap-8 shadow shadow-gray-700">
            <p class="text-xl lg:text-2xl text-white font-semibold">Login</p>
            <form class="flex flex-col gap-4" action="{{ route('auth') }}" method="POST">
                @csrf
                <div class="flex flex-col">
                    <label class="text-base text-slate-700 font-medium">Email</label>
                    <input type="text" name="email"
                        class="bg-slate-800 rounded-md px-4 py-2 focus:outline-none ring-1 text-slate-200" />
                </div>
                <div class="flex flex-col">
                    <label class="text-base text-slate-700 font-medium">Password</label>
                    <input type="password" name="password"
                        class="bg-slate-800 rounded-md px-4 py-2 focus:outline-none ring-1 text-slate-200" />
                </div>
                <button type="submit" class="bg-sky-900 hover:bg-blue-900 rounded-md py-2 text-slate-200">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>
