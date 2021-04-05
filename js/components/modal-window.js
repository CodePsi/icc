Vue.component('modal', {
    props: {
        width: {
            type: Number,
            required: false
        },
        title: {
            type: String,
            required: false,
            default: 'Header'
        },
        visible: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data: function() {
        return {

        }
    },
    mounted: function() {
    },
    watch: {
        visible() {
            if (this.visible) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
                if (this.$listeners && this.$listeners.close) {
                    this.$emit('close');
                }
            }
        }
    },
    methods: {
        closeModalOnClickOnModalMask($event) {
            let target = $event.target;
            let className = target.classList[0];
            if (className === "modal-mask") {
                this.closeModal()
            }
        },
        closeModal() {
            this.$emit('update:visible', false)
        }
    },
    template: `
        <transition name="modal">
            <div class="modal-mask" v-show="visible" @click="closeModalOnClickOnModalMask($event)">
                <div class="modal-wrapper">
                    <div class="modal-container" :style="{width: width}">
    
                        <div class="modal-header">
                            <slot name="header">
                                <span class="modal-header-text">{{ title }}</span>
                            </slot>
                            <button @click="closeModal()" class="close-window-container">
                                <span class="material-icons md-18">close</span>
                            </button>
                        </div>
    
                        <div class="modal-body">
                            <slot name="body">
                                
                            </slot>
                        </div>
    
                        <div class="modal-footer">
                            <slot name="footer"></slot>
                        </div>
                    </div>
                </div>
            </div>
    </transition>
    `
});