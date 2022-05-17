<template>
    <div>
        <div
            class="bg-blue-500 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 text-white font-medium">
            <div
                class="flex justify-center items-center w-14 h-14 bg-white rounded-full">
                <slot>svg</slot>
            </div>
            <div class="text-right relative">
                <p class="text-2xl">{{ stats }}</p>
                <p>{{ message }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        stats: Number,
        message: String,
    },
    data: function () {
        return {
            data: {
                result: this.stats,
                day: '',
                month: '',
                year: '',
            },
        }
    },
}
</script>
