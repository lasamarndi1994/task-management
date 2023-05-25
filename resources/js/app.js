require('./bootstrap');
import { createApp } from 'vue'
import App from './App.vue'
import Modal from './components/Modal.vue'

const app = createApp({})

app.component('TaskComponent', App)
app.component('v-modal', Modal)

app.mount('#app')
