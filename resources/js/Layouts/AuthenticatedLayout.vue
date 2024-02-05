<template>
    <section>
        <!--Sidebar-->
        <div class="flex gap-6">
            <div class="w-[20%]">
                <AppSidebar></AppSidebar>
            </div>
            <div class="w-[80%] pl-0 p-2 pr-3">
                <!-- <AppHeader /> -->
                <slot />
            </div>
        </div>
    </section>
</template>

<script>
import AppHeader from "./AppHeader.vue";
import AppSidebar from "./AppSidebar.vue";

export default { components: { AppSidebar, AppHeader } };
</script>

<style></style>
