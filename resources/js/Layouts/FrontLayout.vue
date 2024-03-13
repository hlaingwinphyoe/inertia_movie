<template>
    <section>
        <SiteHeader />
        <main>
            <slot />
        </main>
        <SiteFooter />
    </section>
</template>

<script>
import SiteHeader from "./SiteHeader.vue";
import SiteFooter from "./SiteFooter.vue";

export default { components: { SiteHeader, SiteFooter } };
</script>

<style></style>
