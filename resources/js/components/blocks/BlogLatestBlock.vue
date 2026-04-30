<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

defineProps<{
    badge_text?: string;
    heading?: string;
    count?: number;
    latestPosts?: Array<{
        id: number;
        title: string;
        slug: string;
        body: string;
        published_at: string;
    }>;
}>();
</script>

<template>
    <section v-if="latestPosts?.length" class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal flex items-end justify-between">
                <div>
                    <span v-if="badge_text" class="inline-flex rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground">
                        {{ badge_text }}
                    </span>
                    <h2 class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl">{{ heading || 'Latest insights' }}</h2>
                </div>
                <Link href="/blog" class="hidden items-center gap-1.5 text-sm font-medium text-primary hover:underline sm:inline-flex">
                    View all <ArrowRight class="h-3.5 w-3.5" />
                </Link>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="(post, idx) in latestPosts"
                    :key="post.id"
                    :href="`/blog/${post.slug}`"
                    class="reveal group rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-7 transition-all hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                    :class="`reveal-delay-${idx + 1}`"
                >
                    <time class="text-sm text-muted-foreground">
                        {{ new Date(post.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </time>
                    <h3 class="mt-3 text-lg font-semibold transition-colors group-hover:text-primary">{{ post.title }}</h3>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary">
                        Read more <ArrowRight class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" />
                    </span>
                </Link>
            </div>

            <div class="mt-8 text-center sm:hidden">
                <Link href="/blog" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline">
                    View all posts <ArrowRight class="h-3.5 w-3.5" />
                </Link>
            </div>
        </div>
    </section>
</template>
