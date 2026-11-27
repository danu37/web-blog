<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
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
    latestPosts: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const site = computed(() => page.props.site ?? {});

const socialLinks = computed(() =>
    [
        { label: 'Instagram', url: site.value.instagramUrl },
        { label: 'YouTube', url: site.value.youtubeUrl },
        { label: 'X / Twitter', url: site.value.xUrl },
        { label: 'Facebook', url: site.value.facebookUrl },
        { label: 'Pinterest', url: site.value.pinterestUrl },
    ].filter((item) => Boolean(item.url)),
);
</script>

<template>
    <SeoHead
        :title="post.title"
        :description="post.excerpt"
        :image="post.featured_image?.url"
        type="article"
    />
    <EditorialLayout :transparent-header="true">
        <!-- Dark Hero Header matching Figma Blog Single -->
        <section class="relative min-h-[440px] sm:min-h-[540px] flex items-end overflow-hidden bg-[#121317] border-b border-[#1f222a]">
            <img
                v-if="post.featured_image"
                :src="post.featured_image.url"
                :alt="post.featured_image.alt"
                class="absolute inset-0 w-full h-full object-cover"
            />
            <div
                v-else
                aria-hidden="true"
                class="absolute inset-0 bg-gradient-to-b from-[#1a1c23] to-[#121317]"
            />
            <div aria-hidden="true" class="absolute inset-0 bg-black/45" />

            <div class="relative z-10 w-full max-w-7xl mx-auto px-5 sm:px-8 pt-28 pb-14 space-y-6">
                <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs text-white/80">
                    <Link href="/" class="hover:text-[#b46b35]">Home</Link>
                    <span aria-hidden="true">&rsaquo;</span>
                    <Link
                        v-if="post.category"
                        :href="`/category/${post.category.slug}`"
                        class="hover:text-[#b46b35]"
                    >
                        {{ post.category.name }}
                    </Link>
                    <Link v-else href="/blog" class="hover:text-[#b46b35]">Journal</Link>
                    <span aria-hidden="true">&rsaquo;</span>
                    <span class="text-white/60 truncate max-w-xs sm:max-w-md">{{ post.title }}</span>
                </nav>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold uppercase tracking-wide text-white max-w-4xl leading-[1.08] drop-shadow">
                    {{ post.title }}
                </h1>

                <p v-if="post.excerpt" class="text-lg sm:text-2xl text-white/90 max-w-3xl leading-snug">
                    {{ post.excerpt }}
                </p>

                <div class="flex flex-wrap items-center gap-4 text-sm text-white/85 pt-2">
                    <span v-if="post.author">by {{ post.author.name }}</span>
                    <span v-if="post.author" class="w-5 h-px bg-white/60" aria-hidden="true" />
                    <span>{{ post.reading_time }} minute read</span>
                    <template v-if="post.published_at_formatted">
                        <span class="w-5 h-px bg-white/60" aria-hidden="true" />
                        <time :datetime="post.published_at">{{ post.published_at_formatted }}</time>
                    </template>
                </div>
            </div>
        </section>

        <!-- Main Article Body + Sidebar -->
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            <article class="lg:col-span-8">
                <div class="prose-editorial" v-html="post.content" />

                <footer
                    v-if="post.tags?.length"
                    class="mt-14 pt-8 border-t border-[#242732] flex flex-wrap items-center gap-3"
                >
                    <span class="text-xs uppercase tracking-[0.2em] text-[#6e6e6e] mr-2">
                        Tags
                    </span>
                    <Link
                        v-for="tag in post.tags"
                        :key="tag.slug"
                        :href="`/tag/${tag.slug}`"
                        class="px-3 py-1 border border-[#242732] bg-[#1a1c23] text-xs uppercase tracking-[0.16em] text-[#b2b2b2] hover:text-[#b46b35] hover:border-[#b46b35] transition-colors rounded"
                    >
                        #{{ tag.name }}
                    </Link>
                </footer>
            </article>

            <!-- Sidebar: Follow Us + The Latest -->
            <aside class="lg:col-span-4 space-y-12">
                <div v-if="socialLinks.length" class="bg-[#1a1c23] border border-[#242732] rounded-lg p-6 space-y-5">
                    <h2 class="text-lg font-semibold text-white">Follow Us</h2>
                    <div class="flex flex-wrap gap-3">
                        <a
                            v-for="social in socialLinks"
                            :key="social.label"
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-3.5 py-2 border border-[#2c303c] rounded text-xs uppercase tracking-[0.16em] text-[#b2b2b2] hover:text-[#b46b35] hover:border-[#b46b35] transition-colors"
                        >
                            {{ social.label }}
                        </a>
                    </div>
                </div>

                <div v-if="latestPosts.length" class="space-y-5">
                    <h2 class="text-lg font-semibold text-white">
                        The Latest
                    </h2>
                    <div class="space-y-4">
                        <Link
                            v-for="item in latestPosts"
                            :key="item.id"
                            :href="`/blog/${item.slug}`"
                            class="group relative block h-36 rounded overflow-hidden bg-[#1a1c23] border border-[#242732]"
                        >
                            <img
                                v-if="item.featured_image"
                                :src="item.featured_image.url"
                                :alt="item.featured_image.alt"
                                loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-black/55 group-hover:bg-black/45 transition-colors" />
                            <div class="relative z-10 h-full p-4 flex flex-col justify-between">
                                <h3 class="text-sm font-medium text-white group-hover:text-[#b46b35] transition-colors line-clamp-2">
                                    {{ item.title }}
                                </h3>
                                <p class="text-xs text-white/80">
                                    {{ item.published_at_formatted }} &mdash; {{ item.reading_time }} minute read
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </aside>
        </div>

        <!-- You May Also Like -->
        <section
            v-if="relatedPosts.length"
            class="max-w-7xl mx-auto px-5 sm:px-8 py-16 border-t border-[#242732]"
        >
            <h2 class="text-xl sm:text-2xl font-semibold uppercase tracking-wide text-white mb-10">
                You May Also Like
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <PostCard v-for="item in relatedPosts" :key="item.id" :post="item" />
            </div>
        </section>
    </EditorialLayout>
</template>
