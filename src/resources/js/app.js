import { createApp } from 'vue'
import { Quasar, Cookies, Notify, AppFullscreen, Loading } from 'quasar'
import i18n from './i18n';
import store from './store'
import router from './router'
import { formatNumber } from './filters';
// Import icon libraries
import iconSet from 'quasar/icon-set/fontawesome-v6'
import '@quasar/extras/fontawesome-v6/fontawesome-v6.css'
// Import Quasar css
import 'quasar/src/css/index.sass'
// Assumes your root component is App.vue
// and placed in same folder as main.js
import App from './App.vue'
import permission from './directive/permission';
// Add Modules
import { bootLang } from './utils/boot_lang'
import addModules from './utils/add_modules'

const myApp = createApp(App)

myApp.use(i18n);
myApp.use(router);
myApp.use(store);

// Add Directives
myApp.directive('permission', permission);

// Add Filters
myApp.config.globalProperties.$filters = { formatNumber }

// Add Quasar
myApp.use(Quasar, {
  iconSet: iconSet,
  plugins: {
    Cookies,
    Notify,
    AppFullscreen,
    Loading
  }, // import Quasar plugins and add here
})

// Assumes you have a <div id="app"></div> in your index.html
myApp.mount('#app')
