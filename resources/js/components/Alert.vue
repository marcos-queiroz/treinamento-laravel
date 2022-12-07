<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr>
        <span v-if="msg.text">{{ msg.text }}</span>
        <ul v-if="msg.errors">
            <li v-for="erro, key in msg.errors" :key="key">
                {{ erro[0] }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['type', 'title', 'msg'],
    computed: {
        style() {
            return `alert alert-${this.type}`;
        }
    }
}
</script>
