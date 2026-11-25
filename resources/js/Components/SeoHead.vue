<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    image: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'website',
    },
});

const page = usePage();
const site = computed(() => page.props.site ?? {});
const currentUrl = computed(() => page.props.currentUrl ?? '');

const fullTitle = computed(() => {
    if (!props.title) {
        return site.value.name || 'NOIR & FRAME';
    }
    return `${props.title} — ${site.value.name || 'NOIR & FRAME'}`;
});

const metaDescription = computed(
    () => props.description || site.value.description || 'Editorial photography journal.',
);
</script>

<template>
    <Head :title="fullTitle">
        <meta name="description" :content="metaDescription" />
        <link v-if="currentUrl" rel="canonical" :href="currentUrl" />
        <link v-if="site.faviconUrl" rel="icon" :href="site.faviconUrl" />
        <meta property="og:title" :content="fullTitle" />
        <meta property="og:description" :content="metaDescription" />
        <meta property="og:type" :content="type" />
        <meta v-if="currentUrl" property="og:url" :content="currentUrl" />
        <meta v-if="image" property="og:image" :content="image" />
    </Head>
</template>
