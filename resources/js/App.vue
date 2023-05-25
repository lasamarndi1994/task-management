<template>
    <div class="container">
        <div class="row mt-5">
            <!-- Button trigger modal -->
            <button
                type="button"
                class="btn btn-success"
                @click="modalShow = true"
            >
                <i class="fa fa-plus ms-2"></i> Create a Project
            </button>

            <!-- Modal -->
            <v-modal :modalShow="modalShow" :headerName="'Create Project'">
                <form method="post" @submit.prevent="createProject">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taskName" class="form-label"
                                >Project Name *</label
                            >
                            <input
                                type="text"
                                required
                                class="form-control"
                                id="projectName"
                                v-model="project_form.name"
                                aria-describedby="taskhelp"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="modalShow = false"
                        >
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Create
                        </button>
                    </div>
                </form>
            </v-modal>

            <div class="container mt-2">
                <div class="row">
                    <div
                        class="col-sm"
                        v-for="(project, index) in projects"
                        :key="index"
                    >
                        <div class="card">
                            <ul class="list-group">
                                <li
                                    class="list-group-item active d-flex justify-content-between"
                                    aria-current="true"
                                >
                                    {{ project.name }}
                                    <i
                                        @click="
                                            removeProject(project.id, index)
                                        "
                                        class="fa fa-trash pointer text-danger"
                                    ></i>
                                </li>
                            </ul>

                            <draggable
                                class="list-group"
                                v-model="project.tasks"
                                group="tasks"
                                item-key="id"
                                @start="drag = true"
                                style="min-height: 50px"
                                @end="handleDragEnd"
                            >
                                <template #item="{ element, index }">
                                    <ul class="list-group">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start mt-2"
                                        >
                                            <div class="ms-2 me-auto">
                                                {{ element.task_name }}
                                            </div>
                                            <span
                                                class="badge bg-primary rounded-pill ml-2"
                                                :class="
                                                    setColorForBadge(
                                                        element.priority
                                                    )
                                                "
                                                >{{ element.priority }}</span
                                            >
                                            <div class="ms-2">
                                                <i
                                                    @click="
                                                        handleEdit(
                                                            element,
                                                            index
                                                        )
                                                    "
                                                    class="fa fa-edit pointer"
                                                ></i>

                                                <i
                                                    class="fa fa-trash text-danger pointer"
                                                    @click="
                                                        handleDelete(
                                                            project,
                                                            element,
                                                            index
                                                        )
                                                    "
                                                ></i>
                                            </div>
                                        </li>
                                    </ul>
                                </template>
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="text-center">
                    <button
                        class="btn btn-success text-center"
                        type="button"
                        :disabled="projects.length == 0"
                        @click="isTaskModal = true"
                    >
                        <i class="fa fa-plus ms-2"></i> Add task
                    </button>
                    <br />
                    <small class="text-danger" v-show="projects.length == 0"
                        >Create a project first</small
                    >
                </div>

                <create-task
                    @createTask="createTask"
                    :form="task_form"
                    @closeTaskModal="closeTaskModal"
                    :modalShow="isTaskModal"
                    :projects="projects"
                    :isTaskEdit="isTaskEdit"
                >
                </create-task>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import CreateTask from "./CreateTask.vue";

export default {
    components: {
        draggable,
        CreateTask,
    },
    data() {
        return {
            projects: [],
            drag: false,
            tasks: [],
            modalShow: false,
            isTaskModal: false,
            isTaskEdit: false,
            project_form: {
                name: "",
            },
            task_form: {
                task_name: "",
                project_id: "",
                priority: "",
            },
        };
    },
    created() {
        axios.get("/api/project").then((response) => {
            this.projects = response.data.data;
        });
    },
    methods: {
        handleDelete(project, task, index) {
            axios.delete(`api/task/${task.id}`).then((response) => {
                if (response.data.status == true) {
                    project.tasks.splice(index, 1);
                }
            });
        },
        setColorForBadge(type) {
            if (type == "medium") {
                return "bg-warning";
            } else if (type == "low") {
                return "bg-success";
            }
            return "bg-danger";
        },
        handleDragEnd() {
            axios
                .post("/api/task-reorder", { projects: this.projects })
                .then((res) => {
                    if (res.data.status == true) {
                        console.log("Successfully updates");
                    }
                });
        },
        createTask() {
            if (!this.isTaskEdit) {
                axios.post("/api/task", this.task_form).then((res) => {
                    if (res.data.status == true) {
                        this.pushCurrentTask(res.data.data);
                        this.emptyTaskForm();
                    }
                });
            } else {
                this.updateTask();
            }
        },
        pushCurrentTask(data) {
            this.projects.map((project) => {
                if (project.id == data.project_id) {
                    project.tasks.push(data);
                }
            });
        },
        emptyTaskForm() {
            this.isTaskModal = false;
            this.isTaskEdit = false;
            this.task_form = {
                task_name: "",
                project_id: "",
                priority: "",
            };
        },

        createProject() {
            axios.post("/api/project", this.project_form).then((res) => {
                if (res.data.status == true) {
                    this.modalShow = false;
                    this.projects.push(res.data.data);
                    this.project_form = {
                        name: "",
                    };
                }
            });
        },
        updateTask() {
            axios
                .put(`api/task/${this.task_form.id}`, this.task_form)
                .then((response) => {
                    if (response.data.status == true) {
                        this.pushCurrentTask(response.data.data);
                        this.emptyTaskForm();
                    }
                });
        },
        removeProject(id, index) {
            axios.delete(`/api/project/${id}`).then((response) => {
                if (response.data.status === true) {
                    this.projects.splice(index, 1);
                }
            });
        },

        handleEdit(task, index) {
            this.isTaskModal = true;
            this.isTaskEdit = true;
            this.task_form = {
                id: task.id,
                task_name: task.task_name,
                project_id: task.project_id,
                priority: task.priority,
            };
        },
        closeTaskModal() {
            this.emptyTaskForm();
        },
    },
};
</script>
<style scoped>
.pointer {
    cursor: pointer;
}
</style>
