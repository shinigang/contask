<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <Link :href="'/'">
        <ApplicationLogo class="w-full" />
    </Link>
</template>
