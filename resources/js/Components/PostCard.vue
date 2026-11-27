<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: {
        type: Object,
        required: true,
    },
    variant: {
        type: String,
        default: 'default', // 'default' (4:3) or 'portrait' (3:4)
    },
});
</script>

<template>
    <article class="group flex flex-col">
        <Link
            :href="`/blog/${post.slug}`"
            :class="[
                'relative block overflow-hidden bg-[#1a1c23] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#b46b35]',
                variant === 'portrait'
                    ? 'aspect-[3/4] mb-7'
                    : 'aspect-[4/3] border border-[#222530] mb-5',
            ]"
        >
            <img
                v-if="post.featured_image"
                :src="post.featured_image.url"
                :alt="post.featured_image.alt"
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-[10px] uppercase tracking-[0.25em] text-[#6e6e6e]"
            >
                No Photograph
            </div>

            <div
                v-if="post.category && variant === 'default'"
                class="absolute top-3 left-3 flex items-center gap-1.5"
            >
                <span class="px-2.5 py-1 rounded bg-white/20 backdrop-blur-md text-xs text-white">
                    {{ post.category.name }}
                </span>
            </div>
        </Link>

        <template v-if="variant === 'portrait'">
            <h3 class="text-lg sm:text-xl font-medium leading-snug text-white group-hover:text-[#b46b35] transition-colors mb-2.5">
                <Link :href="`/blog/${post.slug}`" class="focus:outline-none focus-visible:underline">
                    {{ post.title }}
                </Link>
            </h3>
            <p v-if="post.excerpt" class="text-sm sm:text-base text-[#d6d6dc] leading-[1.6] line-clamp-4">
                {{ post.excerpt }}
            </p>
        </template>

        <template v-else>
            <h3 class="text-xl font-semibold leading-snug text-white group-hover:text-[#b46b35] transition-colors mb-2">
                <Link :href="`/blog/${post.slug}`" class="focus:outline-none focus-visible:underline">
                    {{ post.title }}
                </Link>
            </h3>
            <p v-if="post.excerpt" class="text-sm text-[#b2b2b2] leading-relaxed line-clamp-3 mb-4">
                {{ post.excerpt }}
            </p>
            <div class="mt-auto flex items-center gap-3 text-xs text-[#b2b2b2]">
                <time v-if="post.published_at_formatted" :datetime="post.published_at">
                    {{ post.published_at_formatted }}
                </time>
                <span v-if="post.published_at_formatted && post.reading_time" class="w-4 h-px bg-[#6e6e6e]" aria-hidden="true" />
                <span v-if="post.reading_time" class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#b46b35]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                    </svg>
                    {{ post.reading_time }} minute read
                </span>
            </div>
        </template>
    </article>
</template>
