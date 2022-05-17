<template>
    <div>
        <div v-if="!disabled" @click="addToRecipeBook">
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
        }
    },

    methods: {
        addToRecipeBook() {
            if (this.inBook) {
                axios.delete(
                    `${this.$app_url}book/${this.book.id}/recipe/${this.recipeId}`
                )
                this.inBook = false
            } else {
                axios.post(`${this.$app_url}book/${this.book.id}/recipe`, {
                    id: this.recipeId,
                })
                this.inBook = true
            }
        },
    },
}
</script>
