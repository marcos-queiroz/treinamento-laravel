<template>
  <v-container>
    <v-row>
      <v-col md="10" class="mx-auto">
        <card-component title="Busca de carros">
          <template v-slot:content>
            <v-form>
              <v-container>
                <v-row>
                  <v-col cols="2">
                    <v-text-field v-model="busca.id" label="ID"></v-text-field>
                  </v-col>
                  <v-col cols="10">
                    <v-text-field
                      v-model="busca.placa"
                      label="Placa"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </template>

          <template v-slot:footer>
            <v-btn color="primary" outlined @click="searchList()">
              Buscar
            </v-btn>
          </template>
        </card-component>

        <card-component title="Relação de carros">
          <template v-slot:content>
            <v-simple-table>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">ID</th>
                    <th class="text-left">Placa</th>
                    <th class="text-left">KM</th>
                    <th class="text-left">Modelo</th>
                    <th class="text-left">Lugares</th>
                    <th class="text-left">Airbag</th>
                    <th class="text-left">Portas</th>
                    <th class="text-left">Disponível</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="carro in carros" :key="carro.id">
                    <td>{{ carro.id }}</td>
                    <td>{{ carro.placa }}</td>
                    <td>{{ carro.km }}</td>
                    <td>{{ carro.modelo.nome }}</td>
                    <td>{{ carro.modelo.lugares }}</td>
                    <td>{{ carro.modelo.air_bag }}</td>
                    <td>{{ carro.modelo.numero_portas }}</td>
                    <td>{{ carro.disponivel ? "Sim" : "Não" }}</td>
                    <td></td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </template>

          <template v-slot:footer>
            <v-pagination
              v-model="pagination.current"
              :length="pagination.total"
              :total-visible="pagination.per_page"
              @input="loadList"
            ></v-pagination>
          </template>
        </card-component>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      baseUrl: "/v1/carro",
      urlFilter: "",
      carros: [],
      busca: { id: "", placa: "" },
      pagination: {
        current: 1,
        total: 0,
      },
    };
  },
  mounted() {
    this.loadList();
  },
  methods: {
    loadList() {
      let url =
        this.baseUrl + "?page=" + this.pagination.current + this.urlFilter;

      axios
        .get(url)
        .then((response) => {
          this.carros = response.data.data;

          this.pagination.current = response.data.current_page;
          this.pagination.total = response.data.last_page;
          this.pagination.per_page = response.data.per_page;
        })
        .catch((errors) => {
          console.error(errors);
        });
    },
  },
};
</script>
