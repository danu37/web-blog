<script setup>
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../Layouts/EditorialLayout.vue';
import PostCard from '../Components/PostCard.vue';
import SeoHead from '../Components/SeoHead.vue';

defineProps({
    sections: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <SeoHead />
    <EditorialLayout>
        <template v-if="sections.length">
            <template v-for="section in sections" :key="section.id">
                <!-- Hero / Masthead -->
                <section
                    v-if="section.type === 'hero'"
                    class="relative border-b border-[#1e1e22] overflow-hidden"
                >
                    <div
                        v-if="section.settings?.background_image"
                        class="absolute inset-0 opacity-30"
                    >
                        <img
                            :src="section.settings.background_image"
                            :alt="section.title"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-b from-[#0c0c0d]/50 via-[#0c0c0d]/60 to-[#0c0c0d]" />
                    </div>
                    <div class="relative max-w-7xl mx-auto px-5 sm:px-8 py-24 sm:py-36">
                        <p class="text-[11px] uppercase tracking-[0.28em] text-[#c8a97e] mb-6">
                            {{ section.settings?.eyebrow || 'Editorial & Photography' }}
                        </p>
                        <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-normal tracking-tight text-[#f2efe9] max-w-4xl leading-[1.06]">
                            {{ section.title }}
                        </h1>
                        <p
                            v-if="section.description"
                            class="mt-6 text-base sm:text-lg text-[#a39e93] max-w-2xl leading-relaxed"
                        >
                            {{ section.description }}
                        </p>
                        <div class="mt-10 flex flex-wrap gap-5">
                            <Link
                                href="/blog"
                                class="inline-flex items-center px-6 py-3 border border-[#c8a97e] text-[11px] uppercase tracking-[0.22em] text-[#f2efe9] hover:bg-[#c8a97e] hover:text-[#0c0c0d] transition-colors"
                            >
                                Read Journal
                            </Link>
                            <Link
                                href="/gallery"
                                class="inline-flex items-center px-6 py-3 border border-[#2a2a30] text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9] hover:border-[#55524b] transition-colors"
                            >
                                View Galleries
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Featured Article -->
                <section
                    v-else-if="section.type === 'featured_post'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 border-b border-[#1e1e22]"
                >
                    <p class="text-[11px] uppercase tracking-[0.26em] text-[#a39e93] mb-8">
                        {{ section.title }}
                    </p>
                    <div v-if="section.post" class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <Link
                            :href="`/blog/${section.post.slug}`"
                            class="lg:col-span-7 block overflow-hidden bg-[#141416] border border-[#1e1e22] aspect-[16/10] group"
                        >
                            <img
                                v-if="section.post.featured_image"
                                :src="section.post.featured_image.url"
                                :alt="section.post.featured_image.alt"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-[11px] uppercase tracking-[0.25em] text-[#55524b]"
                            >
                                Featured Photograph
                            </div>
                        </Link>
                        <div class="lg:col-span-5 space-y-5">
                            <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.2em] text-[#a39e93]">
                                <span v-if="section.post.category" class="text-[#c8a97e]">
                                    {{ section.post.category.name }}
                                </span>
                                <span v-if="section.post.published_at_formatted">
                                    {{ section.post.published_at_formatted }}
                                </span>
                            </div>
                            <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl leading-[1.12] text-[#f2efe9]">
                                <Link :href="`/blog/${section.post.slug}`" class="hover:text-[#c8a97e] transition-colors">
                                    {{ section.post.title }}
                                </Link>
                            </h2>
                            <p v-if="section.post.excerpt" class="text-sm sm:text-base text-[#a39e93] leading-relaxed">
                                {{ section.post.excerpt }}
                            </p>
                            <Link
                                :href="`/blog/${section.post.slug}`"
                                class="inline-block pt-2 text-[11px] uppercase tracking-[0.22em] text-[#c8a97e] hover:underline underline-offset-4"
                            >
                                Continue Reading &rarr;
                            </Link>
                        </div>
                    </div>
                    <p v-else class="text-sm text-[#6f6b63]">
                        No featured article published yet.
                    </p>
                </section>

                <!-- Editorial Intro / Profile -->
                <section
                    v-else-if="section.type === 'editorial_intro'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 border-b border-[#1e1e22]"
                >
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <p class="lg:col-span-4 text-[11px] uppercase tracking-[0.26em] text-[#c8a97e]">
                            {{ section.title }}
                        </p>
                        <div class="lg:col-span-8 space-y-6">
                            <p class="font-serif text-2xl sm:text-3xl leading-relaxed text-[#f2efe9]">
                                {{ section.description }}
                            </p>
                            <Link
                                href="/about"
                                class="inline-block text-[11px] uppercase tracking-[0.22em] text-[#a39e93] hover:text-[#f2efe9] underline underline-offset-4"
                            >
                                About the Studio
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Selected Projects / Shop -->
                <section
                    v-else-if="section.type === 'projects'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 border-b border-[#1e1e22]"
                >
                    <div class="flex items-end justify-between gap-4 mb-8">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.26em] text-[#a39e93]">
                                {{ section.title }}
                            </p>
                            <p v-if="section.description" class="mt-2 text-sm text-[#a39e93] max-w-xl">
                                {{ section.description }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="(val, key) in section.settings"
                            :key="key"
                            class="border border-[#1e1e22] bg-[#141416] p-6 space-y-2"
                        >
                            <p class="text-[11px] uppercase tracking-[0.22em] text-[#c8a97e]">{{ key }}</p>
                            <p class="text-sm text-[#d6d2c9]">{{ val }}</p>
                        </div>
                    </div>
                </section>

                <!-- Article Grid -->
                <section
                    v-else-if="section.type === 'article_grid'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 border-b border-[#1e1e22]"
                >
                    <div class="flex items-baseline justify-between gap-4 mb-12">
                        <h2 class="text-[11px] uppercase tracking-[0.26em] text-[#a39e93]">
                            {{ section.title }}
                        </h2>
                        <Link
                            href="/blog"
                            class="text-[11px] uppercase tracking-[0.22em] text-[#c8a97e] hover:underline underline-offset-4"
                        >
                            All Stories &rarr;
                        </Link>
                    </div>
                    <div v-if="section.posts?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
                        <PostCard v-for="post in section.posts" :key="post.id" :post="post" />
                    </div>
                    <p v-else class="text-sm text-[#6f6b63]">
                        No stories published yet.
                    </p>
                </section>

                <!-- Gallery Strip -->
                <section
                    v-else-if="section.type === 'gallery_strip'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20"
                >
                    <div class="flex items-baseline justify-between gap-4 mb-12">
                        <h2 class="text-[11px] uppercase tracking-[0.26em] text-[#a39e93]">
                            {{ section.title }}
                        </h2>
                        <Link
                            href="/gallery"
                            class="text-[11px] uppercase tracking-[0.22em] text-[#c8a97e] hover:underline underline-offset-4"
                        >
                            Explore Galleries &rarr;
                        </Link>
                    </div>
                    <div v-if="section.items?.length" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <Link
                            v-for="item in section.items"
                            :key="item.id"
                            :href="`/gallery/${item.gallery_slug}`"
                            class="group relative block aspect-[3/4] overflow-hidden bg-[#141416] border border-[#1e1e22]"
                        >
                            <img
                                :src="item.url"
                                :alt="item.alt"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
                            />
                        </Link>
                    </div>
                    <p v-else class="text-sm text-[#6f6b63]">
                        No gallery frames published yet.
                    </p>
                </section>
            </template>
        </template>

        <section v-else class="max-w-7xl mx-auto px-5 sm:px-8 py-32 text-center space-y-4">
            <h1 class="font-serif text-4xl text-[#f2efe9]">Editorial Journal</h1>
            <p class="text-sm text-[#a39e93]">
                Configure homepage sections in the admin panel to publish your front page.
            </p>
        </section>
    </EditorialLayout>
</template>
