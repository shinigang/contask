<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    person: Object,
    businesses: Array,
    tags: Array
});

const fnameInput = ref(null);
const lnameInput = ref(null);
const emailInput = ref(null);
const phoneInput = ref(null);
const businessInput = ref(null);

const form = useForm({
    first_name: props.person.first_name,
    last_name: props.person.last_name,
    email: props.person.email,
    phone: props.person.phone,
    business_id: props.person.business_id ?? null,
    tags: props.person.tags ? props.person.tags.map(tag => tag.id) : []
});

const storePerson = () => {
    form.put(route('person.update', props.person.id), {
        errorBag: 'updatePerson',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.first_name) {
                form.reset('first_name');
                fnameInput.value.focus();
            }
            if (form.errors.last_name) {
                form.reset('last_name');
                lnameInput.value.focus();
            }
            if (form.errors.email) {
                form.reset('email');
                emailInput.value.focus();
            }
            if (form.errors.phone) {
                form.reset('phone');
                phoneInput.value.focus();
            }
            if (form.errors.business_id) {
                form.reset('business_id');
                businessInput.value.focus();
            }
        },
    });
};

const deletePersonForm = useForm({});
const personToBeDeleted = ref(null);

const confirmDeletePerson = (person) => {
    personToBeDeleted.value = person;
};
const deletePerson = () => {
    deletePersonForm.delete(route('person.destroy', personToBeDeleted.value.id), {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
            personToBeDeleted.value = null;
        }
    });
};
</script>

<template>
    <AppLayout title="Edit Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="storePerson">
                    <template #title>
                        Update a Profile
                    </template>

                    <template #description>
                        Update a contact person.
                    </template>

                    <template #form>
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-auto">
                                    <InputLabel for="firstName" value="First Name *" />
                                    <TextInput
                                        id="firstName"
                                        ref="fnameInput"
                                        v-model="form.first_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="new-fname"
                                    />
                                    <InputError :message="form.errors.first_name" class="mt-2" />
                                </div>
                                <div class="col-auto">
                                    <InputLabel for="lastName" value="Last Name *" />
                                    <TextInput
                                        id="lastName"
                                        ref="lnameInput"
                                        v-model="form.last_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="new-lname"
                                    />
                                    <InputError :message="form.errors.last_name" class="mt-2" />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div class="col-auto">
                                    <InputLabel for="email" value="Email Address" />
                                    <TextInput
                                        id="email"
                                        ref="emailInput"
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        autocomplete="new-email"
                                    />
                                    <InputError :message="form.errors.email" class="mt-2" />
                                </div>
                                <div class="col-auto">
                                    <InputLabel for="phone" value="Phone / Mobile" />
                                    <TextInput
                                        id="phone"
                                        ref="phoneInput"
                                        v-model="form.phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="new-phone"
                                    />
                                    <InputError :message="form.errors.phone" class="mt-2" />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div class="col-auto">
                                    <InputLabel for="business" value="Business" />
                                    <select
                                        ref="businessInput"
                                        v-model="form.business_id" id="business" name="business" placeholder=" "
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option value="">Select a Business</option>
                                        <option v-for="business in props.businesses" :key="business.id" :value="business.id">
                                            {{ business.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.business_id" class="mt-2" />
                                </div>
                                <div v-if="tags.length > 0" class="col-auto">
                                    <InputLabel for="tags" value="Tags" />
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div v-for="tag in props.tags" :key="tag.id">
                                            <label class="flex items-center">
                                                <Checkbox v-model:checked="form.tags" :value="tag.id.toString()" />
                                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ tag.name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <DangerButton
                            class="me-auto"
                            @click="confirmDeletePerson(person)"
                        >
                            Delete
                        </DangerButton>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('person.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>

        <!-- Delete Person Confirmation Modal -->
        <ConfirmationModal :show="personToBeDeleted != null" @close="personToBeDeleted = null">
            <template #title>
                Delete Person
            </template>

            <template #content>
                Are you sure you would like to delete this person: <b>{{ personToBeDeleted.first_name }} {{ personToBeDeleted.last_name }}</b>?
            </template>

            <template #footer>
                <SecondaryButton @click="personToBeDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deletePersonForm.processing }"
                    :disabled="deletePersonForm.processing"
                    @click="deletePerson"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
