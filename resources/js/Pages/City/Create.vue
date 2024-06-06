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

const city = useForm({
    name: ''
})

</script>

<template>
    <Head title="Добавление города" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Добавление города</h2>
                <PrimaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations`) }">Назад</PrimaryButton>
            </div>

        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between mb-5">
                    <div class="p-6 flex flex-col gap-6">
                        <div class="flex flex-col w-96">
                            <InputLabel>Название города *</InputLabel>
                            <TextInput v-model="city.name" @input="city.clearErrors('name')"></TextInput>
                            <small v-if="city.errors.name">{{city.errors.name}}</small>
                        </div>
                    </div>

                </div>
                <form @submit.prevent="city.post(`/cities`, city)">
                    <PrimaryButton>Добавить город</PrimaryButton>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
