<script setup>
import { Link } from '@inertiajs/vue3';
import Person from '@/Components/Icons/Person.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TaskList from '@/Components/OwnerTasks/TaskList.vue';
import TaskForm from '@/Components/OwnerTasks/TaskForm.vue';

defineProps({
    person: Object,
    taskStatuses: Object,
    tasks: Array
});
</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Profile: {{ person.last_name }}, {{ person.first_name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 lg:grid-cols-2 lg:divide-x">

                            <div class="w-full flex flex-col gap-4 items-center justify-center dark:text-white">
                                <div class="flex items-center justify-center w-40 h-40 overflow-hidden border bg-gray-100 rounded-full dark:bg-gray-600">
                                    <Person class="w-20 h-20" />
                                </div>
                                <div class="text-center">
                                    <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ person.last_name }}, {{ person.first_name }}</h2>
                                    <span class="mt-3 text-xs text-gray-500 dark:text-gray:100">Added {{ person.created_time_ago }}</span>
                                </div>
                            </div>

                            <dl class="pt-5 lg:p-5 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                                <div class="flex flex-col pb-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Email address</dt>
                                    <dd class="text-lg font-semibold">
                                        <a v-if="person.email" :href="`mailto:${ person.email }`">{{ person.email }}</a>
                                        <span v-else class="text-xs text-gray-500 dark:text-gray-200">None</span>
                                    </dd>
                                </div>
                                <div class="flex flex-col py-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Phone / Mobile</dt>
                                    <dd class="text-lg font-semibold">
                                        <a v-if="person.phone" :href="`tel:${ person.phone }`">{{ person.phone }}</a>
                                        <span v-else class="text-xs text-gray-500 dark:text-gray-200">None</span>
                                    </dd>
                                </div>
                                <div v-if="person.business" class="flex flex-col py-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Employer</dt>
                                    <dd class="text-lg font-semibold">
                                        <Link :href="route('business.show', person.business.id)">{{ person.business.name }}</Link>
                                    </dd>
                                </div>
                            </dl>

                        </div>
                    </div>

                    <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8">
                        <h4 class="text-2xl font-bold dark:text-white text-center">Tasks</h4>

                        <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

                        <div class="grid grid-cols-1 lg:grid-cols-2 lg:divide-x">

                            <div class="lg:pe-5">
                                <TaskForm :owner="person" ownerType="person" :statuses="taskStatuses" />
                            </div>

                            <div class="pt-3 lg:ps-5">
                                <TaskList :tasks="tasks" />
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
