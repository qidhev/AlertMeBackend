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

const props = defineProps({
    city: {
        type: Object
    },
    location: {
        type: Object
    },
    types: {
        type: Array
    }
})

const locationEdit = useForm({
    name: props.location.name,
    email: props.location.email,
    phone: props.location.phone,
    street: props.location.street,
    house: props.location.house,
    type_id: props.location.type_id,
    city_id: props.city.id
})

</script>

<template>
    <Head title="Изменение уведомления" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Изменение локации</h2>
                <PrimaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations`) }">Назад</PrimaryButton>
            </div>

        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between mb-5">
                    <div class="p-6 flex flex-col gap-6">
                        <div class="flex flex-col w-96">
                            <InputLabel>Наименование *</InputLabel>
                            <TextInput v-model="locationEdit.name" @input="locationEdit.clearErrors('name')"></TextInput>
                            <small v-if="locationEdit.errors.name">Имя обязательно к заполнению</small>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Email</InputLabel>
                            <TextInput v-model="locationEdit.email"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Телефон *</InputLabel>
                            <TextInput v-model="locationEdit.phone" @input="locationEdit.clearErrors('phone')"></TextInput>
                            <small v-if="locationEdit.errors.phone">Телефон обязателен к заполнению</small>
                        </div>
                        <div class="flex flex-col w-96">
                            <InputLabel>Тип локации*</InputLabel>
                            <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="locationEdit.type_id">
                                <option v-for="type in types" :value="type.id" :key="type.id">{{type.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-6">
                        <InputLabel>Адрес локации</InputLabel>
                        <small v-if="locationEdit.errors.street || locationEdit.errors.house">{{locationEdit.errors.street}}</small>
                        <div class="flex flex-col w-96">
                            <InputLabel>Город</InputLabel>
                            <TextInput :disabled="true" v-model="city.name"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Улица</InputLabel>
                            <TextInput @input="locationEdit.clearErrors('street')" v-model="locationEdit.street"></TextInput>
                        </div>

                        <div class="flex flex-col w-96">
                            <InputLabel>Дом</InputLabel>
                            <TextInput @input="locationEdit.clearErrors('street')" v-model="locationEdit.house"></TextInput>
                        </div>
                    </div>

                </div>
                <form @submit.prevent="locationEdit.put(`/cities/${city.id}/locations/${location.id}`)">
                    <PrimaryButton>Изменить локацию</PrimaryButton>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
