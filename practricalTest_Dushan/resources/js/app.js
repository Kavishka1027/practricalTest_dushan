import './bootstrap';
import { createApp } from 'vue';
import SubscriptionApp from './components/SubscriptionApp.vue';

const el = document.getElementById('app');

if (el) {
    createApp(SubscriptionApp, {
        websitesEndpoint: el.dataset.websitesEndpoint,
    }).mount(el);
}
