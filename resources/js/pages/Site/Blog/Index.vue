<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import BlockRenderer from '@/components/blocks/BlockRenderer.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

defineProps<{
    page: { title: string; blocks: Array<{ type: string; data: Record<string, unknown> }> } | null;
    posts: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            body: string;
            published_at: string;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
}>();

useScrollReveal();
</script>

<template>
    <Head title="Blog" />

    <div>
        <BlockRenderer v-if="page?.blocks" :blocks="page.blocks" />

        <!-- Post listing -->
        <section class="pb-24">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="space-y-5">
                    <Link
                        v-for="(post, idx) in posts.data"
                        :key="post.id"
                        :href="`/blog/${post.slug}`"
                        class="reveal group block rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-7 transition-all hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :class="`reveal-delay-${(idx % 3) + 1}`"
                    >
                        <time class="text-sm text-muted-foreground">
                            {{ new Date(post.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </time>
                        <h2 class="mt-2 text-xl font-semibold transition-colors group-hover:text-primary">
                            {{ post.title }}
                        </h2>
                        <span class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-primary">
                            Read more <ArrowRight class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" />
                        </span>
                    </Link>
                </div>

                <nav v-if="posts.links.length > 3" class="mt-12 flex justify-center gap-1">
                    <template v-for="link in posts.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="rounded-lg px-3.5 py-2 text-sm transition-colors"
                            :class="link.active ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-accent'"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="rounded-lg px-3.5 py-2 text-sm text-muted-foreground/40"
                            v-html="link.label"
                        />
                    </template>
                </nav>
            </div>
        </section>
    </div>
</template>
