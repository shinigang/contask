<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-4 justify-center">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 shadow text-gray-400 dark:text-gray-700 rounded"
                    v-html="link.label" />

                <Link v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm text-gray-500 dark:text-gray-50 leading-4 shadow rounded hover:bg-white dark:hover:bg-gray-600 focus:border-primary focus:text-primary"
                    :class="{ 'bg-white dark:bg-indigo-400': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
