import './bootstrap';
/* import vuetify from "./vuetify"; */
import { createApp } from 'vue'
import App from './components/App.Vue'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// Vuetify


const vuetify = createVuetify({
    components,
    directives
  })

const app = createApp(App);
app.use(vuetify).mount("#app");