<template>
    <div class="form-group">
        <label :for="id">{{ title }}</label>
        <slot></slot>
        <div :id="idHelp" class="form-text text-muted">{{ textHelp }}</div>
    </div>
</template>

<script>
export default {
    props: ['id', 'title', 'idHelp', 'textHelp']
}
</script>
