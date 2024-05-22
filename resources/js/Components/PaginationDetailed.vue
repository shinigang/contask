<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    pager: {
        type: Object,
    }
});

const updatePageNumber = (link) => {
    router.visit(link.url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="py-3">
        <div class="overflow-hidden sm:rounded">
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between sm:px-6">
                <div class="flex-1 flex-cols sm:flex items-center text-center sm:justify-between">
                    <div>
                        <p class="text-xs text-gray-700 dark:text-gray-50">
                            Showing <span class="font-medium">{{ pager.from }}</span> to <span class="font-medium">{{ pager.to }}</span> of <span class="font-medium">{{ pager.total }}</span> results
                        </p>
                    </div>
                    <div v-if="pager.links.length > 3" class="mt-2 sm:mt-0">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button
                                v-for="(link, index) in pager.links"
                                :key="index"
                                @click.prevent="updatePageNumber(link)"
                                :disabled="link.active || !link.url"
                                class="relative inline-flex items-center px-4 py-2 border shadow text-sm font-medium"
                                :class="{
                                    'z-10 bg-indigo-50 dark:bg-indigo-700 border-indigo-500 dark:border-indigo-700 dark:bg-indigo-700 text-indigo-600 font-black dark:text-white':
                                        link.active,
                                    'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-500 dark:text-gray-300':
                                        !link.active,
                                    'hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-50': !link.active && link.url
                                }"
                            >
                                <span v-html="link.label"></span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>