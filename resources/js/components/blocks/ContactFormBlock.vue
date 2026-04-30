<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { Mail, MapPin, Phone } from 'lucide-vue-next';

defineProps<{
    badge_text?: string;
    heading?: string;
    subtitle?: string;
    show_contact_info?: boolean;
    content?: Record<string, string>;
}>();

const page = usePage();

const form = useForm({
    name: '',
    email: '',
    message: '',
    'cf-turnstile-response': '',
});

function submit(): void {
    form.post('/contact', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-1/2 top-0 h-[400px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 30%, transparent), transparent)" />
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span v-if="badge_text" class="animate-fade-in inline-flex rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground">
                    {{ badge_text }}
                </span>
                <h1 class="animate-fade-up mt-6 text-5xl font-bold tracking-tight sm:text-6xl">{{ heading || "Let's talk" }}</h1>
                <p v-if="subtitle" class="animate-fade-up mt-4 text-lg text-muted-foreground" style="animation-delay: 0.1s">{{ subtitle }}</p>
            </div>

            <div class="mt-16 grid gap-12 lg:grid-cols-5">
                <div class="reveal lg:col-span-3">
                    <form @submit.prevent="submit" class="rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-8 sm:p-10">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium">Name</label>
                                <input id="name" v-model="form.name" type="text" required
                                    class="mt-2 block w-full rounded-xl border border-border bg-background px-4 py-3 text-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="Your name" />
                                <p v-if="form.errors.name" class="mt-1.5 text-sm text-destructive">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input id="email" v-model="form.email" type="email" required
                                    class="mt-2 block w-full rounded-xl border border-border bg-background px-4 py-3 text-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="you@company.com" />
                                <p v-if="form.errors.email" class="mt-1.5 text-sm text-destructive">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium">Message</label>
                                <textarea id="message" v-model="form.message" rows="5" required
                                    class="mt-2 block w-full rounded-xl border border-border bg-background px-4 py-3 text-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="Tell us about your project..." />
                                <p v-if="form.errors.message" class="mt-1.5 text-sm text-destructive">{{ form.errors.message }}</p>
                            </div>
                            <div v-if="page.props.turnstileSiteKey" class="cf-turnstile" :data-sitekey="page.props.turnstileSiteKey" />
                            <p v-if="form.errors['cf-turnstile-response']" class="text-sm text-destructive">{{ form.errors['cf-turnstile-response'] }}</p>
                            <button type="submit" :disabled="form.processing"
                                class="w-full rounded-xl bg-primary px-6 py-3 text-sm font-medium text-primary-foreground shadow-lg shadow-primary/25 transition-all hover:shadow-xl hover:shadow-primary/30 disabled:opacity-50">
                                {{ form.processing ? 'Sending...' : 'Send message' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div v-if="show_contact_info !== false" class="reveal reveal-delay-2 space-y-6 lg:col-span-2">
                    <div v-if="content?.contact_email" class="rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                            <Mail class="h-5 w-5 text-primary" />
                        </div>
                        <h3 class="mt-4 font-semibold">Email</h3>
                        <a :href="`mailto:${content.contact_email}`" class="mt-1 text-sm text-muted-foreground transition hover:text-primary">{{ content.contact_email }}</a>
                    </div>
                    <div v-if="content?.contact_phone" class="rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                            <Phone class="h-5 w-5 text-primary" />
                        </div>
                        <h3 class="mt-4 font-semibold">Phone</h3>
                        <p class="mt-1 text-sm text-muted-foreground">{{ content.contact_phone }}</p>
                    </div>
                    <div v-if="content?.contact_address" class="rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                            <MapPin class="h-5 w-5 text-primary" />
                        </div>
                        <h3 class="mt-4 font-semibold">Office</h3>
                        <div class="mt-1 text-sm text-muted-foreground" v-html="content.contact_address" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
