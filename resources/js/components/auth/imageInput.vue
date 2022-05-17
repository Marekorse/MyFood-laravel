<template>
    <div class="relative flex justify-center flex-col my-3">
        <div
            @dragover.prevent
            @drop.prevent
            v-if="dropzone"
            class="flex items-center">
            <label
                @drop="dragFile"
                class="h-20 w-20 cursor-pointer border-2 border-gray-400 rounded-full"
                for="my-file">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-full text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </label>
        </div>
        <input
            name="file"
            type="file"
            hidden
            @change="uploadFile"
            id="my-file" />
        <div v-if="preview" class="">
            <figure
                class="relative w-20 h-20 rounded-full overflow-hidden border-white">
                <img class="w-full h-full object-cover" :src="preview" alt="" />
                <svg
                    @click.prevent="reset()"
                    xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 bottom-2 h-4 w-4 rounded-full flex items-center justify-center bg-red-600 text-white cursor-pointer p-0.5 z-20"
                    viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </figure>
        </div>
        <label
            for="my-file"
            class="text-sm mt-2 text-blue-400 font-semibold cursor-pointer"
            >{{ $t('nav.register.profilePicture') }}</label
        >
    </div>
</template>

<script>
export default {
    props: ['old-image'],

    data: function () {
        return {
            preview: this.oldImage,
            image: null,
            dropzone: true,
        }
    },
    methods: {
        previewImage(input) {
            if (input.files) {
                var reader = new FileReader()
                reader.onload = (e) => {
                    this.preview = e.target.result
                }
                this.image = input.files[0]
                reader.readAsDataURL(input.files[0])
            }
            this.$emit('newImage', this.image)
            this.dropzone = false
        },
        uploadFile(e) {
            this.previewImage(e.target)
        },
        dragFile(e) {
            this.previewImage(e.dataTransfer)
        },
        reset() {
            this.preview = null
            this.dropzone = true
            this.image = null
        },
    },
}
</script>
