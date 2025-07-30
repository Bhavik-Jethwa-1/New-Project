import './bootstrap';
import '../css/app.css'
import { createPinia } from 'pinia';
import axios from 'axios'
import { useAuthStore } from './store/auth';

import { createApp } from 'vue';

import App from './App.vue';
import router from './routes/auth';

axios.defaults.baseURL = 'http://127.0.0.1:8000'

axios.interceptors.request.use(config => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

const pinia = createPinia()

const app = createApp(App);

app.use(router).use(pinia).mount('#app');