<template>
    <div class="mx-12">
        <div class="w-full flex items-center justify-center mr-2 mb-5">
            <div
                @dragenter.prevent="dragEnter"
                @dragleave.prevent="dragLeave"
                @dragover.prevent="dragOver"
                @drop.prevent="drop"
                id="dropZone"
                class="w-full h-28 rounded-xl border-4 border-dashed border-blue-400"
                :class="{
                    'transition-all transform duration-500 bg-blue-400 text-white':
                        entered,
                    'transition-all transform duration-500 bg-transparent text-blue-400':
                        !entered,
                }">
                <div
                    class="flex w-full h-full items-center flex-col justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-10 w-10"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span v-if="!name" class="text-xs font-bold"
                        >{{ $t('recipesBook.addToRecipeBookButton') }}
                    </span>
                    <span v-if="name" class="text-xs font-bold"
                        >{{ name }}
                    </span>
                    <span v-if="action" class="text-xs font-bold"
                        >{{ action }}
                    </span>
                </div>
            </div>
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
import modal from '../basicComponents/modal.vue'
export default {
    components: { modal },
    props: {
        book: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            recipeId: null,
            userBooks: null,
            showModal: false,
            entered: false,
            droped: false,
            name: null,
            action: null,
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
        paginate() {
            axios.get(`${this.userBooks.next_page_url}`).then((res) => {
                this.userBooks.current_page = res.data.current_page
                this.userBooks.next_page_url = res.data.next_page_url
                res.data.data.forEach((item) => {
                    this.userBooks.data.push(item)
                })
            })
        },
        dragEnter(e) {
            this.clear()
        },

        dragOver(e) {
            this.entered = true
            this.name = e.dataTransfer.getData('card_name')
        },
        dragLeave(e) {
            this.entered = false
            this.name = false
        },
        drop(e) {
            this.recipeId = e.dataTransfer.getData('card_id')
            if (this.userBooks.data.length) {
                this.showModal = true
            } else this.addRecipeToBook(this.book.id)
        },
        addRecipeToBook(bookId) {
            axios
                .post(`${this.$app_url}book/${bookId}/recipe`, {
                    recipe_id: this.recipeId,
                    book_id: bookId,
                })
                .then((res) => {
                    this.droped = true
                    this.action = this.$t('recipesBook.wasAddedToRecipeBook')
                    if (bookId == this.book.id) {
                        this.$root.$emit('addedToBook', this.recipeId)
                    }
                    this.showModal = false
                    setTimeout(() => {
                        this.clear()
                    }, 4000)
                })
                .catch((err) => {
                    this.droped = true
                    this.action = this.$t(
                        'recipesBook.alreadyAddedToRecipeBook'
                    )
                    this.showModal = false
                    setTimeout(() => {
                        this.clear()
                    }, 4000)
                })
        },
        clear() {
            this.entered = false
            this.droped = false
            this.action = null
            this.name = null
            this.recipeId = null
            this.showModal=false
        },
    },
}
</script>

<style lang="scss" scoped></style>
