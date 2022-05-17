<template>
    <div class="bg-white rounded-xl">
        <div
            class="relative flex flex-col w-full flex-wrap justify-center items-center px-4 pt-3">
            <div
                class="flex items-center justify-center absolute w-6 h-6 -top-2 left-2/4 -ml-3 rounded-full bg-indigo-600">
                <i class="fas fa-book-open text-base text-white"></i>
            </div>
            <textarea
                ref="textarea"
                @input="textareaResize()"
                @keydown.esc="reset()"
                :disabled="!editing"
                v-model="bookName"
                maxlength="80"
                rows="1"
                class="text-xl w-full text-center bg-transparent text-gray-500 font-semibold border-0 outline-none focus:ring-0 resize-none"
                type="text" />
            <span
                @click="editBookName"
                class="absolute top-1 right-1 text-xs font-semibold text-white bg-indigo-600 rounded-full px-6 py-0.5 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500"
                v-if="owner && !editing">
                {{ $t('global.edit') }}
            </span>
            <span
                @click="saveChanges()"
                class="absolute top-1 right-1 text-xs font-semibold text-white bg-indigo-600 rounded-full px-6 py-0.5 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500"
                v-if="owner && editing">
                {{ $t('global.save') }}
            </span>
        </div>
        <div class="flex w-full flex-wrap justify-center items-center gap-1">
            <span
                v-if="userBooks && userBooks.data.length"
                @click="showBookModal = true"
                class="text-sm font-semibold text-white bg-indigo-600 rounded-full px-6 py-0.5 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500">
                {{ $t('recipesBook.otherBooks') }}
            </span>
            <modal v-if="showBookModal" @closeModal="showBookModal = false">
                <div
                    class="w-full md:w-4/5 lg:w-3/6 xl:w-2/6 bg-white rounded-xl overflow-hidden m-1 max-h-52 overflow-y-auto">
                    <div class="w-full text-center bg-indigo-600 text-white">
                        <span class="text-lg m-4 text-white font-semibold">
                            {{ $t('recipesBook.otherBooks') }}
                        </span>
                    </div>

                    <ul class="w-full text-center p-3">
                        <li class="">
                            <a
                                class="text-base text-blue-400 font-semibold"
                                :href="$app_url + 'receptar'">
                                {{ $t('recipesBook.myRecipeBook') }}
                            </a>
                        </li>
                        <li class="">
                            <span
                                v-if="!userBooks.data.length"
                                class="text-base text-gray-400 font-semibold">
                                {{ $t('recipesBook.empty') }}
                            </span>
                        </li>
                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-indigo-600 to-transparent"></div>

                        <li
                            class="text-base m-1 text-gray-500 font-semibold transform transition-all duration-500 hover:scale-105 hover:text-blue-400"
                            v-for="book in userBooks.data"
                            :key="book.id">
                            <a :href="$app_url + 'receptar/' + book.user_id">
                                {{ book.name }}
                            </a>
                        </li>
                        <li
                            v-if="userBooks.next_page_url"
                            class="w-full flex justify-center p-3 font-semibold text-blue-500">
                            <a @click.prevent="paginate()" href="">{{
                                $t('global.showMore')
                            }}</a>
                        </li>
                    </ul>
                </div>
            </modal>
            <span
                v-if="owner"
                @click="showPrivacyModal = true"
                class="text-sm font-semibold text-white bg-indigo-600 rounded-full px-6 py-0.5 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500">
                {{ $t('recipesBook.privacy') }}
            </span>
            <modal
                v-if="showPrivacyModal"
                @closeModal="showPrivacyModal = false">
                <div
                    class="w-full md:w-4/5 lg:w-3/6 xl:w-2/6 bg-white rounded-xl overflow-hidden m-1 max-h-52 overflow-y-auto">
                    <div class="w-full text-center bg-indigo-600 text-white">
                        <span class="text-lg m-4 text-white font-semibold">
                            {{ $t('recipesBook.privacy') }}
                        </span>
                    </div>

                    <ul class="w-full text-center p-3">
                        <li
                            @click="setPrivacy(privacyOption.id)"
                            class="text-base m-1 cursor-pointer text-gray-500 font-semibold transform transition-all duration-500 hover:scale-105 hover:text-blue-400"
                            v-for="privacyOption in privacyOptions"
                            :key="privacyOption.id">
                            {{ $t(`recipesBook.${privacyOption.privacy}`) }}
                        </li>
                    </ul>
                </div>
            </modal>
        </div>
        <div
            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-green-500 to-transparent"></div>

        <recipe-book-users
            :owner="owner"
            :book-id="book.id"
            :book-user-id="book.user_id"
            :auth-user="user" />
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
                                encodeURI(
                                    $app_url +
                                        'receptar/' +
                                        book.slug +
                                        '/recept/' +
                                        recipe.slug
                                )
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
                        $t('recipesBook.popularCategories')
                    }}</span>
                </div>
            </template>
            <template v-slot:labels>
                <div
                    v-for="(category, key, index) in userMostPopularCategories"
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
        <delete-from-recipes-book v-if="shared || owner"  :bookId="book.id" />
    </div>
