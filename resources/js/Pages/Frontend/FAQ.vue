<template>
    <div class="container mx-auto my-10 mb-16">
        <h4
            class="my-5 text-2xl bg-gradient-to-r from-red-400 to-primary-500 bg-clip-text text-transparent"
        >
            Frequently Asked Questions
        </h4>
        <div class="">
            <el-collapse accordion>
                <el-collapse-item
                    class="text-lg"
                    :title="item.title"
                    v-for="item in faqs"
                    :key="item.id"
                >
                    <div class="text-gray-400">
                        {{ item.desc }}
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>
    </div>
</template>

<script>
export default {
    props: ["faqs"],
};
</script>

<style></style>
