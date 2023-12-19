import './bootstrap';
import '../scss/app.scss';
import {createApp} from 'vue';


import patients_index from './components/patients/Index.vue';
import functions from './components/common/Functions.vue'

createApp(patients_index).mixin(functions).mount("#patients-index");
