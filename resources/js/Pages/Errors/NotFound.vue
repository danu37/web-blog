<script setup>
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import SeoHead from '../../Components/SeoHead.vue';
</script>

<template>
    <SeoHead title="Page Not Found" />
    <EditorialLayout>
        <section class="max-w-3xl mx-auto px-5 sm:px-8 py-32 text-center space-y-6">
            <p class="text-xs uppercase tracking-[0.28em] text-[#b46b35]">404</p>
            <h1 class="text-4xl sm:text-6xl font-semibold text-white">
                Page Not Found
            </h1>
            <p class="text-sm sm:text-base text-[#b2b2b2] max-w-md mx-auto leading-relaxed">
                The page or essay you are looking for is unavailable or has been archived.
            </p>
            <div class="pt-4 flex justify-center gap-5">
                <Link
                    href="/"
                    class="px-6 py-3 bg-[#b46b35] text-xs uppercase tracking-[0.2em] text-white hover:bg-[#9c5a2b] transition-colors rounded"
                >
                    Return Home
                </Link>
                <Link
                    href="/blog"
                    class="px-6 py-3 border border-[#2c303c] text-xs uppercase tracking-[0.2em] text-[#b2b2b2] hover:text-white transition-colors rounded"
                >
                    Browse Journal
                </Link>
            </div>
        </section>
    </EditorialLayout>
</template>
