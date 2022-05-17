<template>
    <div class="bg-white mx-3 rounded-xl">
        <h2
            class="custom-color-logoGray font-corinthia w-full text-center text-4xl font-semibold py-4">
            Rebríček popularity
        </h2>
        <leaderboard-panel class="m-2 my-5">
            <template v-slot:icon>
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 p-0.5 text-sky bg-white rounded-full border-4 border-sky"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-gray-500 ml-2 font font-semibold">{{
                        $t('leaderboard.followersCount')
                    }}</span>
                </div>
            </template>
            <template v-slot:labels>
                <leaderboard-label
                    class="gradient-sky text-white"
                    v-for="(user, index) in mostPopularUser"
                    :key="user.id"
                    :position="index + 1">
                    <template v-slot:name>
                        <a
                            :href="
                                encodeURI($app_url + 'pouzivatel/' + user.name)
                            "
                            >{{ user.name }}</a
                        >
                    </template>
                </leaderboard-label>
            </template>
        </leaderboard-panel>
        <leaderboard-panel class="m-2 my-5">
            <template v-slot:icon>
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 p-0.5 text-pink bg-white rounded-full border-4 border-pink"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="text-gray-500 ml-2 font font-semibold">{{
                        $t('leaderboard.favouriteRecipes')
                    }}</span>
                </div>
            </template>
            <template v-slot:labels>
                <leaderboard-label
                    class="gradient-pink text-white"
                    v-for="(recipe, index) in mostLiked"
                    :key="recipe.id"
                    :position="index + 1">
                    <template v-slot:name>
                        <a
                            :href="
                                encodeURI($app_url + 'recept/' + recipe.slug)
                            "
                            >{{ recipe.title }}</a
                        >
                    </template>
                </leaderboard-label>
            </template>
        </leaderboard-panel>

        <leaderboard-panel class="m-2 my-5">
            <template v-slot:icon>
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 p-0.5 text-purple bg-white rounded-full border-4 border-purple"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <span class="text-gray-500 ml-2 font font-semibold">{{
                        $t('leaderboard.mostCommented')
                    }}</span>
                </div>
            </template>
            <template v-slot:labels>
                <leaderboard-label
                    class="gradient-purple text-white"
                    v-for="(recipe, index) in mostCommented"
                    :key="recipe.id"
                    :position="index + 1">
                    <template v-slot:name>
                        <a
                            :href="
                                encodeURI($app_url + 'recept/' + recipe.slug)
                            "
                            >{{ recipe.title }}</a
                        >
                    </template>
                </leaderboard-label>
            </template>
        </leaderboard-panel>

        <leaderboard-panel class="m-2 my-5">
            <template v-slot:icon>
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 p-0.5 text-green-400 bg-white rounded-full border-4 border-green-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="text-gray-500 ml-2 font font-semibold">{{
                        $t('leaderboard.mostPopularCategories')
                    }}</span>
                </div>
            </template>
            <template v-slot:labels>
                <div
                    v-for="(category, key, index) in mostPopularCategories"
                    :key="key"
                    @click="setCategory(key)"
                    class="flex flex-wrap gap-1 list-none cursor-pointer">
                    <leaderboard-label
                        :position="index + 1"
                        class="bg-gradient-to-tr from-green-300 via-green-400 to-green-500 text-white relative">
                        <template v-slot:name>
                            <span>{{ key }}</span>

                            <svg
                                v-if="selectedCategory === key"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 absolute -top-1 -right-1 rounded-full bg-red-500 p-0.5 transform transition-all duration-200 hover:scale-105"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                    </leaderboard-label>
                </div>
            </template>
        </leaderboard-panel>

        <add-to-favourite-drag-and-drop :book="user.book" v-if="user" />
    </div>
</template>

<script>
import addToFavouriteDragAndDrop from '../recipeBook/addToRecipeBookDragAndDrop.vue'
import leaderboardLabel from './leaderboardLabel.vue'
import leaderboardPanel from './leaderboardPanel.vue'

export default {
    components: {
        leaderboardLabel,
        leaderboardPanel,
        addToFavouriteDragAndDrop,
    },
    data() {
        return {
            selectedCategory: null,
        }
    },
    methods: {
        setCategory(data) {
            if (this.selectedCategory == data) {
                this.$root.$emit('setCategory', '')
                this.selectedCategory = null
                return
            }
            this.$root.$emit('setCategory', data)
            this.$root.$emit('clearCategoryBar')
            this.selectedCategory = data
        },
    },
    mounted() {
        this.$root.$on('clearLeaderboard', () => {
            this.selectedCategory = null
        })
    },
    props: {
        user: {
            type: [Object, Number],
            default: {},
        },
        mostLiked: {
            type: [Object, Array],
            default: [],
        },
        mostPopularUser: {
            type: [Object, Array],
            default: [],
        },
        mostCommented: {
            type: [Object, Array],
            default: [],
        },
        mostPopularCategories: {
            type: [Object, Array],
            default: {},
        },
    },
}
</script>
