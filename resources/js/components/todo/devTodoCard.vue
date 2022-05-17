<template>
    <div class="w-full mb-4">
        <div class="rounded-xl bg-white p-4 shadow-lg w-full relative">
            <div class="flex justify-between py-1 text-black dark:text-white">
                <textarea
                    :disabled="!editing"
                    @blur="updateCard()"
                    @keypress.enter.prevent="updateCard()"
                    @input="textareaResize"
                    ref="textarea"
                    class="bg-transparent border-0 focus:ring-0 resize-none max-h-10 hidebar text-black font-semibold"
                    v-model="title"
                    maxlength="50"></textarea>
                <dropdown align="right" onClick>
                    <template v-slot:text>
                        <p
                            class="text-sm font-semibold px-2 py-0.5 rounded-md bg-blue-400 text-white">
                            upraviť
                        </p>
                    </template>
                    <template v-slot:dropDown>
                        <div
                            class="flex bg-gray-100 p-2 rounded-lg items-center flex-col justify-center w-full text-xs font-semibold text-gray-500 list-none uppercase">
                            <li
                                @click="startEditing"
                                class="p-1 cursor-pointer hover:text-gray-700">
                                {{ $t('global.edit') }}
                            </li>
                            <li
                                @click="areYouSure = true"
                                class="p-1 cursor-pointer hover:text-gray-700">
                                {{ $t('global.delete') }}
                            </li>
                        </div>
                    </template>
                </dropdown>
                <are-you-sure
                    v-if="areYouSure"
                    @submitEvent="deleteCard(arrayIndex)"
                    @closeEvent="areYouSure = false"
                    :message="$t('areYouSure.deleteToDo')"
                    :button="$('global.delete')"></are-you-sure>
            </div>
            <div
                class="h-0.5 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
            <draggable
                class="bg-white cursor-pointer"
                v-model="cardTasks"
                group="people"
                :animation="200"
                @change="reorder"
                @add="onAdd($event, false)">
                <dev-todo-task
                    @delete="deleteTask"
                    v-for="task in cardTasks"
                    :key="task.id"
                    :data-id="task.id"
                    :task="task"
                    class="text-sm text-black py-1"></dev-todo-task>
            </draggable>
            <p
                v-if="!showInput"
                @click="showInput = !showInput"
                class="mt-3 mb-1 text-gray-600 cursor-pointer hover:text-gray-800">
                {{ $t('toDo.addTask') }}
            </p>
            <transition name="slide">
                <div v-if="showInput">
                    <div class="border-l-4 rounded border-blue-400">
                        <textarea
                            row="2"
                            maxlength="250"
                            @input="textareaResize"
                            ref="textarea"
                            placeholder="Akú úlohu chceš pridať ?"
                            @keydown.enter="addTask()"
                            @keydown.esc="
                                ;(showInput = false),
                                    (text = ''),
                                    (deadline = '')
                            "
                            class="w-full font-semibold p-2 mt-2 rounded border-0 outline-none focus:ring-0 resize-none overflow-y-auto"
                            v-model="text"
                            rows="2"></textarea>
                    </div>
                </div>
            </transition>
            <div class="flex items-center">
                <button
                    @click="addTask()"
                    v-if="showInput && text.length > 0"
                    class="text-sm font-semibold mt-1 mb-1 text-gray-600 dark:text-gray-400 cursor-pointer">
                    {{ $t('toDo.add') }}
                </button>
                <svg
                    v-if="showInput"
                    @click=";(showInput = false), (text = ''), (deadline = '')"
                    xmlns="http://www.w3.org/2000/svg"
                    class="ml-2 h-4 w-4"
                    viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
import areYouSure from '../basicComponents/areYouSure.vue'
import devTodoTask from './devTodoTask.vue'
import draggable from 'vuedraggable'
import Dropdown from '../basicComponents/dropdown.vue'

export default {
    components: {
        devTodoTask,
        draggable,
        Dropdown,
        areYouSure,
    },
    props: { arrayIndex: Number, todoCard: Object },
    data: function () {
        return {
            areYouSure: false,
            title: this.todoCard.card_name,
            editing: false,
            cardTasks: this.todoCard.tasks,
            showInput: false,
            text: '',
            test: '',
        }
    },
    methods: {
        reorder(evt) {
            this.cardTasks.map((task, index) => {
                task.order = index
            })

            axios
                .put(`${this.$app_url}task/reorder`, {
                    todo_id: this.todoCard.id,
                    tasks: this.cardTasks,
                })
                .then((response) => {})
        },
        onAdd(evt) {
            let id = evt.item.getAttribute('data-id')
            axios
                .put(`${this.$app_url}task/${id}`, {
                    todo_id: this.todoCard.id,
                })
                .then((response) => {
                    this.reorder()
                })
        },

        addTask(id) {
            axios
                .post(`${this.$app_url}task`, {
                    task_name: this.text,
                    deadline: this.deadline,
                    todo_id: this.todoCard.id,
                    order: this.cardTasks.length + 1,
                })
                .then((response) => {
                    this.showInput = false
                    this.text = ''
                    this.deadline = ''
                    this.cardTasks.push(response.data)
                })
        },
        startEditing() {
            this.editing = true
            setTimeout(() => {
                this.$refs.textarea.focus()
            }, 0.2)
        },
        deleteCard(arrayIndex) {
            axios
                .delete(`${this.$app_url}todo/${this.todoCard.id}`)
                .then((response) => {})

            this.$emit('deleteCard', arrayIndex)
            this.showOverlay = false
            this.showPopup = false
            this.areYouSure = false
        },
        textareaResize() {
            this.$refs.textarea.style.height = 'auto'
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + 'px'
        },
        updateCard() {
            axios.patch(`${this.$app_url}todo/${this.todoCard.id}`, {
                card_name: this.title,
            })
            this.editing = false
        },
        deleteTask(task) {
            axios
                .delete(`${this.$app_url}task/${task.id}`)
                .then((response) => {})
            this.cardTasks.splice(this.cardTasks.indexOf(task), 1)
        },
    },
}
</script>

<style lang="scss">
.min-h-custom {
    min-height: 3.5em;
}
</style>
