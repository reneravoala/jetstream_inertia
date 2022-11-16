<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import {useForm} from "@inertiajs/inertia-vue3";
import { formatDistance } from 'date-fns';
import { fr } from 'date-fns/locale';

import axios from "axios";

const props = defineProps({
    thread: Object,
    messages: Object,
    other: Object,
    user: Object,
});

Echo.channel(`for_user_${props.user.id}`).listen(".new_message", (data) => {
    // console.log(data);
    const message = {
        body: data.message,
        created_at: data.created_at,
        name: data.sender_name
    };
    props.messages.push(message);
})

const form = useForm({
    _method: 'POST',
    body: '',
});

const createMessage = () => {
    form.post(route('messages.create', props.thread), {
        errorBag: 'createMessage',
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    })
}

const setRead = () => {
    axios.put(route('messages.set-read'), {
        thread_id: props.thread.id
    });
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Messages {{ other.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="message in messages" key="thread.id">
                            <span class="text-sm font-bold mr-2">{{ message.name }}</span>
                            {{ message.body }}
                            <span class="text-xs">{{ formatDistance(new Date(message.created_at), new Date(), {locale: fr, addSuffix: true}) }}</span></li>
                    </ul>
                    <form @submit.prevent="createMessage">
                        <Textarea v-model="form.body" rows="5" cols="30" @focus="setRead"/>
                        <Button type="submit">Envoyer</Button>
                    </form>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
