<template>
    <dropdown class="ml-8 h-9 flex items-center dropdown-right">
        <dropdown-trigger class="h-9 flex items-center">
            <div class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path class="heroicon-ui"
                          d="M4.06 13a8 8 0 0 0 5.18 6.51A18.5 18.5 0 0 1 8.02 13H4.06zm0-2h3.96a18.5 18.5 0 0 1 1.22-6.51A8 8 0 0 0 4.06 11zm15.88 0a8 8 0 0 0-5.18-6.51A18.5 18.5 0 0 1 15.98 11h3.96zm0 2h-3.96a18.5 18.5 0 0 1-1.22 6.51A8 8 0 0 0 19.94 13zm-9.92 0c.16 3.95 1.23 7 1.98 7s1.82-3.05 1.98-7h-3.96zm0-2h3.96c-.16-3.95-1.23-7-1.98-7s-1.82 3.05-1.98 7zM12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"/>
                </svg>
            </div>

            <span class="text-90">{{ locales[currentLocale] }}</span>
        </dropdown-trigger>

        <dropdown-menu slot="menu" width="200" direction="rtl">
            <ul class="list-reset">
                <li v-for="(name, locale) in localesWithoutCurrent" :key="locale" :data="locale">
                    <a href="#" @click.prevent="switchLocale(locale)"
                       class="block no-underline text-90 hover:bg-30 p-3">
                        {{ name }}
                    </a>
                </li>
            </ul>
        </dropdown-menu>
    </dropdown>
</template>

<script>
import _ from "lodash";

export default {
    data: () => ({
        locales: []
    }),

    mounted() {
        this.locales = { 'ar': 'عربي', 'en': 'English' }
    },

    computed: {
        currentLocale() {
            return window.config.locale ? window.config.locale : 'en'
        },

        localesWithoutCurrent() {
            return _.omit(this.locales, this.currentLocale)
        }
    },

    methods: {
        switchLocale(locale) {
            const queryParams = new URLSearchParams(window.location.search);
            queryParams.set('lang', locale);
            history.replaceState(null, null, '?' + queryParams.toString());
            window.location.reload()
        }
    }
}
</script>
