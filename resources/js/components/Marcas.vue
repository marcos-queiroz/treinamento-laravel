<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component title="Busca de marcas">

                    <template v-slot:content>
                        <div class="row">

                            <div class="col mb-3">
                                <input-container-component id="inputId" title="ID" id-help="idHelp"
                                    text-help="Opcional. Informe o ID do registro">
                                    <input type="number" id="inputId" class="form-control" aria-describedby="idHelp">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component id="inputNome" title="Nome" id-help="nomeHelp"
                                    text-help="Opcional. Informe o nome da marca">
                                    <input type="text" id="inputNome" class="form-control" aria-describedby="nomeHelp">
                                </input-container-component>
                            </div>

                        </div>
                    </template>

                    <template v-slot:footer>
                        <button class="btn btn-primary btn-sm float-end">Buscar</button>
                    </template>

                </card-component>

                <card-component title="Relação de marcas">

                    <template v-slot:content>
                        <table-component :title="titleTable" :data="dataTable"></table-component>
                    </template>

                    <template v-slot:footer>
                        <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal"
                            data-bs-target="#modalMarca">Adicionar</button>
                    </template>

                </card-component>

                <modal-component id="modalMarca" title="Adicionar marca">

                    <template v-slot:alerts>
                        <alert-component v-if="statusType !== ''" :type="statusType" :title="statusTitle"
                            :msg="statusMsg"></alert-component>
                    </template>

                    <template v-slot:content>

                        <input-container-component id="inputNovoNome" title="Nome" id-help="novNomeHelp"
                            text-help="Informe o nome da marca">
                            <input v-model="nomeMarca" type="text" id="inputNovoNome" class="form-control"
                                aria-describedby="novNomeHelp">
                        </input-container-component>

                        <input-container-component id="inputImagem" title="Imagem" id-help="imagemHelp"
                            text-help="Selecione uma imagem no formato PNG">
                            <input @change="loadFile($event)" type="file" id="inputImagem" class="form-control"
                                aria-describedby="imagemHelp">
                        </input-container-component>

                    </template>

                    <template v-slot:footer>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="save()">Salvar</button>
                    </template>
                </modal-component>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            baseUrl: 'http://app.locadora.localhost/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: [],
            statusType: '',
            statusTitle: '',
            statusMsg: {},
            titleTable: {
                'id': { 'title': 'ID', 'type': 'string' },
                'nome': { 'title': 'Nome', 'type': 'string' },
                'imagem': { 'title': 'Imagem', 'type': 'image' },
                'created_at': { 'title': 'Data de cadastro', 'type': 'date' },
            },
            dataTable: [],
            marcas: [],
        }
    },
    computed: {
        token() {
            let token = document.cookie.split(';').find(i => {
                return i.includes('token=');
            });

            token = token.split('=')[1];

            return `Bearer ${token}`;
        }
    },
    mounted() {
        this.loadList();
    },
    methods: {
        loadList() {
            let options = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            };

            axios.get(this.baseUrl, options)
                .then(response => {
                    this.marcas = response.data;

                    this.dataTable = this.marcas.data;
                })
                .catch(errors => {
                    console.error(erros);
                });
        },
        loadFile(e) {
            this.arquivoImagem = e.target.files;
        },
        save() {
            this.statusType = '';

            let formData = new FormData();

            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);

            let options = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            };

            axios.post(this.baseUrl, formData, options)
                .then(response => {
                    this.statusType = 'success';
                    this.statusTitle = 'Cadastro realizado com sucesso';
                    this.statusMsg = {
                        text: `ID do registro: ${response.data.id}`
                    }

                    this.loadList();
                })
                .catch(errors => {
                    this.statusType = 'danger';
                    this.statusTitle = 'Erro ao tentar cadastrar a marca';
                    this.statusMsg = {
                        errors: errors.response.data.errors
                    }
                });
        }
    }
}
</script>