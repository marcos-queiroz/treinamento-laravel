<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <card-component title="Busca de marcas">
          <template v-slot:content>
            <div class="row">
              <div class="col mb-3">
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
              </div>

              <div class="col mb-3">
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
              </div>
            </div>
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
            <table-component
              :title="titleTable"
              :data="dataTable"
              :show="{
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalForm',
                title: 'Visualizar marca',
                operation: 'show',
              }"
              :edit="{
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalForm',
                title: 'Atualizar marca',
                operation: 'edit',
              }"
              :destroy="{
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalForm',
                title: 'Remover marca',
                operation: 'destroy',
              }"
            ></table-component>
          </template>

          <template v-slot:footer>
            <div class="row">
              <div class="col-10">
                <paginate-component>
                  <li
                    class="page-item"
                    v-for="(l, key) in marcas.links"
                    :key="key"
                  >
                    <a
                      class="page-link"
                      :class="l.active ? 'active' : ''"
                      @click="paginate(l)"
                      v-html="l.label"
                    ></a>
                  </li>
                </paginate-component>
              </div>
              <div class="col-2 py-1">
                <button
                  class="btn btn-success btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#modalForm"
                  @click="createForm()"
                >
                  Adicionar
                </button>
              </div>
            </div>
          </template>
        </card-component>

        <!-- Modal Form -->
        <modal-component id="modalForm" :title="$store.state.form.title">
          <template v-slot:alerts>
            <alert-component
              v-if="$store.state.transaction.status"
              :type="$store.state.transaction.status"
              :title="$store.state.transaction.title"
              :message="$store.state.transaction.message"
              :errors="$store.state.transaction.errors"
            >
            </alert-component>
          </template>

          <template v-slot:content>
            <template
              v-if="
                $store.state.form.operation == 'create' ||
                $store.state.form.operation == 'edit'
              "
            >
              <input-container-component
                id="inputNome"
                title="Nome"
                id-help="nomeHelp"
                text-help="Informe o nome da marca"
              >
                <input
                  v-model="$store.state.item.nome"
                  type="text"
                  id="inputNome"
                  class="form-control"
                  aria-describedby="nomeHelp"
                />
              </input-container-component>

              <input-container-component
                id="inputImagem"
                title="Imagem"
                id-help="imagemHelp"
                text-help="Selecione uma imagem no formato PNG"
              >
                <input
                  @change="loadFile($event)"
                  type="file"
                  id="inputImagem"
                  class="form-control"
                  aria-describedby="imagemHelp"
                />
              </input-container-component>
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
                  <input-container-component
                    title="Imagem"
                    v-if="$store.state.item.imagem"
                  >
                    <img
                      :src="`/storage/${$store.state.item.imagem}`"
                      alt="Logo"
                      width="60"
                    />
                  </input-container-component>
                </div>
              </div>
            </template>

            <div v-if="$store.state.form.operation == 'destroy'">
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
            </div>
          </template>

          <template v-slot:footer>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Fechar
            </button>
            <button
              v-if="$store.state.form.operation == 'create'"
              type="button"
              class="btn btn-primary"
              @click="save()"
            >
              Salvar
            </button>
            <button
              v-if="$store.state.form.operation == 'edit'"
              type="button"
              class="btn btn-primary"
              @click="update()"
            >
              Atualizar
            </button>
            <button
              v-if="
                $store.state.form.operation == 'destroy' &&
                $store.state.transaction.status !== 'success'
              "
              type="button"
              class="btn btn-danger"
              @click="destroy()"
            >
              Remover
            </button>
          </template>
        </modal-component>
        <!--/ Modal Form -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      baseUrl: "http://app.locadora.localhost/api/v1/marca",
      urlPaginate: "",
      urlFilter: "",
      arquivoImagem: [],
      titleTable: {
        id: { title: "ID", type: "string" },
        nome: { title: "Nome", type: "string" },
        imagem: { title: "Imagem", type: "image" },
        created_at: { title: "Data de cadastro", type: "date" },
      },
      dataTable: [],
      marcas: [],
      busca: { id: "", nome: "" },
    };
  },
  computed: {
    token() {
      let token = document.cookie.split(";").find((i) => {
        return i.includes("token=");
      });

      token = token.split("=")[1];

      return `Bearer ${token}`;
    },
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
        this.urlPaginate = "page=1";
        this.urlFilter = "&filtro=" + filter;
      } else {
        this.urlFilter = "";
      }

      this.loadList();
    },
    paginate(link) {
      if (link.url) {
        this.urlPaginate = link.url.split("?")[1];
        this.loadList();
      }
    },
    loadList() {
      let url = this.baseUrl + "?" + this.urlPaginate + this.urlFilter;

      let options = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .get(url, options)
        .then((response) => {
          this.marcas = response.data;

          this.dataTable = this.marcas.data;
        })
        .catch((errors) => {
          console.error(erros);
        });
    },
    loadFile(e) {
      this.arquivoImagem = e.target.files;
    },
    createForm() {
      this.$store.state.form.operation = "create";
      this.$store.state.form.title = "Adicionar marca";
    },
    save() {
      let formData = new FormData();

      formData.append("nome", this.$store.state.item.nome);
      formData.append("imagem", this.arquivoImagem[0]);

      let options = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(this.baseUrl, formData, options)
        .then((response) => {
          this.$store.state.transaction.status = "success";
          this.$store.state.transaction.title =
            "Cadastro realizado com sucesso";
          this.$store.state.transaction.message = `ID do registro: ${response.data.id}`;

          inputImagem.value = "";

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

      if (this.arquivoImagem[0]) {
        formData.append("_method", "put");
        formData.append("imagem", this.arquivoImagem[0]);
      } else {
        formData.append("_method", "patch");
      }

      let options = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
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

          inputImagem.value = "";

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

      let options = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .delete(url, options)
        .then((response) => {
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
  },
};
</script>