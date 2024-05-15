<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Head,
    router
}                          from '@inertiajs/vue3';
import PrimaryButton       from "@/Components/PrimaryButton.vue";
import SecondaryButton     from "@/Components/SecondaryButton.vue";

defineProps({
    notifications: {
        type: Array,
    },
    types: {
        type: Array,
    },
    cities: {
        type: Array
    },
    typesLocation: {
        type: Array
    }
});

const send = () => {

}
</script>

<template>
    <Head title="Notification" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Уведомления</h2>
                <PrimaryButton @click="() => { $inertia.visit('/notification/create') }">Создание уведомления</PrimaryButton>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center justify-between" v-for="notify in notifications" :key="notify.id">
                    <div>
                        <div class="mb-2 text-sm text-sky-500">{{types.find(type => type.id === notify.type_id).name}}</div>
                        <div class="mb-3">
                            <span class="font-bold">{{notify.title}}</span>
                            {{notify.city_id ? `: ${cities.find(city => city.id === notify.city_id).name}` : ''}}
                        </div>
                        <div class="text-gray-700 mb-2">{{notify.main_text}}</div>
                        <div class="text-sm text-gray-400">Период с {{notify.date_start_at}} по {{notify.date_end_at}}</div>
                    </div>
                    <div>
                        <form v-if="!notify.send" @submit.prevent="router.post('/send-notification', {id: notify.id})">
                            <button type="submit">Отправить</button>
                        </form>
                        <SecondaryButton v-if="notify.send">Создать дополнение</SecondaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
