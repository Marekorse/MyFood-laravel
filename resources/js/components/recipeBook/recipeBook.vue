<template>
    <div
        class="flex flex-shrink-0 flex-wrap pt-3 w-full flex-col md:flex-row p-4">
        <recipes-container
            class="w-full md:w-7/12 xl:w-8/12"
            :cardUrl="cardUrl"
            :base-url="baseUrl"></recipes-container>
        <recipes-book-panel
            :shared="shared"
            :privacy-options="privacyOptions"
            :book="book"
            :user="user"
            :user-most-popular-categories="userPopularCategories"
            :most-liked="mostLiked"
            class="w-full md:w-5/12 xl:w-4/12" />
    </div>
</template>

<script>
import recipesContainer from '../recipe/recipesContainer.vue'
import recipesBookPanel from './recipesBookPanel.vue'
export default {
    components: {
        recipesContainer,
        recipesBookPanel,
    },
    props: {
        baseUrl: {
            type: String,
            default: null,
        },
        cardUrl: {
            type: String,
            default: null,
        },
        privacyOptions: {
            type: Array,
            default: null,
        },
        book: {
            type: Object,
            default: null,
        },
        user: {
            type: Object,
            default: null,
        },
        userBooks: {
            type: Array,
            default: null,
        },
        userPopularCategories: {
            type: [Object, Array],
            default: null,
        },
        mostLiked: {
            type: [Object, Array],
            default: null,
        },
        shared: {
            type: Boolean,
            default: false,
        },

    },
}
</script>

<style lang="scss" scoped></style>
