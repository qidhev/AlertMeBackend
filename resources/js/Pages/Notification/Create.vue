<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Head,
    useForm
}                          from '@inertiajs/vue3';
import PrimaryButton       from "@/Components/PrimaryButton.vue";
import TextInput           from "@/Components/TextInput.vue";
import InputLabel          from "@/Components/InputLabel.vue";
import {ref}               from "vue";

defineProps({
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

const createType = ref(false);

const notify = useForm({
    title: '',
    subtitle: '',
    main_text: '',
    date_start_at: '',
    date_end_at: '',
    parent_id: null,
    type_id: null,
    type_name: '',
    city_id: null,
    type_location_id: null,
});

const createNotify = () => {
    notify.post('/notification', notify)
}

</script>

<template>
    <Head title="Notification" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Создание уведомления</h2>
                <PrimaryButton @click="() => { $inertia.visit('/notification') }">Назад</PrimaryButton>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between mb-5">
                    <div class="p-6 flex flex-col gap-6">

                        <div class="flex flex-col w-96">
                            <InputLabel>Заголовок *</InputLabel>
                            <TextInput v-model="notify.title"></TextInput>
                            <small v-if="notify.errors.title">Заголовок обязателен к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Подзаголовок *</InputLabel>
                            <TextInput v-model="notify.subtitle"></TextInput>
                            <small v-if="notify.errors.subtitle">Подзаголовок обязателен к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Основной текст *</InputLabel>
                            <textarea
                                v-model="notify.main_text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                            <small v-if="notify.errors.main_text">Основной текст обязателен к заполнению</small>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-6">
                        <div class="flex flex-col w-96">
                            <InputLabel>Начало события *</InputLabel>
                            <TextInput type="datetime-local" v-model="notify.date_start_at"></TextInput>
                            <small v-if="notify.errors.date_start_at">Начало обязателено к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Конец события *</InputLabel>
                            <TextInput type="datetime-local" v-model="notify.date_end_at"></TextInput>
                            <small v-if="notify.errors.date_end_at">Конец обязателен к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Тип уведомления *</InputLabel>
                            <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="notify.type_id">
                                <option v-for="type in types" :value="type.id" :key="type.id">{{type.name}}</option>
                            </select>
                            <small v-if="notify.errors.type_id">Тип обязателен к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Город</InputLabel>
                            <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="notify.city_id">
                                <option :value="null"></option>
                                <option v-for="city in cities" :value="city.id" :key="city.id">{{city.name}}</option>
                            </select>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Тип локации</InputLabel>
                            <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="notify.type_location_id">
                                <option :value="null"></option>
                                <option v-for="locationType in typesLocation" :value="locationType.id" :key="locationType.id">{{locationType.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="createNotify">
                    <PrimaryButton>Создать уведомление</PrimaryButton>
                </form>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
