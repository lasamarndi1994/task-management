<template>
    <v-modal :modalShow="modalShow" :headerName="headerName">
        <form method="post" @submit.prevent="createTask">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="taskName" class="form-label">Task Name *</label>
                    <input
                        type="text"
                        required
                        class="form-control"
                        id="projectName"
                        v-model="form.task_name"
                        aria-describedby="taskhelp"
                    />
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label"
                        >Select Project *</label
                    >
                    <select
                        class="form-contrl form-select"
                        required
                        v-model="form.project_id"
                    >
                        <option value="" disabled>Select project</option>
                        <option
                            v-for="(project, index) in projects"
                            :key="index"
                            :value="project.id"
                        >
                            {{ project.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label">Priority *</label>
                    <select
                        class="form-contrl form-select"
                        required
                        v-model="form.priority"
                    >
                        <option value="" disabled>Select task priority</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="closeTaskModal()"
                >
                    Close
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ isTaskEdit ? "Update" : "Create" }}
                </button>
            </div>
        </form>
    </v-modal>
</template>
<script>
export default {
    emits: ["closeTaskModal", "createTask"],
    props: {
        form: Object,
        modalShow: Boolean,
        isTaskEdit: Boolean,
        projects: Array,
    },
    data() {
        return {};
    },
    computed: {
        headerName() {
            return this.isTaskEdit ? "Update a Task" : "Create a Task";
        },
    },
    methods: {
        createTask() {
            this.$emit("createTask", this.form);
        },
        closeTaskModal() {
            this.$emit("closeTaskModal");
        },
    },
};
</script>
