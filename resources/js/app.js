import './bootstrap';
import '../scss/app.scss';
import {createApp} from 'vue';


import patients_index from './components/patients/Index.vue';
import patients_edit from './components/patients/Edit.vue';
import functions from './components/common/Functions.vue'

createApp(patients_index).mixin(functions).mount("#patients-index");
createApp(patients_edit).mixin(functions).mount("#patients-edit");
