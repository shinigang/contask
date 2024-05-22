<script setup>
import { computed, ref, watch } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PaginationDetailed from '@/Components/PaginationDetailed.vue';
import Company from '@/Components/Icons/Company.vue';
import Person from '@/Components/Icons/Person.vue';
import MagnifyingGlass from '@/Components/Icons/MagnifyingGlass.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


const props = defineProps({
    tasks: Object,
    statuses: Object,
    status: {
        type: String,
        default: 'all'
    },
    query: String
});

console.log(props.tasks);

const deleteTaskForm = useForm({});
const taskToBeDeleted = ref(null);

const search = ref(props.query);
let searchUrl = computed(() => {
    let url = new URL(route('task.index'));
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

const addTask = () => {
    router.visit(route('task.create'));
};

const updateStatus = (task, newStatus) => {
    router.put(route('task.update-status', task.id), {
        status: newStatus
    }, {
        preserveState: true,
        onSuccess: () => {
            task.status = newStatus;
        }
    });
};

const confirmDeleteTask = (task) => {
    taskToBeDeleted.value = task;
};

const deleteTask = () => {
    deleteTaskForm.delete(route('task.destroy', taskToBeDeleted.value.id), {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
            taskToBeDeleted.value = null;
        }
    });
};
</script>

<template>
    <AppLayout title="Tasks">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tasks List
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center mb-5 px-2 sm:px-0">
                    <div class="grow">
                        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Tasks
                        </h3>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-50">
                            A list of all the tasks.
                        </p>
                    </div>
                    <div class="text-right">
                        <PrimaryButton type="button" @click="addTask">Add Task</PrimaryButton>
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
                                        placeholder="Search tasks data.."
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <Link
                                :href="route('task.index', {status: 'all' })"
                                class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium rounded"
                                :class="status == 'all' ? 'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300'"
                            >
                                All
                            </Link>
                            <Link
                                v-for="(statusText, statusKey) in statuses" :key="`status_${statusKey}`"
                                :href="route('task.index', {status: statusKey })"
                                class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium rounded"
                                :class="status == statusKey ? 'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300'"
                            >
                                {{ statusText }}
                            </Link>
                        </div>
                    </div>

                    <div class="relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Task Name</th>
                                    <th scope="col" class="px-6 py-3">Owner</th>
                                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="tasks.data.length" v-for="task in tasks.data" :key="task.id"
                                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-100 even:dark:bg-slate-800
                                        border-b dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-indigo-900"
                                >
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ task.name }}
                                        <div class="text-sm text-gray-600">
                                            {{ task.created_time_ago }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link
                                            v-if="task.taskable.first_name"
                                            :href="route('person.show', task.taskable.id)"
                                            class="flex items-center gap-4"
                                        >
                                            <div class="flex items-center justify-center w-10 h-10 overflow-hidden border bg-gray-100 rounded-full dark:bg-gray-600">
                                                <Person class="w-6 h-6" />
                                            </div>
                                            <div class="font-medium dark:text-white">
                                                <div>{{ task.taskable.first_name }} {{ task.taskable.last_name }}</div>
                                                <div class="text-sm text-blue-600 hover:underline">
                                                    <a :href="`mailto:${task.taskable.email}`" target="_blank">{{ task.taskable.email }}</a>
                                                </div>
                                            </div>
                                        </Link>
                                        <Link v-else :href="route('business.show', task.taskable.id)" class="flex items-center gap-4">
                                            <div class="flex items-center justify-center w-10 h-10 overflow-hidden border bg-gray-100 rounded-full dark:bg-gray-600">
                                                <Company class="w-6 h-6" />
                                            </div>
                                            <div class="font-medium dark:text-white">
                                                <div>{{ task.taskable.name }}</div>
                                                <div class="text-sm text-blue-600 hover:underline">
                                                    <a :href="`mailto:${task.taskable.contact_email}`" target="_blank">{{ task.taskable.contact_email }}</a>
                                                </div>
                                            </div>
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <Dropdown align="center" width="40">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center px-3 py-2 text-sm leading-5 font-medium border dark:border-0 rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800
                                                            hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700
                                                            transition ease-in-out duration-150"
                                                    >
                                                        {{ statuses[task.status] }}
                                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div v-for="(statusText, statusKey) in statuses" :key="`${task.id}_${statusKey}`">
                                                        <form @submit.prevent="updateStatus(task, statusKey)">
                                                            <DropdownLink as="button" :disabled="task.status == statusKey" :class="task.status == statusKey ? 'bg-indigo-100 dark:bg-indigo-600' : ''">
                                                                {{ statusText }}
                                                            </DropdownLink>
                                                        </form>
                                                    </div>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </td>
                                    <td class="flex items-center justify-center px-6 py-4 space-x-3">
                                        <Link as="button" :href="route('task.edit', task.id)"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</Link>
                                        <button type="button" class="cursor-pointer ms-6 text-sm text-red-500" @click="confirmDeleteTask(task)">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="5">
                                        <p class="text-center text-sm text-gray-400 py-4">No Tasks Data</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <PaginationDetailed :pager="tasks" />
            </div>
        </div>

        <!-- Delete Task Confirmation Modal -->
        <ConfirmationModal :show="taskToBeDeleted != null" @close="taskToBeDeleted = null">
            <template #title>
                Delete Task
            </template>

            <template #content>
                Are you sure you would like to delete this task: <b>{{ taskToBeDeleted.name }}</b>?
            </template>

            <template #footer>
                <SecondaryButton @click="taskToBeDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteTaskForm.processing }"
                    :disabled="deleteTaskForm.processing"
                    @click="deleteTask"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
