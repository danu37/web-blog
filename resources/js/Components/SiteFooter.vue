<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const site = computed(() => page.props.site ?? {});

const socialLinks = computed(() =>
    [
        { label: 'Instagram', url: site.value.instagramUrl },
        { label: 'YouTube', url: site.value.youtubeUrl },
        { label: 'X', url: site.value.xUrl },
        { label: 'Facebook', url: site.value.facebookUrl },
        { label: 'Pinterest', url: site.value.pinterestUrl },
    ].filter((item) => Boolean(item.url)),
);
</script>

<template>
    <footer class="bg-[#0a0a0a] border-t border-[#1f222a] mt-20">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
            <div class="space-y-2">
                <p class="text-sm font-semibold tracking-[0.16em] uppercase text-white">
                    {{ site.name || 'GARUDA STERIDA' }}
                </p>
                <p class="text-sm text-[#b2b2b2] max-w-md leading-relaxed">
                    {{ site.footerText }}
                </p>
                <p class="text-xs text-[#6e6e6e]">
                    &copy; {{ site.year || new Date().getFullYear() }} {{ site.name || 'GARUDA STERIDA' }}
                </p>
            </div>

            <div class="flex flex-col md:items-end gap-4">
                <div class="flex flex-wrap gap-6">
                    <Link href="/" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Home
                    </Link>
                    <Link href="/blog" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Sejarah
                    </Link>
                    <Link href="/gallery" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Chant
                    </Link>
                    <Link href="/#shop" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Produk
                    </Link>
                    <Link href="/about" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Tentang Kami
                    </Link>
                    <Link href="/contact" class="text-xs uppercase tracking-[0.18em] text-[#b2b2b2] hover:text-white">
                        Kontak
                    </Link>
                </div>

                <div v-if="socialLinks.length" class="flex flex-wrap gap-5">
                    <a
                        v-for="social in socialLinks"
                        :key="social.label"
                        :href="social.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-xs uppercase tracking-[0.18em] text-[#b46b35] hover:text-white transition-colors"
                    >
                        {{ social.label }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
