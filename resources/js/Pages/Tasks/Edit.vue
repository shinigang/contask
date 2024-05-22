<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    task: Object,
    statuses: Object,
    businesses: Array,
    people: Array,
    ownerTypes: Array
});

console.log(props.ownerTypes);

const nameInput = ref(null);
const ownerTypeInput = ref(null);
const ownerInput = ref(null);
const descInput = ref(null);
const statusInput = ref(null);

const form = useForm({
    owner_type: props.task.taskable.first_name ? 'person' : 'business',
    owner_id: props.task.taskable.id,
    name: props.task.name,
    description: props.task.description ?? '',
    status: props.task.status
});

const updateTask = () => {
    form.put(route('task.update', props.task.id), {
        errorBag: 'updateTask',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.name) {
                form.reset('name');
                nameInput.value.focus();
            }
            if (form.errors.owner_type) {
                form.reset('owner_type');
                ownerTypeInput.value.focus();
            }
            if (form.errors.owner_id) {
                form.reset('owner_id');
                ownerInput.value.focus();
            }
            if (form.errors.description) {
                form.reset('description');
                descInput.value.focus();
            }
            if (form.errors.status) {
                form.reset('status');
                statusInput.value.focus();
            }
        },
    });
};

const deleteTaskForm = useForm({});
const taskToBeDeleted = ref(null);

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
    <AppLayout title="Edit Task">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Task
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="updateTask">
                    <template #title>
                        Edit Task Form
                    </template>

                    <template #description>
                        Update a task for a contact person or business.
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Task Name *" />
                            <TextInput
                                id="name"
                                ref="nameInput"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="new-name"
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Description" />
                            <textarea
                                id="description"
                                ref="descInput"
                                v-model="form.description"
                                class="block w-full p-2.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Task description..."
                                rows="4"
                            />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="ownerType" value="Task Owner Type *" />
                            <select
                                ref="ownerTypeInput"
                                v-model="form.owner_type" id="ownerType" name="owner_type" placeholder=" "
                                class="capitalize bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option v-for="ownerType in props.ownerTypes" :key="ownerType" :value="ownerType" class="capitalize">
                                    {{ ownerType }}
                                </option>
                            </select>
                            <InputError :message="form.errors.owner_type" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="owner" value="Task Owner *" />
                            <select
                                ref="ownerInput"
                                v-model="form.owner_id" id="owner" name="owner_id" placeholder=" "
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option></option>
                                <option
                                    v-if="form.owner_type == 'person'"
                                    v-for="person in props.people"
                                    :key="person.id"
                                    :value="person.id"
                                >
                                    {{ person.last_name }}, {{ person.first_name }}
                                </option>
                                <option
                                    v-else
                                    v-for="business in props.businesses"
                                    :key="business.id"
                                    :value="business.id"
                                >
                                    {{ business.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.owner_id" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="status" value="Status *" />
                            <select
                                ref="statusInput"
                                v-model="form.status" id="status" name="status" placeholder=" "
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option v-for="(status, statusKey) in props.statuses" :key="statusKey" :value="statusKey">
                                    {{ status }}
                                </option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <DangerButton
                            class="me-auto"
                            @click="confirmDeleteTask(task)"
                        >
                            Delete
                        </DangerButton>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('task.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </template>
                </FormSection>
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
