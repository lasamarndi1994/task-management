require('./bootstrap');
import { createApp } from 'vue'
import Test from './App.vue'
import Modal from './Modal.vue'

const app = createApp({})

app.component('TaskComponent', Test)
app.component('v-modal', Modal)

app.mount('#app')
