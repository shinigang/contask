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
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    categories: Array,
    tags: Array
});

const nameInput = ref(null);
const emailInput = ref(null);

const form = useForm({
    name: '',
    contact_email: '',
    categories: [],
    tags: [],
    new: false
});

const storeBusiness = () => {
    form.post(route('business.store'), {
        errorBag: 'storeBusiness',
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
</script>

<template>
    <AppLayout title="New Business">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Business
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="storeBusiness">
                    <template #title>
                        Create a New Business
                    </template>

                    <template #description>
                        Make a new contact business.
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
                                        autocomplete="new-name"
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>
                                <div class="col-auto">
                                    <InputLabel for="email" value="Contact Email Address" />
                                    <TextInput
                                        id="email"
                                        ref="emailInput"
                                        v-model="form.contact_email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        autocomplete="new-email"
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
                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <Link :href="route('business.index')" class="me-3 text-xs dark:text-gray-100 tracking-widest uppercase">Cancel</Link>
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
