<template>
    <div class="w-full">
        <dropdown onClick>
            <template v-slot:text>
                <div
                    class="bg-blue-500 shadow-lg rounded-md flex items-center justify-between m-2 py-1 px-8 border-b-4 border-blue-600 text-white font-medium transform transition-all duration-300 hover:scale-105">
                    <slot
                        class="text blue-400 fotn font-semibold text-base"
                        name="title">
                        title
                    </slot>
                </div>
            </template>
            <template v-slot:dropDown>
                <slot
                    class="text blue-400 fotn font-semibold text-base"
                    name="options">
                    options
                </slot>
            </template>
        </dropdown>
        <div class="">
            <slot class="text blue-400 font-semibold text-base" name="content">
                content
            </slot>
        </div>
    </div>
</template>

<script>
import dropdown from '../basicComponents/dropdown.vue'
export default {
    components: {
        dropdown,
    },
}
</script>

<style lang="scss" scoped>
.h-custom {
    height: 30rem;
}
</style>
