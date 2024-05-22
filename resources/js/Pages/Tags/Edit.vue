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
import DangerButton from '@/Components/DangerButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    tag: Object
});

const nameInput = ref(null);
const form = useForm({
    name: props.tag.name
});

const updateTag = () => {
    form.put(route('tag.update', props.tag.id), {
        errorBag: 'updateTag',
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

const deleteTagForm = useForm({});
const tagToBeDeleted = ref(null);

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
    <AppLayout title="Edit Tag">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Tag
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="updateTag">
                    <template #title>
                        Edit Tag Form
                    </template>

                    <template #description>
                        Update a tag to be used for Business and Person.<br>
                        e.g. Popular, New, VIP
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Tag Name" />
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
                            @click="confirmDeleteTag(tag)"
                        >
                            Delete
                        </DangerButton>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('tag.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </template>
                </FormSection>
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
