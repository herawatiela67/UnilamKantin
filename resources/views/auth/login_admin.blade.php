<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - UnilamKantin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen px-4">

    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-2xl">
        <div class="text-center mb-6">
            <span class="text-3xl">🛡️</span>
            <h2 class="text-xl font-black text-gray-900 tracking-tight mt-2">KantinQuick Admin</h2>
            <p class="text-xs text-gray-400 mt-1">Silakan masuk untuk mengelola data sistem kantin</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 text-rose-700 rounded-xl text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email Admin</label>
                <input type="email" name="email" required placeholder="admin@kantinquick.com" class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm tracking-wide mt-2">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

</body>
</html>