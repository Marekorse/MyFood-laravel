<template>
    <div
        class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
        <p class="text-gray-600">
            {{ label }}
        </p>
        <div
            :class="{ 'border-l-4 border-blue-500 z-20': editing }"
            class="flex justify-between flex-wrap w-full">
            <div @dblclick="startEditing()" class="overflow-x-auto">
                <textarea
                    ref="textarea"
                    rows="1"
                    :disabled="!editing"
                    @input="textareaResize()"
                    @keydown.esc="resetValue()"
                    @keydown.enter="updateValue(name, inputValue)"
                    class="border-none m-0 py-0 h-auto focus:ring-0 bg-transparent max-h-28 resize-none"
                    type="text"
                    v-model="inputValue" />
            </div>

            <div class="flex items-center" v-if="editing">
                <li
                    class="cursor-pointer list-none text-blue-500 hover:text-blue-700 mr-2"
                    @click="updateValue(name, inputValue)">
                    {{ $t('global.save') }}
                </li>
                <li
                    class="cursor-pointer text-xs list-none text-red-500 hover:text-blue-700"
                    @click="resetValue()">
                    {{ $t('global.cancel') }}
                </li>
            </div>

            <li
                v-else
                @click="startEditing()"
                class="cursor-pointer list-none text-blue-500 hover:text-blue-700">
                {{ $t('global.edit') }}
            </li>
        </div>
        <are-you-sure
            v-if="areYouSure"
            @submitEvent="updateValue(name, inputValue)"
            @closeEvent="resetValue()"
            :message="$t('areYouSure.saveChanges')"
            :button="$t('global.save')"></are-you-sure>

        <div
            @click="blur()"
            v-if="editing"
            class="fixed w-screen h-screen bg-transparent z-10"></div>
    </div>
</template>

<script>
import areYouSure from '../basicComponents/areYouSure.vue'
export default {
    components: {
        areYouSure,
    },
    props: {
        label: String,
        name: String,
        value: String,
    },
    data: function () {
        return {
            areYouSure: false,
            editing: false,
            inputValue: this.value,
        }
    },
    methods: {
        sameValuesCheck() {
            return this.inputValue === this.value
        },
        blur() {
            if (this.sameValuesCheck()) {
                this.editing = false
                return
            }
            this.areYouSure = true
        },
        startEditing() {
            this.editing = true
            setTimeout(() => {
                this.$refs.textarea.focus()
            }, 400)
        },
        updateValue(name, value) {
            if (this.sameValuesCheck()) {
                this.editing = false
                return
            }
            this.$emit('updateUser', name, value)
            this.editing = false
        },
        resetValue() {
            this.areYouSure = false
            this.inputValue = this.value
            this.editing = false
            this.textareaResize()
        },
        textareaResize() {
            this.$refs.textarea.style.height = 'auto'
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + 'px'
        },
    },
    mounted() {
        this.textareaResize()
    },
}
</script>
