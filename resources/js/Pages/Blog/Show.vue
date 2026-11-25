<script setup>
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import PostCard from '../../Components/PostCard.vue';
import SeoHead from '../../Components/SeoHead.vue';

defineProps({
    post: {
        type: Object,
        required: true,
    },
    relatedPosts: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <SeoHead
        :title="post.title"
        :description="post.excerpt"
        :image="post.featured_image?.url"
        type="article"
    />
    <EditorialLayout>
        <article class="max-w-5xl mx-auto px-5 sm:px-8 pt-16 pb-20">
            <header class="space-y-6 mb-12 text-center max-w-3xl mx-auto">
                <div class="flex items-center justify-center gap-3 text-[11px] uppercase tracking-[0.22em] text-[#a39e93]">
                    <Link
                        v-if="post.category"
                        :href="`/category/${post.category.slug}`"
                        class="text-[#c8a97e] hover:underline underline-offset-4"
                    >
                        {{ post.category.name }}
                    </Link>
                    <span v-if="post.category && post.published_at_formatted">/</span>
                    <time v-if="post.published_at_formatted" :datetime="post.published_at">
                        {{ post.published_at_formatted }}
                    </time>
                    <span>/</span>
                    <span>{{ post.reading_time }} min read</span>
                </div>

                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl leading-[1.08] text-[#f2efe9]">
                    {{ post.title }}
                </h1>

                <p v-if="post.excerpt" class="text-base sm:text-lg text-[#a39e93] leading-relaxed">
                    {{ post.excerpt }}
                </p>

                <p v-if="post.author" class="text-[11px] uppercase tracking-[0.22em] text-[#6f6b63]">
                    Words &amp; Photographs by {{ post.author.name }}
                </p>
            </header>

            <figure
                v-if="post.featured_image"
                class="mb-14 border border-[#1e1e22] bg-[#141416] overflow-hidden"
            >
                <img
                    :src="post.featured_image.url"
                    :alt="post.featured_image.alt"
                    class="w-full max-h-[75vh] object-cover"
                />
            </figure>

            <div
                class="prose-editorial max-w-2xl mx-auto"
                v-html="post.content"
            />

            <footer
                v-if="post.tags?.length"
                class="max-w-2xl mx-auto mt-14 pt-8 border-t border-[#1e1e22] flex flex-wrap items-center gap-3"
            >
                <span class="text-[11px] uppercase tracking-[0.22em] text-[#6f6b63] mr-2">
                    Tags
                </span>
                <Link
                    v-for="tag in post.tags"
                    :key="tag.slug"
                    :href="`/tag/${tag.slug}`"
                    class="px-3 py-1 border border-[#1e1e22] text-[11px] uppercase tracking-[0.18em] text-[#a39e93] hover:text-[#c8a97e] hover:border-[#c8a97e] transition-colors"
                >
                    #{{ tag.name }}
                </Link>
            </footer>
        </article>

        <section
            v-if="relatedPosts.length"
            class="max-w-7xl mx-auto px-5 sm:px-8 py-16 border-t border-[#1e1e22]"
        >
            <h2 class="text-[11px] uppercase tracking-[0.26em] text-[#a39e93] mb-10">
                Related Stories
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <PostCard v-for="item in relatedPosts" :key="item.id" :post="item" />
            </div>
        </section>
    </EditorialLayout>
</template>
