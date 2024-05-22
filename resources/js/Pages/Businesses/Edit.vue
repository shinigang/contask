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
    business: Object,
    categories: Array,
    tags: Array
});

const nameInput = ref(null);
const emailInput = ref(null);

const form = useForm({
    name: props.business.name,
    contact_email: props.business.contact_email,
    categories: props.business.tags ? props.business.categories.map(category => category.id) : [],
    tags: props.business.tags ? props.business.tags.map(tag => tag.id) : []
});

const updateBusiness = () => {
    form.put(route('business.update', props.business.id), {
        errorBag: 'updateBusiness',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.name) {
                form.reset('name');
                nameInput.value.focus();
            }
            if (form.errors.contact_email) {
                form.reset('contact_email');
                emailInput.value.focus();
            }
        },
    });
};

const deleteBusinessForm = useForm({});
const businessToBeDeleted = ref(null);

const confirmDeleteBusiness = (business) => {
    businessToBeDeleted.value = business;
};
const deleteBusiness = () => {
    deleteBusinessForm.delete(route('business.destroy', businessToBeDeleted.value.id), {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
            businessToBeDeleted.value = null;
        }
    });
};
</script>

<template>
    <AppLayout title="Edit Business">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Business
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="updateBusiness">
                    <template #title>
                        Update a Business
                    </template>

                    <template #description>
                        Update a contact business.
                    </template>

                    <template #form>
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-auto">
                                    <InputLabel for="name" value="Business Name *" />
                                    <TextInput
                                        id="name"
                                        ref="nameInput"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="edit-name"
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>
                                <div class="col-auto">
                                    <InputLabel for="email" value="Business Email Address" />
                                    <TextInput
                                        id="email"
                                        ref="emailInput"
                                        v-model="form.contact_email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        autocomplete="edit-email"
                                    />
                                    <InputError :message="form.errors.contact_email" class="mt-2" />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div v-if="categories.length > 0" class="col-auto">
                                    <InputLabel for="categories" value="Categories" />
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div v-for="category in props.categories" :key="category.id">
                                            <label class="flex items-center">
                                                <Checkbox v-model:checked="form.categories" :value="category.id.toString()" />
                                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ category.name }}</span>
                                            </label>
                                        </div>
                                    </div>
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
                            @click="confirmDeleteBusiness(business)"
                        >
                            Delete
                        </DangerButton>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('business.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>

        <!-- Delete Business Confirmation Modal -->
        <ConfirmationModal :show="businessToBeDeleted != null" @close="businessToBeDeleted = null">
            <template #title>
                Delete Business
            </template>

            <template #content>
                Are you sure you would like to delete this business: <b>{{ businessToBeDeleted.name }}</b>?
            </template>

            <template #footer>
                <SecondaryButton @click="businessToBeDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteBusinessForm.processing }"
                    :disabled="deleteBusinessForm.processing"
                    @click="deleteBusiness"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
