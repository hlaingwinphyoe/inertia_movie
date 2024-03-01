<template>
    <Link href="#" class="relative cursor-pointer">
        <el-card
            class="w-fit relative"
            style="border-radius: 8px !important"
            :body-style="{ padding: '0px' }"
        >
            <img :src="cast.profile" class="h-44 w-36 object-cover" />
        </el-card>
        <div class="mt-2 text-sm">
            <span class="font-bold text-[14.5px]">{{ cast.name }}</span>
        </div>
    </Link>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    props: ["cast"],
    components: { Link },
};
</script>

<style></style>
