<template>
    <div>
        <h4 class="text-lg font-semibold mb-2">Related Movies</h4>
        <div class="grid grid-cols-1 gap-4">
            <Card
                v-for="movie in relatedMovies"
                :key="movie.id"
                :movie="movie"
            />
        </div>
    </div>
</template>

<script>
import Card from "../Composables/Card.vue";

export default {
    props: ["relatedMovies"],
    components: { Card },
};
</script>

<style></style>
