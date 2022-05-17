<template>
    <div
        class="w-32 h-32 flex items-start flex-col flex-shrink-0 bg-gray-200 shadow rounded-xl">
        <div class="relative">
            <avatar-component
                class="absolute top-10 left-2/4 -ml-6"
                :image="user.avatar"
                :size="48"
                thumb />
            <image-component
                class="w-full h-20 rounded-t-lg"
                thumb
                :image="user.cover" />
        </div>
        <span
            class="mt-1 w-full text-center text-sm font-semibold text-blue-400">
            <a :href="$app_url + 'pouzivatel/' + encodeURIComponent(user.name)">
                {{ stringLimiter(user.name, 28) }}
            </a>
        </span>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
export default {
    props: {
        user: {
            type: Object,
            default: null,
        },
    },
    components: {
        avatarComponent,
        imageComponent,
    },
}
</script>

<style lang="scss" scoped></style>
