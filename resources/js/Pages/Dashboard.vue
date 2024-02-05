<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="text-white">
            <!-- Main -->
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                Dashboard
            </div>
        </div>
    </AuthenticatedLayout>
</template>
