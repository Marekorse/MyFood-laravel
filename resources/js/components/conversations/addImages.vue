<template>
    <div>
        <div class="relative">
            <label for="fileInput">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 cursor-pointer"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </label>
            <input
                class="hidden"
                id="fileInput"
                type="file"
                multiple
                accept="image/jpeg"
                @change="addFiles" />
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            images: [],
            preview: [],
        }
    },
    methods: {
        addFiles(e) {
            const selectedFiles = e.target.files
            if (this.fileValidation(selectedFiles[0]['type'])) {
                for (let i = 0; i < selectedFiles.length; i++) {
                    this.images.push(selectedFiles[i])
                }

                for (let i = 0; i < selectedFiles.length; i++) {
                    let reader = new FileReader()
                    reader.onload = (e) => {
                        this.preview.push(reader.result)
                    }

                    reader.readAsDataURL(selectedFiles[i])
                }
                this.$emit('newImages', this.images)
                this.reset()
            }
        },
        fileValidation(type) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif']
            return allowedTypes.includes(type)
        },
        reset() {
            this.images = []
            this.preview = []
        },
    },
}
</script>
