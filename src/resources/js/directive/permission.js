import { hasPermission } from '../utils/helpers';

export default {
    created(el, binding, vnode) {
        const { value } = binding;

        if (value && value instanceof Array && value.length > 0) {
            if (value.includes('*')) return true;
            const hasAccess = hasPermission(value)

            if (!hasAccess) {
                el.style.display = 'none';
                // el.style.visibility = 'hidden';
            }
        } else {
            throw new Error(`Permissions are required! Example: v-permission="['manage user','manage permission']"`);
        }
    }
};
