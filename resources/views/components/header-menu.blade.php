{{-- Figma: desktop Top bar 253:4513; tablet 253:443; menu 253:645 --}}
<header x-data="{ open: false }" class="site-header">
    <div class="site-header__inner">
        <x-site-logo class="shrink-0" />

        <x-header-menu-items class="site-header__nav" />

        <div class="hidden xl:flex items-center justify-end shrink-0">
            <x-auth-button />
        </div>

        {{-- Figma Tablet / Phone: бургер + красная pill в шапке --}}
        <div class="site-header__actions xl:hidden">
            <button
                type="button"
                @click="open = ! open"
                class="inline-flex items-center justify-center p-2 -ml-2 text-brand-menu hover:text-gray-900 rounded-md"
                :aria-expanded="open"
                aria-label="Меню"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <x-auth-button compact />
        </div>
    </div>

    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        class="xl:hidden bg-white"
        x-cloak
    >
        <div class="site-header__mobile-panel">
            <x-header-menu-items mobile />
            <x-auth-button mobile />
        </div>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
</style>
