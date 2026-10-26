@props([
  'size' => 'md',
  'showText' => true,
  'theme' => 'light'
])

@php
  $customLogo = \App\Models\SiteContent::get('site_logo');
  $siteName = \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba');
  
  // Pisahkan nama situs jika terdiri dari dua kata atau lebih
  $parts = explode(' ', $siteName, 2);
  $part1 = $parts[0] ?? 'Diabetes';
  $part2 = $parts[1] ?? 'Uji Coba';
  
  $boxSize = match($size) {
    'sm' => 'w-8 h-8',
    'lg' => 'w-12 h-12',
    'xl' => 'w-16 h-16',
    default => 'w-10 h-10',
  };

  $textClass1 = match($size) {
    'sm' => 'text-base font-extrabold',
    'lg' => 'text-2xl sm:text-3xl font-extrabold',
    'xl' => 'text-3xl sm:text-4xl font-black',
    default => 'text-xl sm:text-2xl font-extrabold',
  };
  
  $textColor1 = ($theme === 'dark') ? 'text-white' : 'text-[#123B5D]';
  $textColor2 = ($theme === 'dark') ? 'text-cyan-300' : 'text-[#168FE5]';
@endphp

<div class="flex items-center space-x-2.5">
  <div class="{{ $boxSize }} flex items-center justify-center shrink-0">
    @if(!empty($customLogo))
      <img 
        src="{{ $customLogo }}" 
        alt="{{ $siteName }}" 
        class="w-full h-full object-contain filter drop-shadow-2xs rounded-lg"
      >
    @else
      <!-- Logo Mark Bawaan (Infinity / Ribbon Heart & Simbol Medis) -->
      <svg viewBox="0 0 100 80" class="w-full h-full filter drop-shadow-2xs">
        <path d="M35 15 C15 15, 5 35, 5 50 C5 68, 22 75, 38 65 L50 55 L62 65 C78 75, 95 68, 95 50 C95 35, 85 15, 65 15 C52 15, 42 25, 50 40 C58 25, 48 15, 35 15 Z" fill="#00b4d8" opacity="0.95"/>
        <path d="M50 40 C42 25, 52 15, 65 15 C85 15, 95 35, 95 50 C95 68, 78 75, 62 65 L50 55 Z" fill="#00a870"/>
        <circle cx="50" cy="38" r="7" fill="#ffffff"/>
        <path d="M47 38 h6 M50 35 v6" stroke="#00a870" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    @endif
  </div>

  @if($showText)
    <div class="flex items-baseline space-x-1 leading-none select-none">
      <span class="{{ $textClass1 }} {{ $textColor1 }} tracking-tight">{{ $part1 }}</span>
      <span class="{{ $textClass1 }} {{ $textColor2 }} tracking-tight ml-1">{{ $part2 }}</span>
    </div>
  @endif
</div>
