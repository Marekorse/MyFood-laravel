<template>
    <div
        :class="{ 'transform-gpu -rotate-12': rotate }"
        class="px-4 m-1 text-xs rounded-lg bg-indigo-600 text-white">
        <span>
            {{ $t('recipesBook.improved') }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        rotate: {
            type: Boolean,
            default: false,
        },
    },
}
</script>

<style lang="scss" scoped></style>
