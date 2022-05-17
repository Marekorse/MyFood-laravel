<template>
    <div
        class="flex items-start justify-start flex-col w-full overflow-x-auto md:flex-row mb-8">
        <div
            v-for="(todo, k) in todos"
            :key="k"
            class="w-full lg:w-1/3 p-2 lg:flex-shrink-0">
            <dev-todo-card
                @deleteCard="deleteCard"
                :todo-card="todo"
                :array-index="k">
            </dev-todo-card>
        </div>

        <div
            v-if="todos.length < 3"
            class="shadow-sm w-full md:w-1/3 p-2 md:flex-shrink-0">
            <div class="rounded-xl bg-white p-4 shadow-sm w-full">
                <div class="py-1">
                    <p
                        @click="showInput = true"
                        class="text-sm text-gray-600 font-semibold hover:text-gray-800">
                        {{ $t('toDo.addNewCard') }}
                    </p>
                    <transition name="slide-fade">
                        <div v-if="showInput">
                            <div
                                class="border-l-4 rounded border-blue-400 my-2">
                                <input
                                    class="text-xs w-full p-2 mt-2 rounded border-0 outline-none focus:ring-0 resize-none overflow-y-auto"
                                    v-model="cardName"
                                    type="text" />
                            </div>
                            <div class="flex items-center">
                                <button
                                    class="text-sm font-semibold text-gray-600 cursor-pointer"
                                    @click="addNewCard">
                                    {{ $t('toDo.add') }}
                                </button>
                                <li
                                    @click="showInput = false"
                                    class="list-none cursor-pointer">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mt-1 ml-3 text-gray-400"
                                        viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </li>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import devTodoCard from './devTodoCard.vue'

export default {
    components: {
        devTodoCard,
    },

    data: function () {
        return {
            todos: [],
            cardName: '',
            showInput: false,
        }
    },
    methods: {
        addNewCard() {
            axios
                .post(`${this.$app_url}todo`, {
                    card_name: this.cardName,
                })
                .then((response) => {
                    this.todos.push(response.data)
                    this.showInput = false
                    this.cardName = ''
                })
        },
        deleteCard(index) {
            console.log(index)
            this.todos.splice(index, 1)
        },
    },

    created() {
        axios.get(`${this.$app_url}todo`).then((response) => {
            this.todos = response.data
        })
    },
}
</script>
