{{-- Desktop: как в Figma — лого слева, меню по центру, pill справа --}}
<header x-data="{ open: false }" class="site-header">
    <div class="site-header__inner">
        <x-site-logo />

        <x-header-menu-items class="site-header__nav" />

        <div class="hidden lg:flex items-center ms-auto">
            <x-auth-button />
        </div>

        <button
            type="button"
            @click="open = ! open"
            class="lg:hidden inline-flex items-center justify-center p-2 text-brand-menu hover:text-gray-900"
            aria-label="Меню"
        >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div
        :class="{ 'block': open, 'hidden': ! open }"
        class="hidden lg:hidden border-t border-brand-header-border bg-white px-6 py-4 space-y-4"
    >
        <x-header-menu-items mobile />
        <x-auth-button mobile />
    </div>
</header>
