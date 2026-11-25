<script setup>
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import Pagination from '../../Components/Pagination.vue';
import SeoHead from '../../Components/SeoHead.vue';

defineProps({
    galleries: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <SeoHead title="Galleries" description="Curated photography series and visual monographs." />
    <EditorialLayout>
        <section class="max-w-7xl mx-auto px-5 sm:px-8 pt-16 pb-20">
            <header class="border-b border-[#1e1e22] pb-12 mb-12 space-y-4 max-w-2xl">
                <p class="text-[11px] uppercase tracking-[0.26em] text-[#c8a97e]">
                    Visual Series
                </p>
                <h1 class="font-serif text-4xl sm:text-5xl text-[#f2efe9]">
                    Galleries
                </h1>
                <p class="text-sm sm:text-base text-[#a39e93] leading-relaxed">
                    Sequenced photographic portfolios and monochrome studies.
                </p>
            </header>

            <div v-if="galleries.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <article v-for="gallery in galleries.data" :key="gallery.id" class="group">
                    <Link
                        :href="`/gallery/${gallery.slug}`"
                        class="block overflow-hidden bg-[#141416] border border-[#1e1e22] aspect-[4/3] mb-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e]"
                    >
                        <img
                            v-if="gallery.cover_image"
                            :src="gallery.cover_image.url"
                            :alt="gallery.cover_image.alt"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center text-[10px] uppercase tracking-[0.25em] text-[#55524b]"
                        >
                            Empty Series
                        </div>
                    </Link>

                    <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.2em] text-[#a39e93] mb-2">
                        <span>{{ gallery.media_count }} Frames</span>
                        <span v-if="gallery.published_at_formatted">{{ gallery.published_at_formatted }}</span>
                    </div>

                    <h2 class="font-serif text-2xl text-[#f2efe9] group-hover:text-[#c8a97e] transition-colors mb-2">
                        <Link :href="`/gallery/${gallery.slug}`">
                            {{ gallery.title }}
                        </Link>
                    </h2>

                    <p v-if="gallery.description" class="text-sm text-[#a39e93] line-clamp-2">
                        {{ gallery.description }}
                    </p>
                </article>
            </div>

            <div v-else class="py-24 text-center border border-[#1e1e22] bg-[#141416]/50">
                <p class="font-serif text-2xl text-[#f2efe9]">No galleries published yet</p>
            </div>

            <Pagination v-if="galleries.links" :links="galleries.links" />
        </section>
    </EditorialLayout>
</template>
