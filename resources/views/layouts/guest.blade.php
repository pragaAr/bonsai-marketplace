<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="Toko Online - Menjual berbagai jenis tanaman bonsai, alat perawatan, dan aksesori untuk para pecinta bonsai. Temukan koleksi lengkap kami dengan harga terbaik dan layanan pengiriman cepat.">
  <title>{{ $title ?? 'bonsaiku' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="font-sans antialiased text-secondary-900 bg-secondary-50">
  {{ $slot }}
  @livewireScripts
</body>

</html>
