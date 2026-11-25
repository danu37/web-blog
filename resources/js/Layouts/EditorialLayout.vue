<script setup>
import SiteHeader from '../Components/SiteHeader.vue';
import SiteFooter from '../Components/SiteFooter.vue';
</script>

<template>
    <div class="min-h-screen bg-[#0c0c0d] text-[#f2efe9] flex flex-col font-sans">
        <SiteHeader />
        <main class="flex-1">
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>

