<script setup>

import TextInput           from "@/Components/TextInput.vue";
import InputLabel          from "@/Components/InputLabel.vue";
import PrimaryButton       from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    Head,
    router,
    useForm
}                          from "@inertiajs/vue3";
import {ref}               from "vue";
import SecondaryButton     from "@/Components/SecondaryButton.vue";

const props = defineProps({
    city: {
        type: Object
    },
    types: {
        type: Array
    }
})

const createType = ref(false);

const location = useForm({
    name: '',
    email: '',
    phone: '',
    street: '',
    house: '',
    type_id: props.types[0].id,
    type_name: '',
    city_id: props.city.id ?? null
})

const createLocation = () => {
    router.post(`/cities/${props.city.id}/locations`, location)
}

</script>

<template>
    <Head title="Cоздание уведомления" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Создание локации</h2>
                <PrimaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations`) }">Назад</PrimaryButton>
            </div>

        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between mb-5">
                    <div class="p-6 flex flex-col gap-6">
                        <div class="flex flex-col w-96">
                            <InputLabel>Наименование *</InputLabel>
                            <TextInput v-model="location.name" @input="location.clearErrors('name')"></TextInput>
                            <small v-if="location.errors.name">Имя обязательно к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Email</InputLabel>
                            <TextInput v-model="location.email"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Телефон *</InputLabel>
                            <TextInput v-model="location.phone" @input="location.clearErrors('phone')"></TextInput>
                            <small v-if="location.errors.phone">Телефон обязателен к заполнению</small>
                        </div>
                        <div class="flex flex-col w-96" v-if="!createType">
                            <InputLabel>Тип локации*</InputLabel>
                            <div class="flex flex-row gap-4">
                                <select class="border-gray-300 flex-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="location.type_id">
                                    <option v-for="type in types" :value="type.id" :key="type.id">{{type.name}}</option>
                                </select>
                                <SecondaryButton @click="() => createType = true">Новый</SecondaryButton>
                            </div>
                        </div>
                        <div class="flex flex-col w-96" v-else>
                            <InputLabel>Новый тип локации*</InputLabel>
                            <div class="flex flex-row gap-4">
                                <TextInput class-in="flex-1" v-model="location.type_name"></TextInput>
                                <SecondaryButton @click="() => createType = false">Назад</SecondaryButton>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-6">
                        <InputLabel>Адрес локации</InputLabel>
                        <small v-if="location.errors.street || location.errors.house">{{location.errors.street}}</small>
                        <div class="flex flex-col w-96">
                            <InputLabel>Город</InputLabel>
                            <TextInput :disabled="true" v-model="city.name"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Улица</InputLabel>
                            <TextInput @input="location.clearErrors('street')" v-model="location.street"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Дом</InputLabel>
                            <TextInput @input="location.clearErrors('street')" v-model="location.house"></TextInput>
                        </div>
                    </div>

                </div>
                <form @submit.prevent="location.post(`/cities/${city.id}/locations`)">
                    <PrimaryButton>Создать локацию</PrimaryButton>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
