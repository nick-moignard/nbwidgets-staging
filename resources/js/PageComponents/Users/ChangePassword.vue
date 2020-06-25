<template>
  <div class="card main-card">
    <div class="card-header p-3 h-auto d-block">
      <div class="d-block">Edit User</div>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="bd-example">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="nation_name">User email:</label>
              <input
                type="text"
                class="form-control"
                aria-describedby="email_help"
                placeholder="Enter email"
                v-model="user.email"
                disabled="disabled"
              />
              <small id="email_help" class="form-text text-muted">Will be used for login</small>
            </div>
            <div class="form-group col-md-6">
              <label>Name</label>
              <input type="text" class="form-control" placeholder="Name" v-model="user.name" />
            </div>
            <div class="form-group col-md-6">
              <label>Password</label>
              <input type="password" class="form-control" v-model="user.password" />
            </div>
            <div class="form-group col-md-6">
              <label>Password Confirmation</label>
              <input type="password" class="form-control" v-model="user.password_confirmation" />
            </div>
            <div class="col-md-12 mt-5">
              <div class="text-center">
                <button class="btn btn-primary text-right" @click="createUser">Save User</button>
              </div>
            </div>
          </div>
        </div>
      </div>
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
      user: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      },
      role: 2,
    };
  },
  created() {
    axios
      .get(BASE_URL + "/api/roles")
      .then(response => {
        if (response.status == 200) {
          this.roles = response.data.data;
        }
      })
      .catch(error => swal("Error", error, "error"));

    axios
      .get(BASE_URL + "/api/users/edit/"+this.$attrs.id)
      .then(response => {
        if (response.status == 200) {
          this.user = response.data;
          this.role = response.data.role;
        }
      })
      .catch(error => swal("Error", error, "error"));
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    createUser: function() {
      if (this.user.email) {
        if (this.user.name) {
          if (
            this.user.password_confirmation == this.user.password
          ) {
            axios
              .post(
                BASE_URL + "/api/users/edit/"+this.$attrs.id,
                {
                  user: this.user,
                  role: this.role
                }
              )
              .then(response => {
                if (response.data.status == "ok") {
                  swal("Success", "User Updated", "success");
                  window.location.reload();
                } else {
                  swal("Error", response.data.data, "error");
                }
              })
              .catch(error => swal("Error", response, "error"));
          } else
            swal(
              "Incomplete fields",
              "Verify passwords before continuing",
              "warning"
            );
        } else
          swal(
            "Incomplete fields",
            "Fill in the name field before continuing",
            "warning"
          );
      } else
        swal(
          "Incomplete fields",
          "Fill in the email field before continuing",
          "warning"
        );
    }
  }
};
</script>