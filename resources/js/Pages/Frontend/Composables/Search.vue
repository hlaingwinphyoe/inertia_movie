<template>
    <el-dialog
        :modelValue="show"
        @update:modelValue="show = $event"
        @close="closeDialog"
        @open="openDialog"
        draggable
        :title="dialogTitle"
        :close-on-click-modal="true"
        :overflow="true"
    >
        <div class="py-4">
            <el-input
                type="search"
                v-model="query"
                placeholder="Search Movies"
                size="large"
                @keydown.up="focusPrev"
                @keydown.down="focusNext"
                @keyup.enter="hitEnter"
            />

            <div v-if="query" class="py-2 max-h-[450px] overflow-y-auto">
                <p>
                    Found
                    <span class="text-primary-500 font-semibold">
                        {{ results.estimatedTotalHits }}
                    </span>
                    Movie(s)
                </p>
                <div
                    v-for="(item, index) in results.hits"
                    :key="index"
                    class="my-3 p-2 rounded-lg"
                    :class="{ 'bg-secondary-700': index === selectedHitIndex }"
                >
                    <Link :href="route('movies.detail', item.id)">
                        <div class="flex items-start gap-4">
                            <img
                                :src="item.thumbnail"
                                class="rounded-md h-24 w-24 object-cover"
                                alt=""
                            />
                            <article>
                                <h4 class="text-lg">
                                    {{ item.title }}
                                </h4>
                                <p class="text-gray-400 mb-1">
                                    {{ item.excerpt }}
                                </p>
                                <el-tag
                                    v-for="genre in item.genres"
                                    :key="genre.id"
                                    type="info"
                                    class="mr-2"
                                >
                                    {{ genre.name }}
                                </el-tag>
                            </article>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
import { onMounted, reactive, toRefs, watchEffect } from "vue";
import Meilisearch from "meilisearch";
import { Link, router } from "@inertiajs/vue3";
export default {
    props: ["show"],
    emits: ["closed"],
    components: { Link },
    setup(props, context) {
        const state = reactive({
            dialogTitle: "Search",
            doubleClick: false,
            query: "",
            client: "",
            results: [],
            selectedHitIndex: 0,
        });

        const closeDialog = () => {
            state.query = "";
            state.client = "";
            state.results = [];
            state.selectedHitIndex = 0;
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = "Search";
        };

        const search = async (query) => {
            if (query) {
                state.results = await state.client
                    .index("movies")
                    .search(query);
            }
        };

        watchEffect(() => {
            search(state.query);
        });

        onMounted(() => {
            state.client = new Meilisearch({
                host: "http://localhost:7700",
                apiKey: "3TpjkwUKop5P2SK6Z-RAKM7F_KzZRa59MMx_V6TfQBY",
            });
        });

        const focusPrev = () => {
            if (state.selectedHitIndex === 0) {
                state.selectedHitIndex = state.results.hits.length - 1;
            } else {
                state.selectedHitIndex--;
            }
        };

        const focusNext = () => {
            if (state.selectedHitIndex < state.results.hits.length - 1) {
                state.selectedHitIndex++;
            } else {
                state.selectedHitIndex = 0;
            }
        };

        const hitEnter = () => {
            const hit = state.results.hits[state.selectedHitIndex];
            router.visit(route("movies.detail", hit.id));
            closeDialog();
        };

        return {
            ...toRefs(state),
            openDialog,
            closeDialog,
            focusPrev,
            focusNext,
            hitEnter,
        };
    },
};
</script>

<style></style>
