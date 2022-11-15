<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from 'primevue/button';
import {useForm} from "@inertiajs/inertia-vue3";
import { formatDistance } from 'date-fns';
import { fr } from 'date-fns/locale';
import InputText from 'primevue/inputtext'

const props = defineProps({
    users: Object
});

const form = useForm({
    _method: 'POST',
    subject: '',
    user_id: null,
});

const createThread = () => {
    form.post(route('store-thread', props.thread), {
        errorBag: 'createThread',
        preserveScroll: true,
        onSuccess: () => form.reset('user_id'),
    })
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Créer une discussion
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="createThread">
                        <InputText v-model="form.subject"></InputText>
                        <select v-model="form.user_id">
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <Button type="submit">Envoyer</Button>
                    </form>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
