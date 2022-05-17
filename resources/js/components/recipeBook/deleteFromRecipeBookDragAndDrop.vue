<template>
    <div class="mx-12">
        <div class="w-full flex items-center justify-center mr-2 mb-5">
            <div
                @dragenter.prevent="dragEnter"
                @dragleave.prevent="dragLeave"
                @dragover.prevent="dragOver"
                @drop.prevent="drop"
                id="dropZone"
                class="w-full h-28 rounded-xl border-4 border-dashed border-yellow-500"
                :class="{
                    'transition-all transform duration-500 bg-yellow-500 text-white':
                        entered,
                    'transition-all transform duration-500 bg-transparent text-yellow-500':
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
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span v-if="!name" class="text-xs font-bold"
                        >{{ $t('recipesBook.deleteFromRecipesBook') }}
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
    </div>
</template>

<script>
export default {
    props: {
        bookId: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            entered: false,
            droped: false,
            name: null,
            action: null,
        }
    },
    methods: {
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
            const id = e.dataTransfer.getData('card_id')
            axios
                .delete(`${this.$app_url}book/${this.bookId}/recipe/${id}`)
                .then((res) => {
                    this.droped = true
                    this.action = this.$t(
                        'recipesBook.wasDeletedFromRecipesBook'
                    )
                    this.$root.$emit('removedFromBook', id)
                    setTimeout(() => {
                        this.clear()
                    }, 4000)
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        clear() {
            this.entered = false
            this.droped = false
            this.action = null
            this.name = null
        },
    },
}
</script>

<style lang="scss" scoped></style>
