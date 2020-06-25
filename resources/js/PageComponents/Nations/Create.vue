<template>
  <div class="card main-card">
    <div class="card-header p-3 h-auto d-block">
      <div class="d-block">Add new Nation</div>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="bd-example">
          <div>
            <div class="form-group">
              <label for="nation_name">Nation Name:</label>
              <input
                type="text"
                class="form-control"
                id="nation_name"
                aria-describedby="nation_help"
                placeholder="Enter name"
                v-model="nation.name"
              />
              <small id="nation_help" class="form-text text-muted">Choose a name for the nation</small>
            </div>
            <div class="form-group">
              <label for="nation_slug">Nation Slug</label>
              <input
                type="text"
                class="form-control"
                id="nation_slug"
                placeholder="Slug"
                v-model="nation.slug"
              />
            </div>
            <div class="form-group mb-5">
              <label for="nation_token">API Token</label>
              <input
                type="text"
                class="form-control"
                v-model="nation.access_token"
                id="nation_token"
                placeholder
                disabled
              />
            </div>
            <div class="text-right">
              <button class="btn btn-success" @click="show">Get API Token</button>
              <button class="btn btn-primary text-right" @click="createNation">Connect Nation</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showModal">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Get Access Token</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="showModal = false">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="client_id">Client ID:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="client_id"
                      placeholder="Client ID"
                      v-model="client.id"
                    />
                  </div>
                  <div class="form-group">
                    <label for="client_secret">Client Secret</label>
                    <input
                      type="textx"
                      class="form-control"
                      id="client_secret"
                      placeholder="Client Secret"
                      v-model="client.secret"
                    />
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
                  <button type="button" class="btn btn-primary" @click="getCode">Continue</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
    <div v-if="showModalLoading">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Awaiting authorization</h5>
                </div>
                <div class="modal-body">
                  <div class="spinner-grow text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-success" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-warning" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-info" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-light" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="reload">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import VueNotifications from "vue-notifications";
import swal from "sweetalert";

export default {
  data() {
    return {
      nation: {
        name: "",
        slug: "",
        access_token: ""
      },
      client: {
        id: "",
        secret: ""
      },
      code: "",
      showModal: false,
      showModalLoading: false
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    createNation: function() {
      if (this.nation.name) {
        if (this.nation.slug) {
          if (this.nation.access_token) {
            axios
              .post(BASE_URL + "/api/nations", {
                nation: this.nation,
                user_id: this.currentUser.user.id
              })
              .then(response => {
                if (response.status == 200) {
                  swal("Success", "Nation added", "success");
                  window.location.reload();
                }
              })
              .catch(error => swal("Error", error, "error"));
          } else
            swal(
              "Incomplete fields",
              "You first must generate the API Token",
              "warning"
            );
        } else
          swal(
            "Incomplete fields",
            "Fill in the slug field before continuing",
            "warning"
          );
      } else
        swal(
          "Incomplete fields",
          "Fill in the name field before continuing",
          "warning"
        );
    },
    reload() {
      window.location.reload();
    },
    getAccessToken: function() {
      axios
        .post(BASE_URL + "/api/nation/generate/token", {
          client: this.client,
          nation: this.nation,
          code: this.code
        })
        .then(response => {
          if (response.status == 200) {
            this.nation.access_token = response.data.data;
          }
        })
        .catch(error => swal("Error", error, "error"));
    },
    getCode: function() {
      if (this.client.id) {
        if (this.client.secret) {
          this.showModal = false;
          this.showModalLoading = true;
          var popup = window.open(
            `https://${this.nation.slug}.nationbuilder.com/oauth/authorize?response_type=code&client_id=${this.client.id}&redirect_uri=${BASE_URL}/nbcallback`,
            "Copy Url",
            "menubar=0,resizable=0,width=850,height=850"
          );
          var me = this;
          var timer = setInterval(function() {
            var url = new URL(popup.location.href);
            var code = url.searchParams.get("code");
            if (code != undefined && code != null && code != "") {
              me.showModalLoading = false;
              me.code = code;
              me.getAccessToken();
              clearInterval(timer);
              popup.close();
            }
          }, 2000);
        } else
          swal(
            "Incomplete fields",
            "Fill in the Client Secret field before continuing",
            "warning"
          );
      } else
        swal(
          "Incomplete fields",
          "Fill in the Client ID field before continuing",
          "warning"
        );
    },
    show: function() {
      if (this.nation.name != "" && this.nation.slug != "")
        this.showModal = true;
      else
        swal(
          "Incomplete Fields",
          "Fill in the name and slug before continuing",
          "warning"
        );
    }
  }
};
</script>