<template>
  <v-container>
    <v-row>
      <v-col md="10" class="mx-auto">
        <card-component title="Busca de marcas">
          <template v-slot:content>
            <v-row>
              <v-col class="mb-3">
                <input-container-component
                  id="buscaId"
                  title="ID"
                  id-help="idHelp"
                  text-help="Opcional. Informe o ID do registro"
                >
                  <input
                    v-model="busca.id"
                    type="number"
                    id="buscaId"
                    class="form-control"
                    aria-describedby="idHelp"
                  />
                </input-container-component>
              </v-col>

              <v-col class="mb-3">
                <input-container-component
                  id="buscaNome"
                  title="Nome"
                  id-help="nomeHelp"
                  text-help="Opcional. Informe o nome da marca"
                >
                  <input
                    v-model="busca.nome"
                    type="text"
                    id="buscaNome"
                    class="form-control"
                    aria-describedby="nomeHelp"
                  />
                </input-container-component>
              </v-col>
            </v-row>
          </template>

          <template v-slot:footer>
            <button
              class="btn btn-primary btn-sm float-end"
              @click="searchList()"
            >
              Buscar
            </button>
          </template>
        </card-component>

        <card-component title="Relação de marcas">
          <template v-slot:content>
            <v-row>
              <v-col>
                <v-btn
                  color="success"
                  @click="
                    openDialog({
                      title: 'Cadastrar marca',
                      operation: 'create',
                    })
                  "
                >
                  Adicionar
                </v-btn>
              </v-col>
            </v-row>

            <v-simple-table>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">ID</th>
                    <th class="text-left">Nome</th>
                    <th class="text-left">Logo</th>
                    <th class="text-left">Data de cadastro</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="marca in marcas" :key="marca.id">
                    <td>{{ marca.id }}</td>
                    <td>{{ marca.nome }}</td>
                    <td>
                      <img
                        :src="`/storage/${marca.imagem}`"
                        :alt="`Logo ${marca.nome}`"
                        width="30"
                      />
                    </td>
                    <td>{{ marca.created_at | formatDate }}</td>
                    <td>
                      <v-btn
                        elevation="2"
                        small
                        outlined
                        color="info"
                        @click="
                          openDialog(
                            {
                              title: 'Visualizar marca',
                              operation: 'show',
                            },
                            marca
                          )
                        "
                      >
                        Visualizar
                      </v-btn>
                      <v-btn
                        elevation="2"
                        small
                        outlined
                        color="blue-grey"
                        @click="
                          openDialog(
                            {
                              title: 'Atualizar marca',
                              operation: 'edit',
                            },
                            marca
                          )
                        "
                      >
                        Editar
                      </v-btn>
                      <v-btn
                        elevation="2"
                        small
                        outlined
                        color="red"
                        @click="
                          openDialog(
                            {
                              title: 'Remover marca',
                              operation: 'destroy',
                            },
                            marca
                          )
                        "
                      >
                        Remover
                      </v-btn>
                    </td>
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

        <div class="text-center">
          <v-dialog v-model="dialog" width="500">
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                {{ $store.state.form.title }}
              </v-card-title>

              <v-card-text>
                <alert-component
                  class="py-2"
                  v-if="$store.state.transaction.status"
                  :type="$store.state.transaction.status"
                  :title="$store.state.transaction.title"
                  :message="$store.state.transaction.message"
                  :errors="$store.state.transaction.errors"
                >
                </alert-component>

                <template
                  v-if="
                    $store.state.form.operation == 'create' ||
                    $store.state.form.operation == 'edit'
                  "
                >
                  <v-text-field
                    v-model="$store.state.item.nome"
                    label="Nome"
                    required
                  ></v-text-field>

                  <v-file-input
                    show-size
                    v-model="imagem"
                    truncate-length="15"
                    accept="image/*"
                    label="Anexe uma imagem"
                  ></v-file-input>
                </template>

                <template v-if="$store.state.form.operation == 'show'">
                  <div class="row">
                    <div class="col-3 mb-3">
                      <input-container-component title="ID">
                        <input
                          type="number"
                          class="form-control"
                          :value="$store.state.item.id"
                          disabled
                        />
                      </input-container-component>
                    </div>
                    <div class="col-4 mb-3">
                      <input-container-component title="Nome">
                        <input
                          type="text"
                          class="form-control"
                          :value="$store.state.item.nome"
                          disabled
                        />
                      </input-container-component>
                    </div>
                    <div class="col-5 mb-3">
                      <input-container-component title="Data de cadastro">
                        <input
                          type="text"
                          class="form-control"
                          :value="$store.state.item.created_at | formatDate"
                          disabled
                        />
                      </input-container-component>
                    </div>
                    <div class="col-12 mb-3">
                      <input-container-component title="Imagem" v-if="imagem">
                        <img
                          :src="`/storage/${imagem}`"
                          alt="Logo"
                          width="60"
                        />
                      </input-container-component>
                    </div>
                  </div>
                </template>

                <template v-if="$store.state.form.operation == 'destroy'">
                  <div class="row">
                    <div class="col-3 mb-3">
                      <input-container-component title="ID">
                        <input
                          type="number"
                          class="form-control"
                          :value="$store.state.item.id"
                          disabled
                        />
                      </input-container-component>
                    </div>
                    <div class="col-9 mb-3">
                      <input-container-component title="Nome">
                        <input
                          type="text"
                          class="form-control"
                          :value="$store.state.item.nome"
                          disabled
                        />
                      </input-container-component>
                    </div>
                  </div>
                </template>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  v-if="$store.state.form.operation == 'create'"
                  color="primary"
                  @click="save()"
                >
                  Salvar
                </v-btn>

                <v-btn
                  v-if="$store.state.form.operation == 'edit'"
                  color="info"
                  @click="update()"
                >
                  Atualizar
                </v-btn>

                <v-btn
                  v-if="
                    $store.state.form.operation == 'destroy' &&
                    $store.state.transaction.status !== 'success'
                  "
                  color="red"
                  @click="destroy()"
                >
                  Remover
                </v-btn>

                <v-btn color="primary" @click="dialog = false"> Fechar </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>

        <!-- Modal Form -->
        <modal-component id="modalForm" :title="$store.state.form.title">
          <template v-slot:alerts> </template>

          <template v-slot:content> </template>

          <template v-slot:footer> </template>
        </modal-component>
        <!--/ Modal Form -->
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      baseUrl: "/v1/marca",
      urlFilter: "",
      marcas: [],
      busca: { id: "", nome: "" },
      pagination: {
        current: 1,
        total: 0,
      },
      imagem: [],
    };
  },
  mounted() {
    this.loadList();
  },
  methods: {
    searchList() {
      let filter = "";

      for (let key in this.busca) {
        if (this.busca[key]) {
          if (filter != "") {
            filter += ";";
          }

          filter += key + ":like:" + this.busca[key];
        }
      }

      if (filter) {
        this.urlFilter = "&filtro=" + filter;
      } else {
        this.urlFilter = "";
      }

      this.loadList();
    },
    loadList() {
      let url =
        this.baseUrl + "?page=" + this.pagination.current + this.urlFilter;

      axios
        .get(url)
        .then((response) => {
          this.marcas = response.data.data;

          this.pagination.current = response.data.current_page;
          this.pagination.total = response.data.last_page;
          this.pagination.per_page = response.data.per_page;
        })
        .catch((errors) => {
          console.error(errors);
        });
    },
    createForm() {
      this.$store.state.form.operation = "create";
      this.$store.state.form.title = "Adicionar marca";
    },
    save() {
      let formData = new FormData();

      formData.append("nome", this.$store.state.item.nome);
      formData.append("imagem", this.imagem);

      let options = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      axios
        .post(this.baseUrl, formData, options)
        .then((response) => {
          this.$store.state.transaction.status = "success";
          this.$store.state.transaction.title =
            "Cadastro realizado com sucesso";
          this.$store.state.transaction.message = `ID do registro: ${response.data.id}`;

          this.imagem = [];

          this.loadList();
        })
        .catch((errors) => {
          this.$store.state.transaction.status = "danger";
          this.$store.state.transaction.title =
            "Erro ao tentar cadastrar a marca";
          this.$store.state.transaction.errors = errors.response.data.errors;
        });
    },
    update() {
      let url = this.baseUrl + "/" + this.$store.state.item.id;

      let formData = new FormData();

      formData.append("nome", this.$store.state.item.nome);

      if (this.imagem) {
        formData.append("_method", "put");
        formData.append("imagem", this.imagem);
      } else {
        formData.append("_method", "patch");
      }

      let options = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      axios
        .post(url, formData, options)
        .then((response) => {
          this.$store.state.transaction.status = "success";
          this.$store.state.transaction.title =
            "Cadastro atualizado com sucesso";
          this.$store.state.transaction.message = response.data.msg;
          this.$store.state.transaction.errors = "";

          this.imagem = [];

          this.loadList();
        })
        .catch((errors) => {
          console.log("errors", errors);
          this.$store.state.transaction.status = "danger";
          this.$store.state.transaction.title =
            "Erro ao tentar atualizar a marca";
          this.$store.state.transaction.message = "";
          this.$store.state.transaction.errors = errors.response.data.errors;
        });
    },
    destroy() {
      let confirmed = confirm("Tem certeza que deseja remover esse registro?");

      if (!confirmed) {
        return false;
      }

      let url = this.baseUrl + "/" + this.$store.state.item.id;

      axios
        .delete(url)
        .then((response) => {
          this.$store.state.item.nome = "";

          this.$store.state.transaction.status = "success";
          this.$store.state.transaction.title =
            "Transação realizada com sucesso";
          this.$store.state.transaction.message = response.data.msg;

          this.loadList();
        })
        .catch((errors) => {
          this.$store.state.transaction.status = "danger";
          this.$store.state.transaction.title = "Erro na transação";
          this.$store.state.transaction.errors = errors.response.data.errors;
        });
    },
    openDialog(form, obj = {}) {
      this.$store.state.item = obj;

      this.$store.state.transaction.status = "";
      this.$store.state.transaction.title = "";
      this.$store.state.transaction.message = "";
      this.$store.state.transaction.errors = "";

      this.$store.state.form.title = form.title;
      this.$store.state.form.operation = form.operation;

      this.dialog = true;
    },
  },
};
</script>