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
            <header class="border-b border-[#1e1e22] pb-12 mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <p class="text-[11px] uppercase tracking-[0.26em] text-[#c8a97e]">
                        Archive
                    </p>
                    <h1 class="font-serif text-4xl sm:text-5xl text-[#f2efe9]">
                        {{ archiveTitle }}
                    </h1>
                    <p v-if="archiveDescription" class="text-sm sm:text-base text-[#a39e93] leading-relaxed">
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
                        class="w-full bg-[#141416] border border-[#2a2a30] px-4 py-2.5 text-sm text-[#f2efe9] placeholder-[#6f6b63] focus:outline-none focus:border-[#c8a97e]"
                    />
                    <button
                        type="submit"
                        class="px-4 py-2.5 border border-[#2a2a30] text-[11px] uppercase tracking-[0.2em] text-[#d6d2c9] hover:border-[#c8a97e] hover:text-[#c8a97e] transition-colors"
                    >
                        Find
                    </button>
                </form>
            </header>

            <div v-if="categories.length" class="flex flex-wrap gap-3 mb-12">
                <Link
                    href="/blog"
                    :class="[
                        'px-3.5 py-1.5 text-[11px] uppercase tracking-[0.2em] border transition-colors',
                        !activeCategory && !activeTag
                            ? 'border-[#c8a97e] text-[#c8a97e]'
                            : 'border-[#1e1e22] text-[#a39e93] hover:text-[#f2efe9]',
                    ]"
                >
                    All
                </Link>
                <Link
                    v-for="cat in categories"
                    :key="cat.id"
                    :href="`/category/${cat.slug}`"
                    :class="[
                        'px-3.5 py-1.5 text-[11px] uppercase tracking-[0.2em] border transition-colors',
                        activeCategory === cat.slug
                            ? 'border-[#c8a97e] text-[#c8a97e]'
                            : 'border-[#1e1e22] text-[#a39e93] hover:text-[#f2efe9]',
                    ]"
                >
                    {{ cat.name }}
                </Link>
            </div>

            <div v-if="posts.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
                <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
            </div>

            <div v-else class="py-24 text-center border border-[#1e1e22] bg-[#141416]/50 space-y-4">
                <p class="font-serif text-2xl text-[#f2efe9]">No stories found</p>
                <p class="text-sm text-[#a39e93]">
                    Try another keyword or browse all published entries.
                </p>
                <Link
                    href="/blog"
                    class="inline-block text-[11px] uppercase tracking-[0.22em] text-[#c8a97e] hover:underline underline-offset-4"
                >
                    Reset Archive
                </Link>
            </div>

            <Pagination v-if="posts.links" :links="posts.links" />
        </section>
    </EditorialLayout>
</template>
