@props(['class' => '', 'light' => false])

<a href="{{ route('main') }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 shrink-0 ' . $class]) }}>
    <img
        src="{{ asset('images/logo-icon.svg') }}"
        alt=""
        class="h-7 w-7 shrink-0"
        width="28"
        height="28"
        aria-hidden="true"
    >
    <span @class([
        'font-brand font-extrabold text-[25.5px] leading-none tracking-[1.275px]',
        'text-black' => ! $light,
        'text-white' => $light,
    ])>
        БазаБаза
    </span>
</a>
