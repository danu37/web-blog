<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import EditorialLayout from '../Layouts/EditorialLayout.vue';
import PostCard from '../Components/PostCard.vue';
import SeoHead from '../Components/SeoHead.vue';

const props = defineProps({
    sections: {
        type: Array,
        default: () => [],
    },
});

const hasLeadingHero = computed(() => props.sections[0]?.type === 'hero');

const activeDigestCategory = ref('all');

const filteredDigestPosts = (section) => {
    const all = section.posts || [];
    if (activeDigestCategory.value === 'all') {
        return all;
    }
    return all.filter((post) => post.category?.slug === activeDigestCategory.value);
};
</script>

<template>
    <SeoHead />
    <EditorialLayout :transparent-header="hasLeadingHero">
        <template v-if="sections.length">
            <template v-for="section in sections" :key="section.id">
                <!-- Hero / Masthead -->
                <section
                    v-if="section.type === 'hero'"
                    class="relative min-h-[78vh] sm:min-h-[86vh] flex items-center justify-center overflow-hidden bg-[#121317]"
                >
                    <img
                        v-if="section.settings?.background_image"
                        :src="section.settings.background_image"
                        :alt="section.title"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                    <div
                        v-else
                        aria-hidden="true"
                        class="absolute inset-0 bg-gradient-to-b from-[#1a1c23] via-[#121317] to-[#121317]"
                    />
                    <div aria-hidden="true" class="absolute inset-0 bg-black/40" />

                    <div class="relative z-10 max-w-5xl mx-auto px-5 sm:px-8 pt-24 pb-16 text-center space-y-4">
                        <p
                            v-if="section.settings?.eyebrow"
                            class="text-xs uppercase tracking-[0.28em] text-[#b46b35] font-medium"
                        >
                            {{ section.settings.eyebrow }}
                        </p>
                        <h1 class="font-display font-normal text-[44px] sm:text-[68px] leading-tight tracking-[0.5px] uppercase text-white drop-shadow-[4px_10px_48px_rgba(0,0,0,0.37)]">
                            {{ section.title }}
                        </h1>
                        <p
                            v-if="section.description"
                            class="font-alegreya font-medium text-lg sm:text-[24px] text-white/95 max-w-2xl mx-auto leading-normal tracking-wide uppercase"
                        >
                            {{ section.description }}
                        </p>
                    </div>
                </section>

                <!-- Editorial Intro / Profile (Arched Portrait + Outer Arch Frame + Copy) -->
                <section
                    v-else-if="section.type === 'editorial_intro'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 sm:py-28"
                >
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="relative w-full max-w-sm aspect-[3/4] p-2.5 rounded-t-full rounded-b-lg border border-[#2c303c]">
                                <div class="w-full h-full rounded-t-full rounded-b-md bg-[#1a1c23] overflow-hidden shadow-2xl">
                                    <img
                                        v-if="section.settings?.portrait_image"
                                        :src="section.settings.portrait_image"
                                        :alt="section.title"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex flex-col items-center justify-center p-8 text-center space-y-2"
                                    >
                                        <span class="text-xs uppercase tracking-[0.24em] text-[#b46b35]">
                                            Editorial Profile
                                        </span>
                                        <span class="text-xs text-[#6e6e6e]">
                                            Set portrait_image in Homepage Sections
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-7 space-y-6">
                            <p class="text-base sm:text-lg font-medium text-[#b46b35] tracking-wide">
                                {{ section.settings?.eyebrow || 'Who We Are' }}
                            </p>
                            <h2 class="text-3xl sm:text-5xl font-semibold tracking-tight text-white uppercase">
                                {{ section.title }}
                            </h2>
                            <p v-if="section.description" class="text-base sm:text-lg text-white/90 leading-relaxed">
                                {{ section.description }}
                            </p>
                            <p
                                v-if="section.settings?.secondary_description"
                                class="text-base sm:text-lg text-[#b2b2b2] leading-relaxed"
                            >
                                {{ section.settings.secondary_description }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Selected Shop / Projects -->
                <section
                    v-else-if="section.type === 'projects'"
                    id="shop"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20 scroll-mt-24"
                >
                    <div class="text-center space-y-2 mb-12">
                        <p class="text-base text-[#b46b35] font-normal">
                            {{ section.settings?.eyebrow || 'Best Choice' }}
                        </p>
                        <h2 class="text-4xl sm:text-5xl font-semibold text-white">
                            {{ section.title }}
                        </h2>
                    </div>

                    <div v-if="section.items?.length" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            v-for="(item, idx) in section.items"
                            :key="idx"
                            class="bg-[#1a1c23] rounded-[12px] overflow-hidden p-6 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1"
                        >
                            <div>
                                <div class="aspect-[6/5] rounded-[12px] overflow-hidden bg-[#121317] mb-5">
                                    <img
                                        v-if="item.image_url"
                                        :src="item.image_url"
                                        :alt="item.title"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center text-[10px] uppercase tracking-[0.24em] text-[#6e6e6e]"
                                    >
                                        Edition Preview
                                    </div>
                                </div>

                                <div v-if="item.price_label || item.price_value" class="flex items-baseline gap-2 mb-3 text-lg font-semibold">
                                    <span v-if="item.price_label" class="text-[#b46b35]">
                                        {{ item.price_label }}
                                    </span>
                                    <span v-if="item.price_value" class="text-white">
                                        {{ item.price_value }}
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-semibold text-white leading-snug">
                                    {{ item.title }}
                                </h3>
                                <p v-if="item.description" class="text-sm text-[#b2b2b2] leading-relaxed mt-1.5">
                                    {{ item.description }}
                                </p>
                            </div>

                            <div v-if="item.link_url" class="pt-4 mt-4 border-t border-[#262934]">
                                <a
                                    :href="item.link_url"
                                    class="text-xs uppercase tracking-[0.18em] text-[#b46b35] hover:underline underline-offset-4"
                                >
                                    View Details &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Featured Article -->
                <section
                    v-else-if="section.type === 'featured_post'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20"
                >
                    <div class="text-center space-y-2 mb-12">
                        <p class="text-base text-[#b46b35]">
                            {{ section.settings?.eyebrow || 'Featured Story' }}
                        </p>
                        <h2 class="text-4xl sm:text-5xl font-semibold text-white">
                            {{ section.title }}
                        </h2>
                    </div>
                    <div
                        v-if="section.post"
                        class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center bg-[#1a1c23] border border-[#242732] rounded-[12px] p-6 sm:p-8"
                    >
                        <Link
                            :href="`/blog/${section.post.slug}`"
                            class="lg:col-span-7 block overflow-hidden rounded-lg bg-[#121317] aspect-[16/10] group"
                        >
                            <img
                                v-if="section.post.featured_image"
                                :src="section.post.featured_image.url"
                                :alt="section.post.featured_image.alt"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-xs uppercase tracking-[0.24em] text-[#6e6e6e]"
                            >
                                Featured Photograph
                            </div>
                        </Link>
                        <div class="lg:col-span-5 space-y-4">
                            <div class="flex items-center gap-3 text-xs text-[#b2b2b2]">
                                <span v-if="section.post.category" class="text-[#b46b35] font-medium">
                                    {{ section.post.category.name }}
                                </span>
                                <span v-if="section.post.published_at_formatted">
                                    {{ section.post.published_at_formatted }}
                                </span>
                            </div>
                            <h3 class="text-2xl sm:text-4xl font-semibold leading-tight text-white">
                                <Link :href="`/blog/${section.post.slug}`" class="hover:text-[#b46b35] transition-colors">
                                    {{ section.post.title }}
                                </Link>
                            </h3>
                            <p v-if="section.post.excerpt" class="text-sm sm:text-base text-[#b2b2b2] leading-relaxed">
                                {{ section.post.excerpt }}
                            </p>
                            <Link
                                :href="`/blog/${section.post.slug}`"
                                class="inline-block pt-2 text-xs uppercase tracking-[0.2em] text-[#b46b35] hover:underline underline-offset-4"
                            >
                                View Post &rarr;
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Editorial Article Grid (3:4 Portrait Cards + Load More) -->
                <section
                    v-else-if="section.type === 'article_grid'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-20"
                >
                    <div class="text-center space-y-2 mb-14">
                        <p class="text-base text-[#b46b35]">
                            {{ section.settings?.eyebrow || 'Our Blog' }}
                        </p>
                        <h2 class="text-4xl sm:text-5xl font-semibold text-white">
                            {{ section.title }}
                        </h2>
                    </div>

                    <div v-if="section.posts?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-16">
                        <PostCard
                            v-for="post in section.posts"
                            :key="post.id"
                            :post="post"
                            variant="portrait"
                        />
                    </div>
                    <p v-else class="text-center text-sm text-[#6e6e6e]">
                        No stories published yet.
                    </p>

                    <div class="mt-16 flex justify-center">
                        <Link
                            :href="section.settings?.cta_url || '/blog'"
                            class="inline-flex items-center justify-center w-60 h-[54px] border border-[#2c303c] rounded-[2px] text-base text-[#b2b2b2] hover:text-white hover:border-[#b46b35] transition-colors"
                        >
                            {{ section.settings?.cta_label || 'Load more' }}
                        </Link>
                    </div>
                </section>

                <!-- Gallery Strip (Edge-to-Edge Full-Width Strip) -->
                <section
                    v-else-if="section.type === 'gallery_strip'"
                    class="py-12 w-full overflow-hidden"
                >
                    <div v-if="section.items?.length" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                        <Link
                            v-for="item in section.items.slice(0, 6)"
                            :key="item.id"
                            :href="`/gallery/${item.gallery_slug}`"
                            class="group relative block aspect-square overflow-hidden bg-[#1a1c23]"
                        >
                            <img
                                :src="item.url"
                                :alt="item.alt"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-black/25 group-hover:bg-black/0 transition-colors" />
                        </Link>
                    </div>
                </section>

                <!-- Category Digest (Tabs + 2-Panel Feature & Compact Lists) -->
                <section
                    v-else-if="section.type === 'category_digest'"
                    class="max-w-7xl mx-auto px-5 sm:px-8 py-16"
                >
                    <div class="flex flex-wrap items-center gap-6 border-b border-[#242732] pb-3 mb-8">
                        <button
                            type="button"
                            :class="[
                                'text-base transition-colors pb-2 -mb-[13px] border-b-2',
                                activeDigestCategory === 'all'
                                    ? 'text-white border-[#b46b35] font-medium'
                                    : 'text-[#6e6e6e] border-transparent hover:text-[#b2b2b2]',
                            ]"
                            @click="activeDigestCategory = 'all'"
                        >
                            All
                        </button>
                        <button
                            v-for="cat in section.categories || []"
                            :key="cat.id"
                            type="button"
                            :class="[
                                'text-base transition-colors pb-2 -mb-[13px] border-b-2',
                                activeDigestCategory === cat.slug
                                    ? 'text-white border-[#b46b35] font-medium'
                                    : 'text-[#6e6e6e] border-transparent hover:text-[#b2b2b2]',
                            ]"
                            @click="activeDigestCategory = cat.slug"
                        >
                            {{ cat.name }}
                        </button>
                    </div>

                    <div v-if="filteredDigestPosts(section).length" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left Panel: Featured Story + 4 Compact Items -->
                        <div class="lg:col-span-7 border border-[#242732] bg-[#1a1c23]/35 p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-7 space-y-3.5">
                                <Link
                                    :href="`/blog/${filteredDigestPosts(section)[0].slug}`"
                                    class="block aspect-[16/10] overflow-hidden bg-[#121317]"
                                >
                                    <img
                                        v-if="filteredDigestPosts(section)[0].featured_image"
                                        :src="filteredDigestPosts(section)[0].featured_image.url"
                                        :alt="filteredDigestPosts(section)[0].featured_image.alt"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                    />
                                </Link>
                                <p class="text-xs text-[#6e6e6e]">
                                    {{ filteredDigestPosts(section)[0].published_at_formatted }}
                                </p>
                                <h3 class="text-lg font-medium text-white leading-snug">
                                    <Link
                                        :href="`/blog/${filteredDigestPosts(section)[0].slug}`"
                                        class="hover:text-[#b46b35] transition-colors"
                                    >
                                        {{ filteredDigestPosts(section)[0].title }}
                                    </Link>
                                </h3>
                                <p
                                    v-if="filteredDigestPosts(section)[0].excerpt"
                                    class="text-sm text-[#b2b2b2] leading-relaxed line-clamp-2"
                                >
                                    {{ filteredDigestPosts(section)[0].excerpt }}
                                </p>
                                <Link
                                    :href="`/blog/${filteredDigestPosts(section)[0].slug}`"
                                    class="inline-block text-sm text-white border-b border-white pb-0.5 hover:text-[#b46b35] hover:border-[#b46b35]"
                                >
                                    View Post
                                </Link>
                            </div>

                            <div class="md:col-span-5 space-y-4">
                                <div
                                    v-for="item in filteredDigestPosts(section).slice(1, 5)"
                                    :key="item.id"
                                    class="flex items-start gap-3.5 group"
                                >
                                    <Link
                                        :href="`/blog/${item.slug}`"
                                        class="w-24 h-18 shrink-0 overflow-hidden bg-[#121317] border border-[#242732]"
                                    >
                                        <img
                                            v-if="item.featured_image"
                                            :src="item.featured_image.url"
                                            :alt="item.featured_image.alt"
                                            loading="lazy"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                    </Link>
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-medium text-white group-hover:text-[#b46b35] transition-colors line-clamp-2">
                                            <Link :href="`/blog/${item.slug}`">{{ item.title }}</Link>
                                        </h4>
                                        <p class="text-xs text-[#6e6e6e]">
                                            {{ item.published_at_formatted }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Compact Featured Reads -->
                        <div class="lg:col-span-5 border border-[#242732] bg-[#1a1c23]/35 p-6 space-y-5">
                            <p class="text-base font-medium text-white">
                                {{ section.title }}
                            </p>
                            <div class="space-y-5">
                                <div
                                    v-for="item in filteredDigestPosts(section).slice(0, 3)"
                                    :key="`right-${item.id}`"
                                    class="flex items-start gap-4 group"
                                >
                                    <Link
                                        :href="`/blog/${item.slug}`"
                                        class="w-36 h-20 shrink-0 overflow-hidden bg-[#121317] border border-[#242732]"
                                    >
                                        <img
                                            v-if="item.featured_image"
                                            :src="item.featured_image.url"
                                            :alt="item.featured_image.alt"
                                            loading="lazy"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                    </Link>
                                    <div class="space-y-1.5">
                                        <h4 class="text-sm font-medium text-white group-hover:text-[#b46b35] transition-colors line-clamp-2">
                                            <Link :href="`/blog/${item.slug}`">{{ item.title }}</Link>
                                        </h4>
                                        <p class="text-xs text-[#6e6e6e]">
                                            {{ item.published_at_formatted }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-[#6e6e6e]">
                        No stories in this category yet.
                    </p>
                </section>
            </template>
        </template>

        <section v-else class="max-w-7xl mx-auto px-5 sm:px-8 py-36 text-center space-y-4">
            <h1 class="font-display font-normal text-5xl text-white uppercase">Editorial Journal</h1>
            <p class="text-sm text-[#b2b2b2]">
                Configure homepage sections in the admin panel to publish your front page.
            </p>
        </section>
    </EditorialLayout>
</template>
