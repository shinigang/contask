<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    person: Object,
    businesses: Array,
    tags: Array
});
</script>

<template>
    <AppLayout title="Edit Person">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Person
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        {{ person }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
