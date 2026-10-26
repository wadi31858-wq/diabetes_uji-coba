<!-- ============================================================== -->
<!-- REUSABLE FLYING BIRDS ANIMATION COMPONENT                      -->
<!-- Realistic Flock of Flying Birds soaring across the sky         -->
<!-- ============================================================== -->
<div class="bird-layer pointer-events-none absolute inset-0 overflow-hidden z-[4] select-none opacity-85" aria-hidden="true">
  <!-- Bird 1: Medium altitude, leading flock -->
  <div class="bird-1 absolute top-[10%] sm:top-[12%] left-0 w-7 h-4 sm:w-8 sm:h-5 text-[#123B5D]/80 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.72s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.72s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 2: Slightly larger, prominent front glider -->
  <div class="bird-2 absolute top-[18%] sm:top-[20%] left-0 w-9 h-5 sm:w-10 sm:h-6 text-[#123B5D]/85 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.62s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.62s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 3: Distant high sky tiny bird -->
  <div class="bird-3 absolute top-[5%] sm:top-[6%] left-0 w-5 h-3 sm:w-6 sm:h-3.5 text-[#123B5D]/65 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.82s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.82s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 4: Mid-altitude follower -->
  <div class="bird-4 absolute top-[14%] sm:top-[16%] left-0 w-7 h-4 sm:w-8 sm:h-4.5 text-[#123B5D]/75 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.70s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.70s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 5: Upper flock V-wing follower -->
  <div class="bird-5 absolute top-[8%] sm:top-[9%] left-0 w-6 h-3.5 sm:w-7 sm:h-4 text-[#123B5D]/70 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.78s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.78s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 6: Mid-lower soaring bird -->
  <div class="bird-6 absolute top-[22%] sm:top-[24%] left-0 w-8 h-4.5 sm:w-9 sm:h-5 text-[#123B5D]/80 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.65s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.65s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 7: Far distant soaring bird -->
  <div class="bird-7 absolute top-[4%] sm:top-[5%] left-0 w-4.5 h-2.5 sm:w-5 sm:h-3 text-[#123B5D]/60 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.88s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.88s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 8: Center flock glider -->
  <div class="bird-8 absolute top-[16%] sm:top-[17%] left-0 w-7 h-4 sm:w-7.5 sm:h-4.5 text-[#123B5D]/75 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.74s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.74s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 9: Upper-mid follower -->
  <div class="bird-9 absolute top-[11%] sm:top-[13%] left-0 w-6 h-3.5 sm:w-6.5 sm:h-4 text-[#123B5D]/70 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.68s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.68s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>

  <!-- Bird 10: Graceful mid-lower follower -->
  <div class="bird-10 absolute top-[19%] sm:top-[21%] left-0 w-8.5 h-5 sm:w-9 sm:h-5.5 text-[#123B5D]/85 filter drop-shadow-xs">
    <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
      <path class="bird-wing-l" style="animation-duration: 0.60s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
      <path class="bird-wing-r" style="animation-duration: 0.60s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
      <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
    </svg>
  </div>
</div>
