import './bootstrap';
import '../scss/app.scss';
import {createApp} from 'vue';


import patients_index from './components/patients/Index.vue';
import patients_edit from './components/patients/Edit.vue';
import messages_index from './components/messages/Index.vue';
import functions from './components/common/Functions.vue'

createApp(patients_index).mixin(functions).mount("#patients-index");
createApp(patients_edit).mixin(functions).mount("#patients-edit");
createApp(messages_index).mixin(functions).mount("#messages-index");
