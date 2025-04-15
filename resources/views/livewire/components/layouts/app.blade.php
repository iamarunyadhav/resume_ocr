<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume AI Wizard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <div class="text-2xl font-bold mb-4">AI Resume Extraction Wizard</div>
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
