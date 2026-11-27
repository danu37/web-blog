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
        class="mt-16 pt-8 border-t border-[#242732] flex flex-wrap items-center justify-center gap-2"
    >
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="px-3.5 py-2 text-xs uppercase tracking-[0.16em] text-[#6e6e6e]"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                :class="[
                    'px-3.5 py-2 text-xs uppercase tracking-[0.16em] border rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]',
                    link.active
                        ? 'border-[#b46b35] text-[#b46b35] bg-[#1a1c23]'
                        : 'border-[#242732] text-[#b2b2b2] hover:text-white hover:border-[#b46b35]',
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
