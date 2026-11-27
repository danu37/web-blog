<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import EditorialLayout from '../../Layouts/EditorialLayout.vue';
import SeoHead from '../../Components/SeoHead.vue';

defineProps({
    page: {
        type: Object,
        required: true,
    },
    isContact: {
        type: Boolean,
        default: false,
    },
});

const inertiaPage = usePage();
const site = computed(() => inertiaPage.props.site ?? {});
</script>

<template>
    <SeoHead :title="page.title" />
    <EditorialLayout>
        <section class="max-w-4xl mx-auto px-5 sm:px-8 pt-16 pb-24">
            <header class="border-b border-[#242732] pb-10 mb-12 space-y-3">
                <p class="text-sm font-medium text-[#b46b35]">
                    {{ isContact ? 'Inquiries' : 'Who We Are' }}
                </p>
                <h1 class="text-4xl sm:text-5xl font-semibold text-white">
                    {{ page.title }}
                </h1>
            </header>

            <div v-if="page.content" class="prose-editorial max-w-2xl" v-html="page.content" />

            <div
                v-if="isContact"
                class="mt-14 pt-10 border-t border-[#242732] grid grid-cols-1 sm:grid-cols-2 gap-8"
            >
                <div v-if="site.contactEmail" class="space-y-2 bg-[#1a1c23] border border-[#242732] rounded-lg p-6">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#b46b35]">Email</p>
                    <a
                        :href="`mailto:${site.contactEmail}`"
                        class="text-base text-white hover:text-[#b46b35] underline underline-offset-4"
                    >
                        {{ site.contactEmail }}
                    </a>
                </div>
                <div v-if="site.contactLocation" class="space-y-2 bg-[#1a1c23] border border-[#242732] rounded-lg p-6">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#b46b35]">Studio</p>
                    <p class="text-base text-[#d6d6dc]">{{ site.contactLocation }}</p>
                </div>
            </div>
        </section>
    </EditorialLayout>
</template>
