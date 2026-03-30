<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';

const props = defineProps({
    websitesEndpoint: {
        type: String,
        required: true,
    },
});

const loading = ref(true);
const saving = ref(false);
const websites = ref([]);
const selectedWebsiteId = ref(null);
const successMessage = ref('');
const form = reactive({
    email: '',
});
const errors = reactive({
    email: '',
    general: '',
});

const selectedWebsite = computed(() =>
    websites.value.find((website) => website.id === selectedWebsiteId.value) ?? null
);

async function loadWebsites() {
    loading.value = true;
    errors.general = '';

    try {
        const { data } = await axios.get(props.websitesEndpoint);
        websites.value = data;
        selectedWebsiteId.value = data[0]?.id ?? null;
    } catch {
        errors.general = 'Unable to load websites right now. Please refresh and try again.';
    } finally {
        loading.value = false;
    }
}

async function subscribe() {
    if (!selectedWebsite.value) {
        errors.general = 'Select a website before subscribing.';
        return;
    }

    saving.value = true;
    successMessage.value = '';
    errors.email = '';
    errors.general = '';

    try {
        const response = await axios.post(
            `/api/v1/websites/${selectedWebsite.value.id}/subscribe`,
            { email: form.email }
        );

        successMessage.value = response.data.message;
        form.email = '';

        const match = websites.value.find((website) => website.id === selectedWebsite.value.id);
        if (match && response.status === 201) {
            match.subscriptions_count += 1;
        }
    } catch (error) {
        if (error.response?.status === 422) {
            errors.email = error.response.data.errors?.email?.[0] ?? 'Please enter a valid email address.';
            return;
        }

        errors.general = 'Subscription failed. Please try again in a moment.';
    } finally {
        saving.value = false;
    }
}

onMounted(loadWebsites);
</script>

<template>
    <main class="min-h-screen px-4 py-8 text-slate-900 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <section class="mb-8 overflow-hidden rounded-[2rem] border border-[color:var(--line)] bg-[color:var(--panel)] shadow-[0_24px_80px_rgba(120,53,15,0.12)] backdrop-blur">
                <div class="grid gap-8 px-6 py-8 lg:grid-cols-[1.4fr_0.9fr] lg:px-10 lg:py-10">
                    <div>
                        <p class="mb-3 inline-flex rounded-full border border-amber-300/80 bg-amber-100/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-amber-900">
                            Laravel + Vue Demo
                        </p>
                        <h1 class="max-w-2xl text-4xl font-bold tracking-tight text-stone-900 sm:text-5xl">
                            Manage subscriptions for every website in one simple screen.
                        </h1>
                        <p class="mt-4 max-w-2xl text-base leading-8 text-stone-600 sm:text-lg">
                            Users can pick a website, subscribe with an email address, and rely on queued notifications whenever a new post is published.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4 text-sm text-stone-600">
                            <div class="rounded-full border border-amber-200 bg-white/80 px-4 py-2">
                                Background email queue
                            </div>
                            <div class="rounded-full border border-amber-200 bg-white/80 px-4 py-2">
                                Duplicate delivery protection
                            </div>
                            <div class="rounded-full border border-amber-200 bg-white/80 px-4 py-2">
                                Seeded website catalog
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-amber-200/80 bg-white/90 p-6 shadow-[0_18px_45px_rgba(146,64,14,0.14)]">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-700">
                            Subscribe Now
                        </p>

                        <div v-if="loading" class="mt-6 space-y-3">
                            <div class="h-12 animate-pulse rounded-2xl bg-amber-100"></div>
                            <div class="h-12 animate-pulse rounded-2xl bg-amber-100"></div>
                            <div class="h-28 animate-pulse rounded-3xl bg-stone-100"></div>
                        </div>

                        <form v-else class="mt-6 space-y-4" @submit.prevent="subscribe">
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-stone-700">Website</span>
                                <select
                                    v-model="selectedWebsiteId"
                                    class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white"
                                >
                                    <option
                                        v-for="website in websites"
                                        :key="website.id"
                                        :value="website.id"
                                    >
                                        {{ website.name }}
                                    </option>
                                </select>
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-stone-700">Subscriber Email</span>
                                <input
                                    v-model.trim="form.email"
                                    type="email"
                                    placeholder="name@example.com"
                                    class="w-full rounded-2xl border px-4 py-3 text-sm outline-none transition"
                                    :class="errors.email ? 'border-red-300 bg-red-50' : 'border-stone-300 bg-stone-50 focus:border-amber-500 focus:bg-white'"
                                >
                            </label>

                            <p v-if="errors.email" class="text-sm text-red-600">{{ errors.email }}</p>
                            <p v-if="errors.general" class="text-sm text-red-600">{{ errors.general }}</p>
                            <p v-if="successMessage" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                {{ successMessage }}
                            </p>

                            <button
                                type="submit"
                                class="w-full rounded-2xl bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:bg-stone-400"
                                :disabled="saving"
                            >
                                {{ saving ? 'Submitting...' : 'Subscribe to Website' }}
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="website in websites"
                    :key="website.id"
                    class="rounded-[1.6rem] border border-[color:var(--line)] bg-white/80 p-6 shadow-[0_18px_40px_rgba(120,53,15,0.08)] backdrop-blur transition hover:-translate-y-1"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-stone-900">{{ website.name }}</h2>
                            <a
                                :href="website.url"
                                target="_blank"
                                rel="noreferrer"
                                class="mt-2 inline-block text-sm text-amber-700 hover:text-amber-900"
                            >
                                {{ website.url }}
                            </a>
                        </div>
                        <span
                            class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]"
                            :class="selectedWebsiteId === website.id ? 'border-amber-500 bg-amber-100 text-amber-900' : 'border-stone-200 bg-stone-50 text-stone-500'"
                        >
                            {{ selectedWebsiteId === website.id ? 'Selected' : 'Available' }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Posts</p>
                            <p class="mt-2 text-3xl font-bold text-stone-900">{{ website.posts_count }}</p>
                        </div>
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Subscribers</p>
                            <p class="mt-2 text-3xl font-bold text-stone-900">{{ website.subscriptions_count }}</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="mt-6 w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-amber-500 hover:text-amber-800"
                        @click="selectedWebsiteId = website.id"
                    >
                        Subscribe to {{ website.name }}
                    </button>
                </article>
            </section>
        </div>
    </main>
</template>
