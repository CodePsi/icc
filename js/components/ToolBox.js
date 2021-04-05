Vue.component('toolbox', {
    props: {
        width: {
            type: Number,
            default: 20,
            required: false
        },
        type: String,
        materialIcon: {
            type: String,
            required: false,
            default: ''
        },
        text: {
            type: String,
            required: false,
            default: ''
        }
    },
    methods: {

    },
    template: `
        <button @click="$emit('click')" class="icon-button"><span class="material-icons md-18">{{ materialIcon }}</span>{{ text }}</button>
    `
});