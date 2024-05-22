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

const nameInput = ref(null);
const form = useForm({
    name: '',
    new: false
});

const storeTag = () => {
    form.post(route('tag.store'), {
        errorBag: 'storeTag',
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
</script>

<template>
    <AppLayout title="New Tag">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Tag
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="storeTag">
                    <template #title>
                        Create a New Tag
                    </template>

                    <template #description>
                        Make a tag to be used for Business and Person.<br>
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
                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('tag.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
                        <SecondaryButton type="submit" @click="form.new = true" class="me-3">
                            Save and add another
                        </SecondaryButton>
                        <PrimaryButton @click="form.new = false" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Create
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
</template>
