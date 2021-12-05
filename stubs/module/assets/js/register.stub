import Vue        from 'vue';
import upperFirst from 'lodash/upperFirst';
import camelCase  from 'lodash/camelCase';

/*
*   Globally Register all Notification Components in the Notifications Folder
*/
const requireComponent = require.context('./Notifications', true, /[A-Z]\w+\.(vue|js)$/);
requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName)
    const componentName   = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

    // Register component globally
    Vue.component( componentName, componentConfig.default || componentConfig);
});
