<template>
    <li>
        <a style="font-size: 120%;" target="_blank" @click="goTo(go_to_page)">
            <span>
                <!-- <i class="fa fa-user-circle"></i> -->
                
                <span v-html="icon"></span>
            </span>
        </a>
    </li>
</template>

<script>
    export default {
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        props:{
            go_to_page: {
                // default: 2016,
            },
            icon: {
                default: '<i class="fa fa-user-circle"></i>'
            }
            // 'go_to_page',
            // 'icon'
        },
        methods: {
            goTo(page){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page)
            },
        }
    }
</script>

<style>

</style>