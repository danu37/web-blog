<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav
        v-if="links.length > 3"
        aria-label="Pagination"
        class="mt-16 pt-8 border-t border-[#1e1e22] flex flex-wrap items-center justify-center gap-2"
    >
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="px-3.5 py-2 text-xs uppercase tracking-[0.18em] text-[#55524b]"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                :class="[
                    'px-3.5 py-2 text-xs uppercase tracking-[0.18em] border transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e]',
                    link.active
                        ? 'border-[#c8a97e] text-[#c8a97e]'
                        : 'border-[#1e1e22] text-[#a39e93] hover:text-[#f2efe9] hover:border-[#3a3a42]',
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
