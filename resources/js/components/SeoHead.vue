<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface SeoProps {
    title?: string | null;
    description?: string | null;
    canonical?: string | null;
    og_image?: string | null;
    type?: string | null;
    published_time?: string | null;
    modified_time?: string | null;
}

const props = defineProps<{
    seo?: SeoProps | null;
    jsonLd?: Record<string, unknown> | Array<Record<string, unknown>> | null;
}>();

const page = usePage();

const siteSettings = computed(() => (page.props.siteSettings as Record<string, unknown>) || {});
const appName = computed(() => (page.props.name as string) || '');
const defaultDescription = computed(
    () =>
        (siteSettings.value.brand_tagline as string) ||
        (siteSettings.value.default_meta_description as string) ||
        null,
);
const defaultOgImage = computed(() => {
    const raw = siteSettings.value.default_og_image as string | undefined;
    if (!raw) return null;
    return raw.startsWith('http') ? raw : `/storage/${raw}`;
});
const twitterHandle = computed(() => (siteSettings.value.twitter_handle as string) || null);

const title = computed(() => {
    const t = props.seo?.title || appName.value;
    return t === appName.value ? t : `${t} – ${appName.value}`;
});
const description = computed(() => props.seo?.description || defaultDescription.value);
const canonical = computed(() => props.seo?.canonical || (typeof window !== 'undefined' ? window.location.href : null));
const ogImage = computed(() => props.seo?.og_image || defaultOgImage.value);
const type = computed(() => props.seo?.type || 'website');

const organizationJsonLd = computed(() => {
    const data: Record<string, unknown> = {
        '@context': 'https://schema.org',
        '@type': 'Organization',
        name: appName.value,
    };
    if (canonical.value) data.url = new URL(canonical.value).origin;
    if (ogImage.value) data.logo = ogImage.value;
    return data;
});

const articleJsonLd = computed(() => {
    if (type.value !== 'article' || !props.seo) return null;
    const data: Record<string, unknown> = {
        '@context': 'https://schema.org',
        '@type': 'BlogPosting',
        headline: props.seo.title,
        mainEntityOfPage: canonical.value,
    };
    if (description.value) data.description = description.value;
    if (ogImage.value) data.image = ogImage.value;
    if (props.seo.published_time) data.datePublished = props.seo.published_time;
    if (props.seo.modified_time) data.dateModified = props.seo.modified_time;
    return data;
});

const allJsonLd = computed(() => {
    const blocks: Array<Record<string, unknown>> = [organizationJsonLd.value];
    if (articleJsonLd.value) blocks.push(articleJsonLd.value);
    if (props.jsonLd) {
        if (Array.isArray(props.jsonLd)) blocks.push(...props.jsonLd);
        else blocks.push(props.jsonLd);
    }
    return blocks;
});
</script>

<template>
    <Head :title="title">
        <meta v-if="description" name="description" :content="description" />
        <link v-if="canonical" rel="canonical" :href="canonical" />

        <meta property="og:site_name" :content="appName" />
        <meta property="og:type" :content="type" />
        <meta property="og:title" :content="title" />
        <meta v-if="description" property="og:description" :content="description" />
        <meta v-if="canonical" property="og:url" :content="canonical" />
        <meta v-if="ogImage" property="og:image" :content="ogImage" />
        <meta v-if="seo?.published_time" property="article:published_time" :content="seo.published_time" />
        <meta v-if="seo?.modified_time" property="article:modified_time" :content="seo.modified_time" />

        <meta name="twitter:card" :content="ogImage ? 'summary_large_image' : 'summary'" />
        <meta name="twitter:title" :content="title" />
        <meta v-if="description" name="twitter:description" :content="description" />
        <meta v-if="ogImage" name="twitter:image" :content="ogImage" />
        <meta v-if="twitterHandle" name="twitter:site" :content="twitterHandle" />

        <component
            :is="'script'"
            v-for="(block, i) in allJsonLd"
            :key="`ld-${i}`"
            type="application/ld+json"
            v-html="JSON.stringify(block)"
        />
    </Head>
</template>
