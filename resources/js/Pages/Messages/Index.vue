<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    threads: Object,
});


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Discussions
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="thread in threads" key="thread.id"><Link :href="route('messages.show', thread.id)">{{ thread.subject }}</Link></li>
                    </ul>
                </div>
            </div>
        </div>
        <Link :href="route('create-thread')">Créer une discussion</Link>
    </AppLayout>
</template>
