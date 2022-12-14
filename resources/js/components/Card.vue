<template>
  <v-card elevation="1" class="mb-3">
    <v-card-title>{{ title }}</v-card-title>
    <v-card-text>
      <slot name="content"></slot>
    </v-card-text>
    <v-card-actions>
      <slot name="footer"></slot>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  props: ["title"],
};
</script>
