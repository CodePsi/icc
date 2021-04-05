Vue.component('icc-header', {
    methods: {

    },
    data() {
        return {
            links: [
                { href: "/icc/control-page/menu", title: "Головна" },
                { href: "/icc/control-page/requests", title: "Заявки" },
                { href: "/icc/control-page/employees", title: "Працівники" },
                { href: "/icc/control-page/stock", title: "Склад" },
                { href: "/icc/control-page/acts/members", title: "Члени комісії" },
                { href: "/icc/control-page/writeOffPage", title: "Акти списання" },
                { href: "/icc/control-page/acts/actOfInstallation", title: "Акти установки" },
            ]
        };
    },
    template: `
        <div class="header-container">
            <a class="standard-button" v-for="link in links" :href="link.href">{{link.title}}</a>
        </div>
    `
});