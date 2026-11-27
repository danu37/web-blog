<script setup>
import SiteHeader from '../Components/SiteHeader.vue';
import SiteFooter from '../Components/SiteFooter.vue';

defineProps({
    transparentHeader: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="min-h-screen bg-[#121317] text-white flex flex-col font-sans relative">
        <SiteHeader :transparent="transparentHeader" />
        <main class="flex-1">
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>
