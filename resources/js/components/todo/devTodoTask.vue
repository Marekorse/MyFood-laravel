<template>
    <div>
        <div ref="task" class="relative w-full">
            <div
                @mouseenter="entered = true"
                @mouseleave="entered = false"
                class="w-full flex items-center justify-between shadow-md hover:bg-gray-100 p-2 rounded mt-1">
                <textarea
                    @input="textareaResize()"
                    :readonly="!editing"
                    ref="textarea"
                    @keypress.esc="oldValue()"
                    v-model="text"
                    rows="1"
                    maxlength="250"
                    :class="{ 'cursor-pointer': !editing }"
                    class="text-gray-500 w-full max-w-lg hidebar bg-transparent font-semibold text-sm sm:text-base border-none outline-none ring-0 p-2 block whitespace-pre-line break-all focus:outline-none focus:ring-0 resize-none"></textarea>
                <dropDown align="right" onClick hideOnClick>
                    <template v-slot:text>
                        <div class="w-4 h-4 text-center">
                            <i
                                v-if="entered && !editing"
                                class="fas fa-ellipsis-v text-gray-500" />
                        </div>
                    </template>
                    <template v-slot:dropDown>
                        <ul class="bg-gray-100 p-2 rounded-lg">
                            <li
                                @click="startEditing()"
                                class="p-1 cursor-pointer hover:text-gray-700">
                                {{ $t('global.edit') }}
                            </li>
                            <li
                                @click="deleting = true"
                                class="p-1 cursor-pointer hover:text-gray-700">
                                {{ $t('global.delete') }}
                            </li>
                        </ul>
                    </template>
                </dropDown>
            </div>
            <div v-if="editing" class="flex items-center">
                <span
                    @click="updateTask()"
                    class="px-2 text-xs bg-white text-blue-400 rounded-md">
                    {{ $t('global.save') }}
                </span>
                <span
                    @click="oldValue()"
                    v-if="editing"
                    class="px-2 text-xs bg-white text-blue-400 rounded-md">
                    {{ $t('global.cancel') }}
                </span>
            </div>

            <are-you-sure
                v-if="deleting"
                @submitEvent="deleteTask()"
                @closeEvent="areYouSure = false"
                :message="$t('areYouSure.deleteTask')"
                :button="$t('global.delete')"></are-you-sure>
        </div>
    </div>
</template>

<script>
import areYouSure from '../basicComponents/areYouSure.vue'
import dropDown from '../basicComponents/dropdown.vue'
export default {
    components: { dropDown, areYouSure },
    props: { task: Object },
    data: function () {
        return {
            deleting: false,
            change: false,
            editing: false,
            entered: false,
            selected: false,
            text: this.task.task_name,
        }
    },
    methods: {
        reset() {
            ;(this.editing = false),
                (this.entered = false),
                (this.selected = false),
                (this.change = false)
        },
        textareaResize() {
            this.$refs.textarea.style.height = 'auto'
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + 'px'
        },
        deleteTask() {
            this.$emit('delete', this.task)
        },
        startEditing() {
            this.editing = true
        },
        oldValue() {
            this.text = this.task.task_name
            this.reset()
        },
        updateTask() {
            if (this.text !== '' && this.text !== this.task.task_name) {
                axios.patch(`${this.$app_url}task/${this.task.id}`, {
                    task_name: this.text,
                })
                this.reset()
            }
        },
    },
    mounted() {
        this.textareaResize()
    },
}
</script>
