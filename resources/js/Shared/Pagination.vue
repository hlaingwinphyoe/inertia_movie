<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap justify-center mt-3">
            <template v-for="(link, index) in links" :key="index">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-3 py-2 text-sm leading-4 text-gray-500 border border-secondary-600 rounded cursor-not-allowed"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border border-secondary-500 rounded hover:bg-secondary-600 focus:border-indigo-500 focus:text-indigo-500"
                    :class="{
                        'bg-primary-500 border-primary-500 hover:bg-primary-500 text-white':
                            link.active,
                    }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    props: {
        links: Array,
    },
    components: { Link },
};
</script>
