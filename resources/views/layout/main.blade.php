<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* Custom styles */
    .gradient-bg {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }

    .sidebar {
        transition: all 0.3s ease;
    }

    .input-highlight {
        transition: all 0.3s ease;
    }

    .input-highlight:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }

    .modal {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .modal-hidden {
        opacity: 0;
        transform: scale(0.9);
        pointer-events: none;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex">
        @yield('content')
    </div>

</body>

</html>