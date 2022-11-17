<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Calendar from 'primevue/calendar';
import SelectButton from '@/Components/SelectButton.vue';
import {ref} from "vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    user: Object,
    unread_messages_count: Number,
});

const value3 = ref({
    icon: 'pi pi-align-left',
    value: 'Left',
});
const justifyOptions = [
    {icon: 'pi pi-align-left', value: 'Left'},
    {icon: 'pi pi-align-right', value: 'Right'},
    {icon: 'pi pi-align-center', value: 'Center'},
    {icon: 'pi pi-align-justify', value: 'Justify'}
];

const calendar = null;

const dateChanged = (d) => {
    alert(d.valueOf());
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard {{ unread_messages_count }}
                </h2>

                <h5 id="custom">Custom Content</h5>
                <SelectButton v-model="value3" :options="justifyOptions" optionLabel="value" dataKey="value"
                              aria-labelledby="custom" class="rounded-full">
                    <template #option="slotProps">
                        <i :class="slotProps.option.icon"></i>
                    </template>
                </SelectButton>

                <Calendar v-model="calendar" :inline="true" @date-select="dateChanged">
                    <template #date="slotProps">
                        <span v-if="slotProps.date.day > 10 && slotProps.date.day < 15"
                              class="w-full h-full text-center bg-gray-400 font-bold leading-9">{{
                                slotProps.date.day
                            }}</span>
                        <template v-else>{{ slotProps.date.day }}</template>
                    </template>
                </Calendar>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.p-datepicker {
    @apply !w-fit;
}

.p-datepicker-calendar > tbody > tr > td {
    @apply p-6;
}

.p-selectbutton > .p-button {
    @apply bg-gray-500 text-white;
}
</style>
