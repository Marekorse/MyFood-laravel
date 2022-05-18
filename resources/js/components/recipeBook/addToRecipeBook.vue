<template>
    <div>
        <div v-if="!disabled" @click="action()">
            <div class="">
                <div class="relative h-8 w-48">
                    <transition name="fade" mode="out-in">
                        <button
                            v-if="!this.inBook"
                            type="button"
                            class="absolute h-8 w-48 px-3 font-xs font-medium gradient-green-blue focus:outline-none rounded-xl text-white">
                            {{ $t('recipesBook.addToRecipeBookButton') }}
                        </button>
                    </transition>
                    <transition name="fade" mode="out-in">
                        <button
                            v-if="this.inBook"
                            type="button"
                            class="absolute h-8 w-48 px-3 font-sm font-medium gradient-green-blue focus:outline-none rounded-xl text-white">
                            {{ $t('recipesBook.removeFromRecipeBookButton') }}
                        </button>
                    </transition>
                </div>
            </div>
        </div>
        <div v-if="disabled">
            <button
                @click="showAlert = true"
                class="h-8 w-48 px-3 font-xs font-medium gradient-green-blue focus:outline-none rounded-xl text-white">
                {{ $t('recipesBook.addToRecipeBookButton') }}
            </button>
            <modal v-if="showAlert" @closeModal="showAlert = false">
                <auth-alert />
            </modal>
        </div>
        <modal v-if="showModal" @closeModal="clear()">
            <div
                class="w-full md:w-4/5 lg:w-3/6 xl:w-2/6 bg-white rounded-xl overflow-hidden m-1 max-h-52 overflow-y-auto">
                <div class="w-full text-center bg-indigo-600 text-white">
                    <span class="text-lg m-4 text-white font-semibold">
                        {{ $t('recipesBook.selectBook') }}
                    </span>
                </div>

                <ul class="w-full text-center p-3">
                    <li class="">
                        <a
                            class="text-base text-blue-400 font-semibold"
                            @click.prevent="addRecipeToBook(book.id)"
                            href="">
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
                        v-for="userBook in userBooks.data"
                        :key="userBook.id">
                        <a
                            @click.prevent="addRecipeToBook(userBook.id)"
                            href="">
                            {{ userBook.name }}
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
    </div>
</template>

<script>
import authAlert from '../basicComponents/authAlert.vue'
import modal from '../basicComponents/modal.vue'
export default {
    components: {
        modal,
        authAlert,
    },
    props: {
        book: Object,
        isInUserBook: [Boolean],
        recipeId: Number,
        disabled: Boolean,
    },

    data: function () {
        return {
            showAlert: false,
            inBook: this.isInUserBook,
            userBooks: null,
            showModal: false,

        }
    },

    created() {
        axios
            .get(`${this.$app_url}book/shared`)
            .then((res) => {
                this.userBooks = res.data
            })
            .catch((err) => {
                console.log(err)
            })
    },
    methods: {
        action() {
            if (this.userBooks.data.length) {
                this.showModal = true
            } else this.addRecipeToBook(this.book.id)
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
        addRecipeToBook(bookId) {
            if (this.inBook) {
                axios.delete(
                    `${this.$app_url}book/${this.book.id}/recipe/${this.recipeId}`
                )
                this.inBook = false
            } else {
                axios.post(`${this.$app_url}book/${this.book.id}/recipe`, {
                    recipe_id: this.recipeId,
                    book_id: bookId,
                })
                this.inBook = true
            }
        },
    },
}
</script>
