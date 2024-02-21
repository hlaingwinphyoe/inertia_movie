<template>
    <section>
        <SiteHeader />
        <main>
            <slot />
        </main>
    </section>
</template>

<script>
import SiteHeader from "./SiteHeader.vue";

export default { components: { SiteHeader } };
</script>

<style></style>
