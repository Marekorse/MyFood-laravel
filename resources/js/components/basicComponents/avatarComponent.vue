<template>
    <div>
        <img
            draggable="false"
            v-bind:style="{
                minWidth: size + 'px',
                minheight: size + 'px',
                height: size + 'px',
                width: size + 'px',
                borderRadius: rounded + 'px',
                borderWidth: '2px',
            }"
            @click="showImage()"
            :class="{ pointer: showOnClick }"
            :src="imgSrc"
            alt="" />
        <modal v-if="showModal" @closeModal="showModal = false">
            <img
                class="w-full h-auto md:w-5/6 xl:w-4/6 rounded-lg border-4 border-gray-100"
                :src="path + image.url"
                alt="" />
        </modal>
    </div>
</template>

<script>
import modal from './modal.vue'
export default {
    data: function () {
        return {
            showModal: false,
            path: this.$storage_path,
            imgSrc: null,
            default: this.$storage_path + 'images/Default/userAvatar.svg',
        }
    },
    components: {
        modal,
    },
    props: {
        image: {
            type: Object,
            default: null,
        },
        showOnClick: {
            type: Boolean,
            default: false,
        },
        thumb: {
            type: Boolean,
            default: false,
        },
        size: {
            type: Number,
            default: 80,
        },
        rounded: {
            type: Number,
            default: 9999,
        },
        private: {
            type: Boolean,
            default: false,
        },
    },
    watch: {
        image: {
            handler(newValue, oldValue) {
                if (typeof newValue === 'object') {
                    this.imageCheck()
                } else {
                    this.imgSrc = newValue
                }
            },
            deep: true,
        },
    },
    methods: {
        imageCheck() {
            if (this.private) {
                this.path = this.$app_url
            }

            if (this.image && this.thumb) {
                this.imgSrc = this.path + this.image.thumbnail_url
            } else if (this.image && !this.thumb) {
                this.imgSrc = this.path + this.image.url
            } else if (!this.image) {
                this.imgSrc = this.default
            }
        },
        showImage() {
            if (this.showOnClick && this.image) {
                this.showModal = true
            }
            return
        },
    },
    created() {
        this.imageCheck()
    },
}
</script>
<style lang="scss" scoped>
.pointer {
    cursor: pointer;
}
</style>
