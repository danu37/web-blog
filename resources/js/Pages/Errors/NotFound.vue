<script setup>
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import SeoHead from '../../Components/SeoHead.vue';
</script>

<template>
    <SeoHead title="Page Not Found" />
    <EditorialLayout>
        <section class="max-w-3xl mx-auto px-5 sm:px-8 py-32 text-center space-y-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-[#c8a97e]">404</p>
            <h1 class="font-serif text-4xl sm:text-6xl text-[#f2efe9]">
                Frame Not Found
            </h1>
            <p class="text-sm sm:text-base text-[#a39e93] max-w-md mx-auto leading-relaxed">
                The page or essay you are looking for is unavailable or has been archived.
            </p>
            <div class="pt-4 flex justify-center gap-5">
                <Link
                    href="/"
                    class="px-6 py-3 border border-[#c8a97e] text-[11px] uppercase tracking-[0.22em] text-[#f2efe9] hover:bg-[#c8a97e] hover:text-[#0c0c0d] transition-colors"
                >
                    Return Home
                </Link>
                <Link
                    href="/blog"
                    class="px-6 py-3 border border-[#2a2a30] text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9] transition-colors"
                >
                    Browse Journal
                </Link>
            </div>
        </section>
    </EditorialLayout>
</template>
