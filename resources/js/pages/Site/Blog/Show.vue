<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import SeoHead from '@/components/SeoHead.vue';

defineProps<{
    post: {
        id: number;
        title: string;
        slug: string;
        body: string;
        published_at: string;
    };
    seo?: Record<string, unknown> | null;
}>();
</script>

<template>
    <SeoHead :seo="seo" />

    <div>
        <section class="relative overflow-hidden py-20 sm:py-28">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute left-1/2 top-0 h-[400px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 25%, transparent), transparent)" />
            </div>

            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <Link href="/blog" class="inline-flex items-center gap-1.5 text-sm text-muted-foreground transition hover:text-foreground">
                    <ArrowLeft class="h-3.5 w-3.5" /> Back to Blog
                </Link>

                <article class="mt-8">
                    <time class="text-sm text-muted-foreground">
                        {{ new Date(post.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </time>
                    <h1 class="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">{{ post.title }}</h1>
                    <div class="mt-10 rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-8 sm:p-10">
                        <div class="prose prose-lg max-w-none dark:prose-invert" v-html="post.body" />
                    </div>
                </article>
            </div>
        </section>
    </div>
</template>
