<template>
    <div
        class="inline-flex custom-min-h items-center bg-white rounded-md px-6 transform transition duration-500 hover:bg-gray-100">
        <span v-if="position === 1">&#129351;</span>
        <span v-else-if="position === 2">&#129352;</span>
        <span v-else-if="position === 3">&#129353;</span>
        <span class="text-xs font-bold">
            <slot name="name">
                {{ $t('leaderboard.weDontHaveData') }}
            </slot>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        position: {
            type: Number,
            default: 0,
        },
    },
}
</script>

<style lang="scss">
.custom-min-h {
    min-height: 1.5rem;
}
</style>
