<template>
  <div class="card main-card">
    <div class="card-header p-3 h-auto d-block">
      <div class="d-block">Edit Logo</div>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="bd-example">
          <div class="row">
            <div class="form-group col-md-6">
                <label for="exampleCustomFileBrowser" class>File Browser</label>
                <div class="custom-file">
                  <input
                    type="file"
                    id="exampleCustomFileBrowser"
                    name="customFile"
                    class="custom-file-input"
                    @change="updateImage"
                    ref="file"
                  />
                  <label class="custom-file-label" for="exampleCustomFileBrowser">Choose Logo</label>
                </div>
              </div>
             <!-- <div class="col-md-12 mt-5">
              <div class="text-center">
                <button class="btn btn-primary text-right" @click="updateImage">Save Logo</button>
              </div>
            </div> -->
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
      image: '',
    };
  },
  created() {
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    updateImage() {
      this.image = this.$refs.file.files[0];

      const fd = new FormData();
      fd.append("logo", this.image);
      axios
        .post(BASE_URL + "/api/update/logo", fd, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          if (response.status == "200") {
              swal("Success", "New Image Updated Successfully", "success").then(
                () => window.location.reload()
              );
          }
        }).catch(error => {

        });
    },
  }
};
</script>
