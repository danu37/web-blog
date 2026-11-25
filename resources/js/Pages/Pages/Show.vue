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
            <header class="border-b border-[#1e1e22] pb-10 mb-12 space-y-4">
                <p class="text-[11px] uppercase tracking-[0.26em] text-[#c8a97e]">
                    {{ isContact ? 'Inquiries' : 'Profile' }}
                </p>
                <h1 class="font-serif text-4xl sm:text-5xl text-[#f2efe9]">
                    {{ page.title }}
                </h1>
            </header>

            <div v-if="page.content" class="prose-editorial max-w-2xl" v-html="page.content" />

            <div
                v-if="isContact"
                class="mt-14 pt-10 border-t border-[#1e1e22] grid grid-cols-1 sm:grid-cols-2 gap-8"
            >
                <div v-if="site.contactEmail" class="space-y-2">
                    <p class="text-[11px] uppercase tracking-[0.22em] text-[#6f6b63]">Email</p>
                    <a
                        :href="`mailto:${site.contactEmail}`"
                        class="text-base text-[#f2efe9] hover:text-[#c8a97e] underline underline-offset-4"
                    >
                        {{ site.contactEmail }}
                    </a>
                </div>
                <div v-if="site.contactLocation" class="space-y-2">
                    <p class="text-[11px] uppercase tracking-[0.22em] text-[#6f6b63]">Studio</p>
                    <p class="text-base text-[#d6d2c9]">{{ site.contactLocation }}</p>
                </div>
            </div>
        </section>
    </EditorialLayout>
</template>
