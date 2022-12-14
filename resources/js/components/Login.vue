<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Entrar</div>

          <div class="card-body">
            <form method="POST" action="" @submit.prevent="login($event)">
              <input type="hidden" name="_token" :value="csrfToken" />

              <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">
                  Endereço de e-mail
                </label>

                <div class="col-md-6">
                  <input
                    id="email"
                    name="email"
                    v-model="email"
                    type="email"
                    class="form-control"
                    required
                    autocomplete="email"
                    autofocus
                  />
                  <span class="invalid-feedback" role="alert">
                    <strong></strong>
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label
                  for="password"
                  class="col-md-4 col-form-label text-md-end"
                >
                  Senha
                </label>

                <div class="col-md-6">
                  <input
                    id="password"
                    name="password"
                    v-model="password"
                    type="password"
                    class="form-control"
                    required
                    autocomplete="current-password"
                  />
                  <span class="invalid-feedback" role="alert">
                    <strong></strong>
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="remember"
                      id="remember"
                    />

                    <label class="form-check-label" for="remember">
                      Manter conectado
                    </label>
                  </div>
                </div>
              </div>

              <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">Entrar</button>

                  <a class="btn btn-link" href=""> Esqueceu sua senha? </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["csrfToken"],
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    login(e) {
      let url = "http://app.locadora.localhost/api/login";
      let options = {
        method: "post",
        body: new URLSearchParams({
          email: this.email,
          password: this.password,
        }),
      };

      fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
          if (data.access_token) {
            document.cookie = `token=${data.access_token};SameSite=Lax`;
          }
          e.target.submit();
        });
    },
  },
};
</script>
