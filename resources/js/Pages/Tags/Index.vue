<script setup>
import { computed, ref, watch } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PaginationDetailed from '@/Components/PaginationDetailed.vue';
import MagnifyingGlass from '@/Components/Icons/MagnifyingGlass.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    tags: Object,
    query: String
});

const deleteTagForm = useForm({});
const tagToBeDeleted = ref(null);

const search = ref(props.query);
let searchUrl = computed(() => {
    let url = new URL(route('tag.index'));
    if (search.value) {
        url.searchParams.append("query", search.value);
        url.searchParams.append("page", 1);
    }

    return url;
});

watch(() => searchUrl.value, (newSearchUrl) => {
    router.visit(newSearchUrl, {
        preserveScroll: true,
        preserveState: true,
    });
});

const addTag = () => {
    router.visit(route('tag.create'));
};

const confirmDeleteTag = (tag) => {
    tagToBeDeleted.value = tag;
};

const deleteTag = () => {
    deleteTagForm.delete(route('tag.destroy', tagToBeDeleted.value.id), {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
            tagToBeDeleted.value = null;
        }
    });
};
</script>

<template>
    <AppLayout title="Tags">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tags List
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center mb-5 px-2 sm:px-0">
                    <div class="grow">
                        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Tags
                        </h3>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-50">
                            A list of all the Tags.
                        </p>
                    </div>
                    <div class="text-right">
                        <PrimaryButton type="button" @click="addTag">Add Tag</PrimaryButton>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-2 rounded">
                    <div class="flex flex-col lg:flex-row gap-2 lg:gap-10 justify-center items-center mb-2">
                        <div class="grow">
                            <div class="max-w-[400px]">
                                <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <MagnifyingGlass class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                    </div>
                                    <input
                                        v-model="search"
                                        type="search" id="search"
                                        class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search tags data.."
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                        </div>
                    </div>

                    <div class="relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="tags.data.length" v-for="tag in tags.data" :key="tag.id"
                                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-100 even:dark:bg-slate-800
                                        border-b dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-indigo-900"
                                >
                                    <td class="px-6 py-4">
                                        {{ tag.name }}
                                    </td>
                                    <td class="flex items-center justify-center px-6 py-4 space-x-3">
                                        <Link as="button" :href="route('tag.edit', tag.id)"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</Link>
                                        <button type="button" class="cursor-pointer ms-6 text-sm text-red-500" @click="confirmDeleteTag(tag)">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="5">
                                        <p class="text-center text-sm text-gray-400 py-4">No Tag Data</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <PaginationDetailed :pager="tags" />
            </div>
        </div>

        <!-- Delete Tag Confirmation Modal -->
        <ConfirmationModal :show="tagToBeDeleted != null" @close="tagToBeDeleted = null">
            <template #title>
                Delete Tag
            </template>

            <template #content>
                Are you sure you would like to delete this tag: <b>{{ tagToBeDeleted.name }}</b>?
            </template>

            <template #footer>
                <SecondaryButton @click="tagToBeDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteTagForm.processing }"
                    :disabled="deleteTagForm.processing"
                    @click="deleteTag"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
