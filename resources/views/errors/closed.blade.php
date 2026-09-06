<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JastipKu - Kami Sedang Tutup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-lg border border-slate-100 p-8 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            🌙
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">JastipKu Sedang Istirahat</h1>
        <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            {{ $reason }}
        </p>
        <div class="bg-blue-50 text-blue-700 text-xs font-bold px-4 py-2 rounded-lg inline-block mb-8">
            Buka kembali pukul {{ $openTime }} WIB
        </div>

        <hr class="border-slate-100 mb-6">

        <p class="text-xs text-slate-400">
            Tim JastipKu Admin? <a href="/login" class="text-blue-600 font-bold hover:underline">Login di sini</a>
        </p>
    </div>
</body>

</html>
