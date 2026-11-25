<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const site = computed(() => page.props.site ?? {});
const mobileOpen = ref(false);

const navItems = [
    { label: 'Journal', href: '/blog' },
    { label: 'Galleries', href: '/gallery' },
    { label: 'About', href: '/about' },
    { label: 'Contact', href: '/contact' },
];

const isActive = (href) => {
    const url = page.url || '/';
    return url === href || url.startsWith(`${href}/`);
};
</script>

<template>
    <header class="border-b border-[#1e1e22] bg-[#0c0c0d]/95 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 h-18 flex items-center justify-between gap-6">
            <Link
                href="/"
                class="flex items-center gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e]"
            >
                <img
                    v-if="site.logoUrl"
                    :src="site.logoUrl"
                    :alt="site.name"
                    class="h-7 w-auto object-contain"
                />
                <span class="text-xs sm:text-sm uppercase tracking-[0.28em] font-medium text-[#f2efe9]">
                    {{ site.name || 'NOIR & FRAME' }}
                </span>
            </Link>

            <nav aria-label="Primary navigation" class="hidden md:flex items-center gap-8">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'text-[11px] uppercase tracking-[0.22em] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e] py-1',
                        isActive(item.href)
                            ? 'text-[#c8a97e]'
                            : 'text-[#a39e93] hover:text-[#f2efe9]',
                    ]"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <button
                type="button"
                class="md:hidden inline-flex items-center justify-center p-2 text-[#a39e93] hover:text-[#f2efe9] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e]"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigation menu"
                @click="mobileOpen = !mobileOpen"
            >
                <span class="text-[11px] uppercase tracking-[0.22em]">
                    {{ mobileOpen ? 'Close' : 'Menu' }}
                </span>
            </button>
        </div>

        <nav
            v-if="mobileOpen"
            aria-label="Mobile navigation"
            class="md:hidden border-t border-[#1e1e22] bg-[#0c0c0d] px-5 py-6 space-y-4"
        >
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="block text-xs uppercase tracking-[0.22em] text-[#d6d2c9] hover:text-[#c8a97e] py-1"
                @click="mobileOpen = false"
            >
                {{ item.label }}
            </Link>
        </nav>
    </header>
</template>
