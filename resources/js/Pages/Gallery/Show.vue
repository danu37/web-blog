<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import SeoHead from '../../Components/SeoHead.vue';

const props = defineProps({
    gallery: {
        type: Object,
        required: true,
    },
});

const activeIndex = ref(null);

const openLightbox = (index) => {
    activeIndex.value = index;
};

const closeLightbox = () => {
    activeIndex.value = null;
};

const prevFrame = () => {
    if (activeIndex.value === null || !props.gallery.media.length) return;
    activeIndex.value = (activeIndex.value - 1 + props.gallery.media.length) % props.gallery.media.length;
};

const nextFrame = () => {
    if (activeIndex.value === null || !props.gallery.media.length) return;
    activeIndex.value = (activeIndex.value + 1) % props.gallery.media.length;
};

const onKeydown = (e) => {
    if (activeIndex.value === null) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevFrame();
    if (e.key === 'ArrowRight') nextFrame();
};

onMounted(() => window.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
    <SeoHead
        :title="gallery.title"
        :description="gallery.description"
        :image="gallery.media?.[0]?.url"
    />
    <EditorialLayout>
        <section class="max-w-7xl mx-auto px-5 sm:px-8 pt-16 pb-20">
            <div class="mb-8">
                <Link
                    href="/gallery"
                    class="text-xs uppercase tracking-[0.2em] text-[#b2b2b2] hover:text-[#b46b35]"
                >
                    &larr; All Galleries
                </Link>
            </div>

            <header class="border-b border-[#242732] pb-12 mb-14 space-y-4 max-w-3xl">
                <p v-if="gallery.published_at_formatted" class="text-xs uppercase tracking-[0.22em] text-[#b46b35]">
                    {{ gallery.published_at_formatted }}
                </p>
                <h1 class="text-4xl sm:text-5xl font-semibold text-white">
                    {{ gallery.title }}
                </h1>
                <p v-if="gallery.description" class="text-sm sm:text-base text-[#b2b2b2] leading-relaxed">
                    {{ gallery.description }}
                </p>
            </header>

            <div v-if="gallery.media?.length" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <figure v-for="(item, index) in gallery.media" :key="item.id" class="space-y-3">
                    <button
                        type="button"
                        class="block w-full overflow-hidden bg-[#1a1c23] border border-[#242732] aspect-[4/3] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35] cursor-zoom-in"
                        @click="openLightbox(index)"
                    >
                        <img
                            :src="item.url"
                            :alt="item.alt"
                            loading="lazy"
                            class="w-full h-full object-cover hover:scale-[1.02] transition-transform duration-700"
                        />
                    </button>
                    <figcaption v-if="item.caption" class="text-xs text-[#b2b2b2] tracking-wide">
                        {{ item.caption }}
                    </figcaption>
                </figure>
            </div>

            <div v-else class="py-24 text-center border border-[#242732] bg-[#1a1c23]/50 rounded-lg">
                <p class="text-2xl font-semibold text-white">No images in this gallery yet</p>
            </div>
        </section>

        <!-- Lightbox -->
        <div
            v-if="activeIndex !== null && gallery.media[activeIndex]"
            role="dialog"
            aria-modal="true"
            class="fixed inset-0 z-50 bg-[#0a0a0a]/95 flex items-center justify-center p-4 sm:p-10"
            @click.self="closeLightbox"
        >
            <button
                type="button"
                aria-label="Close lightbox"
                class="absolute top-6 right-6 text-xs uppercase tracking-[0.2em] text-[#b2b2b2] hover:text-white px-3.5 py-2 border border-[#2c303c] rounded"
                @click="closeLightbox"
            >
                Close
            </button>

            <button
                type="button"
                aria-label="Previous image"
                class="hidden sm:inline-flex px-4 py-3 text-xs uppercase tracking-[0.2em] text-[#b2b2b2] hover:text-white"
                @click="prevFrame"
            >
                Prev
            </button>

            <div class="max-w-5xl max-h-[85vh] flex flex-col items-center gap-4">
                <img
                    :src="gallery.media[activeIndex].url"
                    :alt="gallery.media[activeIndex].alt"
                    class="max-h-[75vh] w-auto object-contain border border-[#242732]"
                />
                <p v-if="gallery.media[activeIndex].caption" class="text-xs text-[#d6d6dc]">
                    {{ gallery.media[activeIndex].caption }}
                </p>
            </div>

            <button
                type="button"
                aria-label="Next image"
                class="hidden sm:inline-flex px-4 py-3 text-xs uppercase tracking-[0.2em] text-[#b2b2b2] hover:text-white"
                @click="nextFrame"
            >
                Next
            </button>
        </div>
    </EditorialLayout>
</template>
