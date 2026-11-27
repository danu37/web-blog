<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import PostCard from '../../Components/PostCard.vue';
import Pagination from '../../Components/Pagination.vue';
import SeoHead from '../../Components/SeoHead.vue';

const props = defineProps({
    posts: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    activeCategory: {
        type: String,
        default: null,
    },
    activeTag: {
        type: String,
        default: null,
    },
    filters: {
        type: Object,
        default: () => ({ q: '' }),
    },
    archiveTitle: {
        type: String,
        default: 'Journal',
    },
    archiveDescription: {
        type: String,
        default: '',
    },
});

const search = ref(props.filters.q || '');

const submitSearch = () => {
    router.get('/blog', search.value ? { q: search.value } : {}, { preserveState: true });
};
</script>

<template>
    <SeoHead :title="archiveTitle" :description="archiveDescription" />
    <EditorialLayout>
        <section class="max-w-7xl mx-auto px-5 sm:px-8 pt-16 pb-20">
            <header class="border-b border-[#242732] pb-12 mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="space-y-3 max-w-2xl">
                    <p class="text-sm font-medium text-[#b46b35]">
                        Our Blog
                    </p>
                    <h1 class="text-4xl sm:text-5xl font-semibold text-white">
                        {{ archiveTitle }}
                    </h1>
                    <p v-if="archiveDescription" class="text-sm sm:text-base text-[#b2b2b2] leading-relaxed">
                        {{ archiveDescription }}
                    </p>
                </div>

                <form @submit.prevent="submitSearch" class="flex items-center gap-2 w-full lg:w-80">
                    <label for="journal-search" class="sr-only">Search articles</label>
                    <input
                        id="journal-search"
                        v-model="search"
                        type="search"
                        placeholder="Search stories..."
                        class="w-full bg-[#1a1c23] border border-[#2c303c] rounded px-4 py-2.5 text-sm text-white placeholder-[#6e6e6e] focus:outline-none focus:border-[#b46b35]"
                    />
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#b46b35] text-white text-xs uppercase tracking-[0.18em] rounded hover:bg-[#9c5a2b] transition-colors"
                    >
                        Find
                    </button>
                </form>
            </header>

            <div v-if="categories.length" class="flex flex-wrap gap-3 mb-12">
                <Link
                    href="/blog"
                    :class="[
                        'px-4 py-1.5 text-xs uppercase tracking-[0.18em] border rounded transition-colors',
                        !activeCategory && !activeTag
                            ? 'border-[#b46b35] text-[#b46b35] bg-[#1a1c23]'
                            : 'border-[#242732] text-[#b2b2b2] hover:text-white',
                    ]"
                >
                    All
                </Link>
                <Link
                    v-for="cat in categories"
                    :key="cat.id"
                    :href="`/category/${cat.slug}`"
                    :class="[
                        'px-4 py-1.5 text-xs uppercase tracking-[0.18em] border rounded transition-colors',
                        activeCategory === cat.slug
                            ? 'border-[#b46b35] text-[#b46b35] bg-[#1a1c23]'
                            : 'border-[#242732] text-[#b2b2b2] hover:text-white',
                    ]"
                >
                    {{ cat.name }}
                </Link>
            </div>

            <div v-if="posts.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
                <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
            </div>

            <div v-else class="py-24 text-center border border-[#242732] bg-[#1a1c23]/50 rounded-lg space-y-4">
                <p class="text-2xl font-semibold text-white">No stories found</p>
                <p class="text-sm text-[#b2b2b2]">
                    Try another keyword or browse all published entries.
                </p>
                <Link
                    href="/blog"
                    class="inline-block text-xs uppercase tracking-[0.2em] text-[#b46b35] hover:underline underline-offset-4"
                >
                    Reset Archive
                </Link>
            </div>

            <Pagination v-if="posts.links" :links="posts.links" />
        </section>
    </EditorialLayout>
</template>
