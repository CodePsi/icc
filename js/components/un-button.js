Vue.component('un-button', {
    props: {
        type: {
            type: String,
            required: false,
            default: 'common'
        }
    },
    methods: {
        getButtonClass: function () {
            var elementClass = 'un-button';
            if (this.type === "common") {
                elementClass += ' common-button';
            } else if (this.type === "cancel") {
                elementClass += ' cancel-button'
            }

            return elementClass;
        }
    },
    template: `
        <button type="button" :class="getButtonClass()" @click="$emit('click')">
            <slot></slot>
        </button>
    `
});