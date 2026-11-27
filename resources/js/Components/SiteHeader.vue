<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    transparent: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const site = computed(() => page.props.site ?? {});
const mobileOpen = ref(false);
const searchOpen = ref(false);
const searchQuery = ref('');

const navItems = [
    { label: 'Home', href: '/' },
    { label: 'Sejarah', href: '/blog' },
    { label: 'Chant', href: '/gallery' },
    { label: 'Produk', href: '/#shop' },
    { label: 'Tentang Kami', href: '/about' },
];

const isActive = (href) => {
    const url = page.url || '/';
    if (href === '/') {
        return url === '/';
    }
    return url === href || url.startsWith(`${href}/`);
};

const submitSearch = () => {
    const q = searchQuery.value.trim();
    router.get('/blog', q ? { q } : {});
    searchOpen.value = false;
    mobileOpen.value = false;
};
</script>

<template>
    <header
        :class="[
            'z-40 w-full transition-colors',
            transparent
                ? 'absolute top-0 left-0 bg-gradient-to-b from-black/65 via-black/30 to-transparent'
                : 'sticky top-0 border-b border-[#1f222a] bg-[#121317]/95 backdrop-blur-sm',
        ]"
    >
        <div class="max-w-7xl mx-auto px-5 sm:px-8 h-20 flex items-center justify-between gap-6">
            <Link
                href="/"
                class="flex items-center gap-3 shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]"
            >
                <img
                    v-if="site.logoUrl"
                    :src="site.logoUrl"
                    :alt="site.name"
                    class="h-7 w-auto object-contain"
                />
                <span class="text-sm sm:text-base font-semibold tracking-[0.18em] uppercase text-white">
                    {{ site.name || 'GARUDA STERIDA' }}
                </span>
            </Link>

            <nav aria-label="Primary navigation" class="hidden md:flex items-center gap-8">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'text-sm tracking-wide transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35] py-1',
                        isActive(item.href)
                            ? 'text-[#b46b35] font-medium'
                            : 'text-white/85 hover:text-white',
                    ]"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <div class="flex items-center gap-3">
                <form
                    v-if="searchOpen"
                    @submit.prevent="submitSearch"
                    class="hidden sm:flex items-center gap-2"
                >
                    <label for="header-search-input" class="sr-only">Search articles</label>
                    <input
                        id="header-search-input"
                        v-model="searchQuery"
                        type="search"
                        placeholder="Search stories..."
                        class="w-44 lg:w-56 bg-[#1a1c23] border border-[#2c303c] rounded px-3 py-1.5 text-sm text-white placeholder-[#b2b2b2] focus:outline-none focus:border-[#b46b35]"
                    />
                </form>

                <button
                    type="button"
                    aria-label="Toggle search"
                    class="p-2 text-white/85 hover:text-[#b46b35] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]"
                    @click="searchOpen = !searchOpen"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 text-white/85 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]"
                    :aria-expanded="mobileOpen"
                    aria-label="Toggle navigation menu"
                    @click="mobileOpen = !mobileOpen"
                >
                    <span class="text-xs uppercase tracking-[0.2em]">
                        {{ mobileOpen ? 'Close' : 'Menu' }}
                    </span>
                </button>
            </div>
        </div>

        <div
            v-if="mobileOpen"
            class="md:hidden border-t border-[#1f222a] bg-[#121317] px-5 py-6 space-y-4"
        >
            <form @submit.prevent="submitSearch" class="flex items-center gap-2 pb-2">
                <label for="mobile-search-input" class="sr-only">Search articles</label>
                <input
                    id="mobile-search-input"
                    v-model="searchQuery"
                    type="search"
                    placeholder="Search stories..."
                    class="w-full bg-[#1a1c23] border border-[#2c303c] rounded px-3 py-2 text-sm text-white placeholder-[#b2b2b2] focus:outline-none focus:border-[#b46b35]"
                />
                <button
                    type="submit"
                    class="px-3.5 py-2 bg-[#b46b35] text-white text-xs uppercase tracking-wider rounded"
                >
                    Go
                </button>
            </form>

            <nav aria-label="Mobile navigation" class="space-y-3">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="block text-sm tracking-wide text-white/85 hover:text-[#b46b35] py-1"
                    @click="mobileOpen = false"
                >
                    {{ item.label }}
                </Link>
            </nav>
        </div>
    </header>
</template>
