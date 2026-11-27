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
            <header class="border-b border-[#242732] pb-12 mb-12 space-y-3 max-w-2xl">
                <p class="text-sm font-medium text-[#b46b35]">
                    Visual Series
                </p>
                <h1 class="text-4xl sm:text-5xl font-semibold text-white">
                    Galleries
                </h1>
                <p class="text-sm sm:text-base text-[#b2b2b2] leading-relaxed">
                    Sequenced photographic portfolios and monochrome studies.
                </p>
            </header>

            <div v-if="galleries.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <article v-for="gallery in galleries.data" :key="gallery.id" class="group">
                    <Link
                        :href="`/gallery/${gallery.slug}`"
                        class="block overflow-hidden bg-[#1a1c23] border border-[#242732] aspect-[4/3] mb-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]"
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
                            class="w-full h-full flex items-center justify-center text-[10px] uppercase tracking-[0.25em] text-[#6e6e6e]"
                        >
                            Empty Series
                        </div>
                    </Link>

                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.18em] text-[#b2b2b2] mb-2">
                        <span class="text-[#b46b35]">{{ gallery.media_count }} Frames</span>
                        <span v-if="gallery.published_at_formatted">{{ gallery.published_at_formatted }}</span>
                    </div>

                    <h2 class="text-xl font-semibold text-white group-hover:text-[#b46b35] transition-colors mb-2">
                        <Link :href="`/gallery/${gallery.slug}`">
                            {{ gallery.title }}
                        </Link>
                    </h2>

                    <p v-if="gallery.description" class="text-sm text-[#b2b2b2] line-clamp-2">
                        {{ gallery.description }}
                    </p>
                </article>
            </div>

            <div v-else class="py-24 text-center border border-[#242732] bg-[#1a1c23]/50 rounded-lg">
                <p class="text-2xl font-semibold text-white">No galleries published yet</p>
            </div>

            <Pagination v-if="galleries.links" :links="galleries.links" />
        </section>
    </EditorialLayout>
</template>
