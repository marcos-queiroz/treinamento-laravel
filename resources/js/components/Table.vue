<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th v-for="(t, key) in title" :key="key">
          {{ t.title }}
        </th>
        <th v-if="show.visible || edit || destroy">Opções</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(obj, i) in filteredData" :key="i">
        <td v-for="(value, key) in obj" :key="key">
          <span v-if="title[key].type === 'string'">
            {{ value }}
          </span>
          <span v-if="title[key].type === 'date'">
            {{ value | formatDate }}
          </span>
          <span v-if="title[key].type === 'image'">
            <img :src="`/storage/${value}`" alt="Logo" width="30" />
          </span>
        </td>
        <td v-if="show.visible || edit || destroy">
          <button
            v-if="show.visible"
            class="btn btn-outline-info btn-sm"
            :data-bs-toggle="show.dataToggle"
            :data-bs-target="show.dataTarget"
            @click="setStore(obj, show)"
          >
            Visualizar
          </button>
          <button
            v-if="edit.visible"
            class="btn btn-outline-primary btn-sm"
            :data-bs-toggle="edit.dataToggle"
            :data-bs-target="edit.dataTarget"
            @click="setStore(obj, edit)"
          >
            Editar
          </button>
          <button
            v-if="destroy.visible"
            class="btn btn-outline-danger btn-sm"
            :data-bs-toggle="destroy.dataToggle"
            :data-bs-target="destroy.dataTarget"
            @click="setStore(obj, destroy)"
          >
            Remover
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["title", "data", "show", "edit", "destroy"],
  computed: {
    filteredData() {
      let fields = Object.keys(this.title);
      let newData = [];

      this.data.map((i, key) => {
        let filteredItem = {};

        fields.forEach((field) => {
          filteredItem[field] = i[field];
        });

        newData.push(filteredItem);
      });

      return newData;
    },
  },
  methods: {
    setStore(obj, form) {
      this.$store.state.item = obj;

      this.$store.state.transaction.status = "";
      this.$store.state.transaction.title = "";
      this.$store.state.transaction.message = "";
      this.$store.state.transaction.errors = "";

      this.$store.state.form.title = form.title;
      this.$store.state.form.operation = form.operation;
    },
  },
};
</script>
