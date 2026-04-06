<template>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-stone-100 px-4 py-10">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-2xl border border-orange-200/60 bg-white/80 p-8 shadow-xl backdrop-blur">
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-800">Subscribe to Website</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Choose a website and enter your email to subscribe for updates.
                    </p>
                </div>

                <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Website count: <span class="font-semibold">{{ websites.length }}</span>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="website" class="mb-2 block text-sm font-medium text-gray-700">
                            Website
                        </label>
                        <select
                            id="website"
                            v-model="form.website_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                        >
                            <option disabled value="">Select website</option>
                            <option v-for="website in websites" :key="website.id" :value="website.id">
                                {{ website.name }} ({{ website.url }})
                            </option>
                        </select>
                        <p v-if="errors.website_id" class="mt-2 text-sm text-red-600">
                            {{ errors.website_id[0] }}
                        </p>
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter your email"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                        />
                        <p v-if="errors.email" class="mt-2 text-sm text-red-600">
                            {{ errors.email[0] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="button"
                            @click="fetchWebsites"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >
                            Load Websites
                        </button>

                        <button
                            type="submit"
                            :disabled="loading"
                            class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-200 transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{ loading ? 'Submitting...' : 'Subscribe' }}
                        </button>
                    </div>
                </form>

                <div
                    v-if="message"
                    class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
                >
                    {{ message }}
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Debug Website List
                    </h2>
                    <div v-if="websites.length" class="space-y-2">
                        <div
                            v-for="w in websites"
                            :key="'debug-' + w.id"
                            class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700"
                        >
                            <span class="font-medium">{{ w.name }}</span>
                            <span class="mx-2 text-gray-400">•</span>
                            <span>{{ w.url }}</span>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400">No websites loaded yet.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { reactive, ref, onMounted } from 'vue';

const websites = ref([]);
const loading = ref(false);
const message = ref('');
const errors = ref({});

const form = reactive({
    website_id: '',
    email: ''
});

const fetchWebsites = async () => {
    console.log('fetchWebsites called');

    try {
        const res = await axios.get('/api/websites');
        console.log('API Response:', res.data);
        websites.value = res.data.data;
    } catch (err) {
        console.error('API Error:', err);
    }
};

const submitForm = async () => {
    loading.value = true;
    message.value = '';
    errors.value = {};

    console.log('Submitting:', form);

    try {
        const res = await axios.post('/api/subscriptions', {
            website_id: form.website_id,
            email: form.email
        });

        console.log('Response:', res.data);
        message.value = res.data.message || 'Subscribed successfully';
        form.website_id = '';
        form.email = '';
    } catch (err) {
        console.error('Submit Error:', err);

        if (err.response?.status === 422) {
            errors.value = err.response.data.errors || {};
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    console.log('Component Mounted');
    fetchWebsites();
});
</script>
