Vue.component('search-field', {
    props: ['modelValue'],
    // emits: ['update:modelValue'],
    template: `
        <div class="search-field">
                <span class="text-caption">Пошук </span><input class="input-box" v-bind:value="modelValue" @input="$emit('input', $event.target.value)" autocomplete="off">
        </div>
    `
});