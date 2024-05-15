<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Head
}                          from '@inertiajs/vue3';
import PrimaryButton       from "@/Components/PrimaryButton.vue";
import SecondaryButton     from "@/Components/SecondaryButton.vue";

defineProps({
    cities: {
        type: Array
    }
});
</script>

<template>
    <Head title="Города" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Города</h2>
                <PrimaryButton @click="() => { $inertia.visit('/city/create') }">Добавление города</PrimaryButton>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center justify-between" v-for="city in cities" :key="city.id">
                    <div>
                        <div class="">
                            <span class="font-bold">{{city.name}}</span>
                        </div>
                    </div>
                    <div>
                        <SecondaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations`) }">Просмотр локаций</SecondaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