</template>

<script>
import leaderboardPanel from '../recipe/leaderboardPanel.vue'
import dropdown from '../basicComponents/dropdown.vue'
import deleteFromRecipesBook from './deleteFromRecipeBookDragAndDrop.vue'
import recipeBookUsers from './recipeBookUsers.vue'
import axios from 'axios'
import leaderboardLabel from '../recipe/leaderboardLabel.vue'
import Modal from '../basicComponents/modal.vue'

export default {
    props: {
        userMostPopularCategories: {
            type: [Array, Object],
            default: [],
        },
        mostLiked: {
            type: [Array, Object],
            default: [],
        },
        book: {
            type: Object,
            default: [],
        },
        user: {
            type: Object,
            default: [],
        },
        privacyOptions: {
            type: Array,
        },
        shared: {
            type: Boolean,
        },
    },
    components: {
        deleteFromRecipesBook,
        recipeBookUsers,
        dropdown,
        leaderboardPanel,
        leaderboardLabel,
        Modal,
    },
    data() {
        return {
            showPrivacyModal: false,
            showBookModal: false,
            bookName: this.book.name,
            owner: false,
            editing: false,
            bookName: this.book.name,
            selectedCategory: null,
            userBooks: null,
        }
    },
    methods: {
        getUserBooks() {
            axios
                .get(`${this.$app_url}book/shared`)
                .then((res) => {
                    this.userBooks = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        paginate() {
            axios.get(`${this.userBooks.next_page_url}`).then((res) => {
                this.userBooks.current_page = res.data.current_page
                this.userBooks.next_page_url = res.data.next_page_url
                res.data.data.forEach((item) => {
                    this.userBooks.data.push(item)
                })
            })
        },
        setPrivacy(privacy) {
            axios
                .put(`${this.$app_url}book/${this.book.id}/privacy`, {
                    privacy_id: privacy,
                })
                .then((res) => {
                    this.showPrivacyModal = false
                    this.book.privacy = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        textareaResize() {
            this.$refs.textarea.style.height = 'auto'
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + 'px'
        },
        setCategory(data) {
            if (this.selectedCategory == data) {
                console.log('selected rovna sa data ');
                this.$root.$emit('setCategory', '')
                this.selectedCategory = null
                return
            }
            console.log('emit' + data);
            this.$root.$emit('setCategory', data)
            this.$root.$emit('clearCategoryBar')
            this.selectedCategory = data
        },
        editBookName() {
            this.editing = true
            setTimeout(() => {
                this.$refs.textarea.focus()
            }, 500)
        },
        saveChanges() {
            axios
                .put(`${this.$app_url}book/${this.book.id}/name`, {
                    name: this.bookName,
                })
                .then((res) => {
                    this.editing = false
                    this.book.name = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        reset() {
            this.editing = false
            this.bookName = this.book.name
        },
    },
    created() {
        this.getUserBooks()
    },
    mounted() {
        this.textareaResize()

        if (this.user.id === this.book.user_id) {
            this.owner = true
        }
    },
}
</script>

<style lang="scss" scoped>
[v-cloak] {
    display: none;
}
</style>
