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
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    category: Object
});

const nameInput = ref(null);
const form = useForm({
    name: props.category.name
});

const updateCategory = () => {
    form.put(route('category.update', props.category.id), {
        errorBag: 'updateCategory',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.name) {
                form.reset('name');
                nameInput.value.focus();
            }
        },
    });
};

const deleteCategoryForm = useForm({});
const categoryToBeDeleted = ref(null);

const confirmDeleteCategory = (category) => {
    categoryToBeDeleted.value = category;
};
const deleteCategory = () => {
    deleteCategoryForm.delete(route('category.destroy', categoryToBeDeleted.value.id), {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
            categoryToBeDeleted.value = null;
        }
    });
};
</script>

<template>
    <AppLayout title="Edit Category">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Category
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="updateCategory">
                    <template #title>
                        Edit Category Form
                    </template>

                    <template #description>
                        Update a category (such as industry) to be used for Business.<br>
                        e.g. Retail, Finance, Technology
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Category Name" />
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
                    </template>

                    <template #actions>
                        <DangerButton
                            class="me-auto"
                            @click="confirmDeleteCategory(category)"
                        >
                            Delete
                        </DangerButton>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('category.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>

        <!-- Delete Category Confirmation Modal -->
        <ConfirmationModal :show="categoryToBeDeleted != null" @close="categoryToBeDeleted = null">
            <template #title>
                Delete Category
            </template>

            <template #content>
                Are you sure you would like to delete this category: <b>{{ categoryToBeDeleted.name }}</b>?
            </template>

            <template #footer>
                <SecondaryButton @click="categoryToBeDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteCategoryForm.processing }"
                    :disabled="deleteCategoryForm.processing"
                    @click="deleteCategory"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
