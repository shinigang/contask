<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    owner: Object,
    ownerType: String,
    statuses: Object,
});

const nameInput = ref(null);
const descInput = ref(null);
const statusInput = ref(null);

const form = useForm({
    owner_type: props.ownerType,
    owner_id: props.owner.id,
    name: '',
    description: '',
    status: 'open',
    new: true
});

const storeTask = () => {
    form.post(route('task.store'), {
        errorBag: 'storeTask',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.name) {
                form.reset('name');
                nameInput.value.focus();
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
</script>

<template>
    <form @submit.prevent="storeTask">
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
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
                <div class="col-span-6">
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
                <div class="col-span-6">
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
            </div>
        </div>
        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </PrimaryButton>
        </div>
    </form>
</template>
