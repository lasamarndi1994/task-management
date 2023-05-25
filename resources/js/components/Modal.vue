<template>
    <div>
        <div
            class="modal fade show"
            id="taskModal"
            tabindex="-1"
            :class="{ show: modalShow }"
            :style="modalShowHide"
            aria-modal="true"
            role="dialog"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="taskModalLabel">
                            {{ headerName }}
                        </h1>
                    </div>
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" v-show="modalShow"></div>
</template>
<script>
export default {
    emits: ["closeModal"],
    props: {
        modalShow: Boolean,
        headerName: String,
        modalTitle: { type: String, default: "" },
    },
    watch: {
        modalShow: function () {
            var body = document.querySelector("body");
            body.classList.toggle("modal-open");
            body.classList.toggle("body-modal");
        },
    },
    data() {
        return {};
    },
    created() {},
    methods: {
        closeModal() {
            this.$emit("closeModal");
        },
    },
    computed: {
        modalShowHide() {
            return this.modalShow
                ? "display:block; padding-left: 0px;"
                : "display:none;";
        },
    },
};
</script>
<style>
.body-modal {
    overflow: hidden;
    padding-right: 17px;
}
</style>
