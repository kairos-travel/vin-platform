@props(['class' => ''])

<a href="{{ route('main') }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 shrink-0 ' . $class]) }}>
    @if (file_exists(public_path('images/logo.svg')))
        <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'БазаБаза') }}" class="h-7 w-7 shrink-0">
    @else
        {{-- Замени на logo.svg из Figma: Export → SVG → public/images/logo.svg --}}
        <svg class="h-7 w-7 shrink-0" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="#ED1C24" d="M14 1.5l1.8 5.5h5.9l-4.8 3.5 1.8 5.5L14 14.5l-4.7 3.5 1.8-5.5-4.8-3.5h5.9L14 1.5z"/>
            <path fill="#ED1C24" d="M14 5.2l.9 2.8h2.9l-2.3 1.7.9 2.8L14 11.6l-2.4 1.7.9-2.8-2.3-1.7h2.9L14 5.2z"/>
        </svg>
    @endif
    <span class="text-[17px] font-bold text-black leading-none tracking-tight">
        БазаБаза
    </span>
</a>
