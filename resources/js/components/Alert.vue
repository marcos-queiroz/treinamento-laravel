<template>
  <div :class="style" role="alert">
    {{ title }}
    <hr />
    <span v-if="message">{{ message }}</span>
    <ul v-if="errors">
      <li v-for="(erro, key) in errors" :key="key">
        {{ erro[0] }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ["type", "title", "message", "errors"],
  computed: {
    style() {
      return `alert alert-${this.type}`;
    },
  },
};
</script>
