<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume AI Wizard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .spinner {
            animation: spin 20s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-progress {
            width: 0%;
            animation-name: progress;
            animation-timing-function: linear;
            animation-fill-mode: forwards;
        }

        @keyframes progress {
            from { width: 0%; }
            to { width: 100%; }
        }
</style>
    </style>
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
