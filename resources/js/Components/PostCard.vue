<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="group flex flex-col">
        <Link
            :href="`/blog/${post.slug}`"
            class="block overflow-hidden bg-[#141416] border border-[#1e1e22] aspect-[4/3] mb-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c8a97e]"
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
                class="w-full h-full flex items-center justify-center text-[10px] uppercase tracking-[0.25em] text-[#55524b]"
            >
                No Photograph
            </div>
        </Link>

        <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.2em] text-[#a39e93] mb-2.5">
            <Link
                v-if="post.category"
                :href="`/category/${post.category.slug}`"
                class="text-[#c8a97e] hover:underline underline-offset-4"
            >
                {{ post.category.name }}
            </Link>
            <span v-if="post.category && post.published_at_formatted" aria-hidden="true">/</span>
            <time v-if="post.published_at_formatted" :datetime="post.published_at">
                {{ post.published_at_formatted }}
            </time>
        </div>

        <h3 class="font-serif text-2xl leading-snug text-[#f2efe9] group-hover:text-[#c8a97e] transition-colors mb-2.5">
            <Link :href="`/blog/${post.slug}`" class="focus:outline-none focus-visible:underline">
                {{ post.title }}
            </Link>
        </h3>

        <p v-if="post.excerpt" class="text-sm text-[#a39e93] leading-relaxed line-clamp-3">
            {{ post.excerpt }}
        </p>
    </article>
</template>
