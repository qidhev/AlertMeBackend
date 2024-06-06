<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    classIn: {
        type: String,
        default: ''
    }
})

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        :class="['border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm', disabled ? 'disabled:opacity-50' : '', classIn]"
        :disabled="disabled"
        v-model="model"
        ref="input"
    />
</template>
