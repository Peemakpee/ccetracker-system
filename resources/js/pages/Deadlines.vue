<template>
  <section class="content">
    <div class="container-fluid">
      <div class="card">

        <div class="card-header custom-docheader">
          <h3 class="card-title custom-title">Set Deadlines</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="saveData">
            <div class="form-group">
              <label for="deliverable_type">Deliverable Type</label>
              <select id="deliverable_type" class="form-control" v-model="newData.deliverable_type" required>
                <option v-for="templateType in templates" :key="templateType" :value="templateType">{{ templateType
                }}</option>
              </select>
            </div>
            <div v-if="$route.params.program === 'DEAN' || $route.params.program === 'AA'">
              <div class="form-group">
                <label for="program">Program</label>
                <select id="program" class="form-control" v-model="newData.program" required>
                  <option value="BSIT">BSIT</option>
                  <option value="BSCS">BSCS</option>
                  <option value="BSIS">BSIS</option>
                  <option value="BSEMC">BSEMC</option>
                  <option value="BLIS">BLIS</option>
                  <option value="BMMA">BMMA</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="deadline">Deadline Date</label>
              <input type="date" id="deadline" class="form-control" v-model="newData.deadline" required />
            </div>
            <div class="row">
              <div class="col-md-4"></div> 
              <div class="col-md-8">
                <div class="text-right"> 
                  <button type="submit" class="btn custom-button" :disabled="isLoading">
                    <span v-if="isLoading">
                      <i class="fas fa-spinner fa-spin"></i> Saving...
                    </span>
                    <span v-else>Save Data</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <div class="success-banner" v-if="successBannerVisible">
    {{ successMessage }}
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      newData: {
        deliverable_type: '',
        program: '',
        deadline: '',
      },
      isLoading: false,
      templates: [],
      successBannerVisible: false,
      successMessage: '',
    };
  },
  created() {
    this.newData.program = this.$route.params.program;
    this.fetchTemplates();
  },
  methods: {
    showSuccessBanner(message) {
      this.successMessage = message;
      this.successBannerVisible = true;

      setTimeout(() => {
        this.successBannerVisible = false;
        this.successMessage = '';
      }, 1500); 
    },
    fetchTemplates() {
      axios.get('http://127.0.0.1:8000/api/get-templatesOptions')
        .then(response => {
          console.log('API Response:', response.data);
          this.templates = response.data;
          console.log('Templates array:', this.templates);
        })
        .catch(error => {
          console.error('Error fetching templates:', error);
        });
    },
    saveData() {

      const apiUrl = 'http://127.0.0.1:8000/api/set-deadlines';

      if (!this.newData.program) {
        this.newData.program = this.$route.params.program;
      }

      this.isLoading = true;

      axios.post(apiUrl, this.newData)
        .then(response => {
          console.log('Data saved:', response.data);

          this.newData = {
            deliverable_type: '',
            program: '',
            deadline: '',
          };
          this.showSuccessBanner('Deadline has been successfully set');
        })
        .catch(error => {
          console.error('Error saving data:', error);

        })

        .finally(() => {
          this.isLoading = false;
        });
    }
  }
}
</script>

<style scoped>
.custom-docheader {
  background-color: #CFAE46 !important;

}

.custom-button {
  background-color: #CFAE46 !important;

  height: 40px;
  font-weight: 700;
  font-size: medium;
}

.success-banner {
  background-color: #4CAF50;
  color: white;
  text-align: center;
  padding: 20px 40px;
  position: fixed;
  top: 20px;

  right: 20px;

  font-size: 18px;
  font-weight: bold;
  z-index: 9999;
  border-radius: 8px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
  transform: none;

}

.custom-title {
  color: #000000;

  font-size: 20px;
  font-weight: bold;

}
</style>

