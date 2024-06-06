<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Head,
    router
}                          from '@inertiajs/vue3';
import PrimaryButton       from "@/Components/PrimaryButton.vue";
import SecondaryButton     from "@/Components/SecondaryButton.vue";
import DangerButton        from "@/Components/DangerButton.vue";

defineProps({
    city: {
        type: Object
    },
    locations: {
        type: Array
    },
    types: {
        type: Array
    }
});
</script>

<template>
    <Head title="Локации" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Локации</h2>
                <PrimaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations/create`) }">Добавление локации</PrimaryButton>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center justify-between" v-for="location in locations" :key="location.id">
                    <div>
                        <div class="mb-2 text-sm text-sky-500">{{types.find(type => type.id === location.type_id).name}}</div>
                        <div class="mb-3">
                            <span class="font-bold">{{location.name}}</span>
                            {{city.name}}
                        </div>
                        <div class="text-gray-700 mb-2">{{location.address}}</div>
                        <div class="text-sm text-gray-400">
                            Контактные данные: {{location.phone}}
                            <span v-if="location.email">/ {{location.email}}</span>
                        </div>
                    </div>
                    <div class="flex flex-row gap-4">
                        <SecondaryButton @click="() => { $inertia.visit(`/cities/${city.id}/locations/${location.id}/edit`) }">Изменить</SecondaryButton>
                        <form @submit.prevent="router.delete(`/cities/${city.id}/locations/${location.id}`)">
                            <DangerButton>Удалить</DangerButton>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
