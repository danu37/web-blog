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
    ].filter((item) => Boolean(item.url)),
);
</script>

<template>
    <footer class="border-t border-[#1e1e22] bg-[#0c0c0d] mt-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-14 grid grid-cols-1 md:grid-cols-3 gap-10 items-start">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.26em] text-[#f2efe9]">
                    {{ site.name || 'NOIR & FRAME' }}
                </p>
                <p class="text-sm text-[#a39e93] max-w-sm leading-relaxed">
                    {{ site.footerText }}
                </p>
            </div>

            <div class="flex flex-wrap gap-6 md:justify-center">
                <Link href="/blog" class="text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9]">
                    Journal
                </Link>
                <Link href="/gallery" class="text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9]">
                    Galleries
                </Link>
                <Link href="/about" class="text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9]">
                    About
                </Link>
                <Link href="/contact" class="text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9]">
                    Contact
                </Link>
            </div>

            <div class="flex flex-col md:items-end gap-3">
                <div v-if="socialLinks.length" class="flex flex-wrap gap-5">
                    <a
                        v-for="social in socialLinks"
                        :key="social.label"
                        :href="social.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#c8a97e]"
                    >
                        {{ social.label }}
                    </a>
                </div>
                <p class="text-xs text-[#6f6b63]">
                    &copy; {{ site.year || new Date().getFullYear() }} {{ site.name || 'NOIR & FRAME' }}
                </p>
            </div>
        </div>
    </footer>
</template>
