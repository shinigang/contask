<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    task: Object,
    statuses: Array,
    businesses: Array,
    people: Array,
    ownerTypes: Array
});
</script>

<template>
    <AppLayout title="Edit Task">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Task
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        {{ task }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
