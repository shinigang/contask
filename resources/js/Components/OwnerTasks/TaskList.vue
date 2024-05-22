<script setup>
defineProps({
    tasks: Array
});
</script>

<template>
    <ul v-if="tasks.length > 0" role="list" class="divide-y divide-gray-200">
        <li v-for="task in tasks" class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.name }}</p>
                    <p class="mt-1 text-xs leading-5 text-gray-500 line-clamp-3">{{ task.description }}</p>
                </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <span class="capitalize inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ task.status }}</span>
                <p class="mt-1 text-xs leading-5 text-gray-500">Assigned <time :datetime="task.created_at">{{ task.created_time_ago }}</time></p>
            </div>
        </li>
    </ul>
    <div v-else class="py-4 text-center text-xs text-gray-500 dark:text-gray-100">No Tasks Data</div>
</template>
