<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Submitted Deliverables</p>
                <h3>{{ submittedDeliverablesCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Approved by Program Head</p>
                <h3>{{ submittedDeliverablesPhCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Approved by Dean</p>
                <h3>{{ submittedDeliverablesDeanCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Approved Changes</p>
                <h3>{{ submittedApprovedChangesCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Submitted On-time</p>
                <h3>{{ submittedOnTimeCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Submitted Late</p>
                <h3>{{ submittedLateCount }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <div class="count-and-label">
                <p class="large-label">Did Not Submit</p>
                <h3>{{ didNotSubmitCount }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="date-range-wrapper row gx-2 align-items-center">
      <div class="col">
        <label for="fromDate">From</label>
        <input type="date" id="fromDate" v-model="fromDate" @change="handleDateRangeChange" class="form-control mb-3">
      </div>

      <div class="col">
        <label for="toDate">To</label>
        <input type="date" id="toDate" v-model="toDate" @change="handleDateRangeChange" class="form-control mb-3">
      </div>

      <label class="ml-5 mr-3 mt-4" for="academicYear">Filter By Academic Year/Sem/Term:</label>

      <div class="col">
        <label for="startYear">Start Year</label>
        <input type="text" id="startYear" v-model="startYear" @input="handleDateRangeChange" maxlength="4"
          pattern="[0-9]*" class="form-control mb-3">
      </div>

      <div class="col">
        <label for="endYear">End Year</label>
        <input type="text" id="endYear" v-model="endYear" @input="handleDateRangeChange" maxlength="4" pattern="[0-9]*"
          class="form-control mb-3">
      </div>

      <div class="col">
        <label for="semester">Semester</label>
        <select v-model="selectedSemester" @change="handleDateRangeChange" class="form-control mb-3">
          <option value="1st Sem">1st Sem</option>
          <option value="2nd Sem">2nd Sem</option>
          <option value="Summer">Summer</option>
        </select>
      </div>

      <div class="col">
        <label for="term">Term</label>
        <select v-model="selectedTerm" @change="handleDateRangeChange" class="form-control mb-3">
          <option value="1st Term">1st Term</option>
          <option value="2nd Term">2nd Term</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="bg-white rounded-lg shadow-sm m-1">
          <div class="card-header">
            Uploaded Deliverables
          </div>
          <div v-if="!isDataAvailable" class="alert alert-warning">
            No submitted deliverables at this date.
          </div>
          <div class="card-body">
            <canvas ref="pieChart" width="250" height="250" @click="handlePieChartClick"></canvas>
          </div>
        </div>
      </div>

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" :class="{ 'show': isModalProgramOpen, 'd-block': isModalProgramOpen }">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title bold-title">{{ clickedProgram }}: {{ clickedTotalCounts }} Submissions</h5>
            </div>
            <div class="modal-body">
              <table class="table table-striped" ref="modalProgramTableNotPaginated" style="display: none">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Faculty Name</th>
                    <th>Deliverable Type</th>
                    <th>Subject</th>
                    <th>Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in modalProgramTableData" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.user_name }}</td>
                    <td>{{ item.deliverable_type }}</td>
                    <td>{{ item.subject }}</td>
                    <td>{{ item.subject_code }}</td>
                  </tr>
                </tbody>
              </table>
              <table class="table table-striped" ref="modalTable">
                <thead>
                  <tr>
                    <th style="width: 17%">Faculty Name</th>
                    <th style="width: 18%">Deliverable Type</th>
                    <th style="width: 20%">Subject</th>
                    <th style="width: 20%">Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in paginatedStackedModalTableData" :key="item.id">
                    <td>{{ item.user_name }}</td>
                    <td>{{ item.deliverable_type }}</td>
                    <td>{{ item.subject }}</td>
                    <td>{{ item.subject_code }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="pagination pagination-sm m-0 float-right">
                <ul class="pagination">
                  <li class="page-item" :class="{ 'disabled': modalStackedCurrentPage === 1 }">
                    <a class="page-link" @click.prevent="setStackedModalPage(1)">
                      «
                    </a>
                  </li>

                  <li class="page-item" :class="{ 'disabled': modalStackedCurrentPage === modalStackedTotalPages }">
                    <a class="page-link" @click.prevent="setStackedModalPage(modalStackedCurrentPage + 1)">
                      ›
                    </a>
                  </li>
                  <li class="page-item" :class="{ 'disabled': modalStackedCurrentPage === modalStackedTotalPages }">
                    <a class="page-link" @click.prevent="setStackedModalPage(modalStackedTotalPages)">
                      »
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"
                @click="isModalProgramOpen = false">Close</button>
              <button type="button" class="btn btn-primary" @click="generateStackedPDF">Generate Reports</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" :class="{ 'show': isModalOpen, 'd-block': isModalOpen }">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title bold-title"> {{ clickedDeliverableType }} </h5>
              <div class="form-group mb-0">
                <label for="programFilter" class="mr-5">Program Filter:</label>
                <select class="form-control" id="programFilter" v-model="selectedProgram">
                  <option value="">All Programs</option>
                  <option value="BSIT">BSIT</option>
                  <option value="BSCS">BSCS</option>
                  <option value="BSIS">BSIS</option>
                  <option value="BSEMC">BSEMC</option>
                  <option value="BLIS">BLIS</option>
                  <option value="BMMA">BMMA</option>
                  <option v-for="program in uniquePrograms" :key="program" :value="program">{{ program }}</option>
                </select>
              </div>
            </div>
            <div class="modal-body">
              <table class="table table-striped" ref="modalTableFiltered" style="display: none">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Faculty Name</th>
                    <th>Subject</th>
                    <th>Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in filteredModalTableData" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.user_name }}</td>
                    <td>{{ item.subject }}</td>
                    <td>{{ item.subject_code }}</td>
                  </tr>
                </tbody>
              </table>
              <table class="table table-striped" ref="modalTable">
                <thead>
                  <tr>
                    <th>Faculty Name</th>
                    <th>Program</th>
                    <th>Subject</th>
                    <th>Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in paginatedModalTableData" :key="item.id">
                    <td>{{ item.user_name }}</td>
                    <td>{{ item.program }}</td>
                    <td>{{ item.subject }}</td>
                    <td>{{ item.subject_code }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="pagination pagination-sm m-0 float-right">
                <ul class="pagination">
                  <li class="page-item" :class="{ 'disabled': modalCurrentPage === 1 }">
                    <a class="page-link" @click.prevent="setModalPage(1)">
                      «
                    </a>
                  </li>
                  <li class="page-item" :class="{ 'disabled': modalCurrentPage === modalTotalPages }">
                    <a class="page-link" @click.prevent="setModalPage(modalCurrentPage + 1)">
                      ›
                    </a>
                  </li>
                  <li class="page-item" :class="{ 'disabled': modalCurrentPage === modalTotalPages }">
                    <a class="page-link" @click.prevent="setModalPage(modalTotalPages)">
                      »
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"
                @click="isModalOpen = false">Close</button>
              <button type="button" class="btn btn-primary" @click="generatePDF">Generate Reports</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="bg-white rounded-lg shadow-sm m-1">
          <div class="card-header">
            Submission by Programs for Deliverable Type
          </div>
          <div v-if="!isDataAvailable" class="alert alert-warning">
            No submitted deliverables at this date.
          </div>
          <div class="card-body">
            <canvas ref="stackedBarChart" width="250" height="250" @click="handleStackedBarChartClick"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 mx-auto">
        <div class="bg-white rounded-lg shadow-sm mt-3 m-1">
          <div class="card-header">
            Compare Submission Performance By Program
          </div>
          <div v-if="!isDataAvailable" class="alert alert-warning">
            No submitted deliverables at this date.
          </div>
          <div class="card-body">
            <canvas ref="radarChart" width="250" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import Chart from 'chart.js/auto';
import axios from 'axios';

export default {
  data() {
    return {
      tosCount: 0,
      psgCount: 0,
      flCount: 0,
      apCount: 0,
      cpCount: 0,
      epCount: 0,
      egCount: 0,
      simCount: 0,
      syllabusCount: 0,
      pieChart: null,
      barChart: null,
      radarChart: null,
      selectedDate: new Date().toISOString().slice(0, 10),
      isDataAvailable: true,
      selectedDeliverableType: '',
      deliverableTypes: [],
      stackedBarChart: null,
      submissionData: {},
      fromDate: '',
      toDate: '',
      submittedDeliverablesCount: 0,
      submittedDeliverablesPhCount: 0,
      submittedDeliverablesDeanCount: 0,
      submittedOnTimeCount: 0,
      submittedLateCount: 0,
      didNotSubmitCount: 0,
      selectedProgram: '',
      uniquePrograms: [],
      startYear: '',
      endYear: '',
      selectedSemester: '',
      selectedTerm: '',
      selectedAcademicYear: "",
      academicYears: [],
      submittedApprovedChangesCount: 0,
      pieChartPercentages: [],
      isModalOpen: false,
      isModalProgramOpen: false,
      clickedProgram: '',
      clickedTotalCounts: '',
      clickedDeliverableType: '',
      clickedDeliverableTypeStacked: '',
      clickedCount: '',
      modalTableData: [],
      modalProgramTableData: [],
      modalCurrentPage: 1,
      modalStackedCurrentPage: 1,
      modalPerPage: 5,
      modalStackedPerPage: 5,
    };
  },
  computed: {
    filteredModalTableData() {
      if (!this.selectedProgram) {
        return this.modalTableData;
      } else {
        return this.modalTableData.filter(item => item.program === this.selectedProgram);
      }
    },
    modalTotalPages() {
      return Math.ceil(this.modalTableData.length / this.modalPerPage);
    },
    modalStackedTotalPages() {
      return Math.ceil(this.modalProgramTableData.length / this.modalStackedPerPage);
    },
    paginatedStackedModalTableData() {
      const startIndex = (this.modalStackedCurrentPage - 1) * this.modalStackedPerPage;
      const endIndex = startIndex + this.modalStackedPerPage;
      return this.modalProgramTableData.slice(startIndex, endIndex);
    },
    paginatedModalTableData() {
      const startIndex = (this.modalCurrentPage - 1) * this.modalPerPage;
      const endIndex = startIndex + this.modalPerPage;

      const filteredData = this.selectedProgram
        ? this.modalTableData.filter(item => item.program === this.selectedProgram)
        : this.modalTableData;

      return filteredData.slice(startIndex, endIndex);
    },
  },
  methods: {
    setModalPage(pageNumber) {
      this.modalCurrentPage = pageNumber;
    },
    setStackedModalPage(pageNumber) {
      this.modalStackedCurrentPage = pageNumber;
    },
    handleDateRangeChange() {
      const academicYear = this.selectedSemester === 'Summer'
        ? `Summer ${this.endYear}`
        : `A.Y.${this.startYear}-${this.endYear}/${this.selectedSemester}/${this.selectedTerm}`;
      this.selectedAcademicYear = academicYear;
      this.fetchData();
      this.fetchStackedBarChartData();
      this.fetchRadarChartData();
      this.fetchDeliverablesCount();
      this.fetchApprovedPH();
      this.fetchApprovedDean();
      this.fetchApprovedChanges();
      this.fetchOnTimeCount();
      this.fetchLateSubmissionCount();
      this.fetchDidNotSubmitCount();
    },
    updatePieChart(labels, counts) {
      this.$nextTick(() => {
        const pieCtx = this.$refs.pieChart?.getContext('2d');

        if (!pieCtx) {
          console.error('Pie chart canvas not found or not rendered yet.');
          return;
        }
        if (this.pieChart) {
          this.pieChart.destroy();
        }
        var pieChartData = {
          labels: labels,
          datasets: [
            {
              data: counts,
              backgroundColor: [
                '#FEC8D8', // Pastel Rose
                '#95E1D3', // Pastel Blue
                '#B5CDFC', // Pastel Purple
                '#FFD6A5', // Pastel Orange
                '#C3E6CB', // Pastel Mint
                '#A0C1B8', // Pastel Green
                '#FDFFB6', // Pastel Yellow
                '#FFADAD', // Pastel Red
                '#D4E6B5', // Pastel Pink
              ],
            },
          ],
        };
        var segments = labels.map((label, index) => `${label}: ${counts[index]} (${this.pieChartPercentages[index]}%)`);
        pieChartData.labels = segments;

        const pieChartOptions = {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: function (context) {
                  return context.label;
                },
                afterLabel: function (context) {
                  return 'Click me!';
                },
              },
            },
          },
        };

        this.pieChart = new Chart(pieCtx, {
          type: 'pie',
          data: pieChartData,
          options: pieChartOptions,
        });
      });
    },
    createStackedBarChart(stackedBarChartData) {
      this.$nextTick(() => {
        const stackedBarCtx = this.$refs.stackedBarChart?.getContext('2d');

        if (!stackedBarCtx) {
          console.error('Stacked bar chart canvas not found or not rendered yet.');
          return;
        }

        if (this.stackedBarChart) {
          this.stackedBarChart.destroy();
        }
        var stackedBarChartOptions = {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true,
            },
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function (context) {
                  return context.dataset.label + ': ' + context.parsed.y;
                },
                afterLabel: function (context) {
                  return 'Click me!';
                },
              },
            },
          },
        };
        this.stackedBarChart = new Chart(stackedBarCtx, {
          type: 'bar',
          data: {
            labels: stackedBarChartData.labels,
            datasets: stackedBarChartData.datasets,
          },
          options: stackedBarChartOptions,
        });
      });
    },
    createRadarChart(radarChartData) {
      this.$nextTick(() => {
        const radarCtx = this.$refs.radarChart?.getContext('2d');

        if (!radarCtx) {
          console.error('Radar chart canvas not found or not rendered yet.');
          return;
        }
        if (this.radarChart) {
          this.radarChart.destroy();
        }
        this.radarChart = new Chart(radarCtx, {
          type: 'radar',
          data: {
            labels: radarChartData.labels,
            datasets: radarChartData.datasets
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scale: {
              ticks: {
                beginAtZero: true
              }
            }
          }
        });
      });
    },
    fetchStackedBarChartData() {
      axios
        .get('http://127.0.0.1:8000/api/get-stacked-bar-data', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;

          console.log('fetch stacked bar data:', data);

          const labels = data.labels;
          const datasets = data.datasets;

          console.log('fetch labels:', labels);
          console.log('fetch datasets:', datasets);

          const stackedBarChartData = {
            labels: labels,
            datasets: datasets
          };

          this.isDataAvailable = datasets.some(dataset => dataset.data.some(count => count > 0));

          this.createStackedBarChart(stackedBarChartData);
        })
        .catch(error => {
          console.error('An error occurred while fetching stacked bar chart data:', error);
        });
    },
    fetchData() {
      axios
        .get('http://127.0.0.1:8000/api/get-deliverable-counts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          const labels = Object.keys(data);
          const counts = Object.values(data);

          this.isDataAvailable = counts.some(count => count > 0);
          const total = counts.reduce((acc, count) => acc + count, 0);
          this.pieChartPercentages = counts.map(count => ((count / total) * 100).toFixed(2));

          this.updatePieChart(labels, counts);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchDeliverablesCount() {
      axios
        .get('http://127.0.0.1:8000/api/get-uploaded-counts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedDeliverablesCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },

    fetchApprovedPH() {
      axios
        .get('http://127.0.0.1:8000/api/get-deliverable-phcounts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedDeliverablesPhCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchApprovedDean() {
      axios
        .get('http://127.0.0.1:8000/api/get-deliverable-deancounts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedDeliverablesDeanCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchApprovedChanges() {
      axios
        .get('http://127.0.0.1:8000/api/get-deliverable-approveChanges', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedApprovedChangesCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchOnTimeCount() {
      axios
        .get('http://127.0.0.1:8000/api/get-on-time-counts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedOnTimeCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchLateSubmissionCount() {
      axios
        .get('http://127.0.0.1:8000/api/get-late-submission-counts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.submittedLateCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchDidNotSubmitCount() {
      axios
        .get('http://127.0.0.1:8000/api/get-did-not-submit-counts', {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.didNotSubmitCount = data.reduce((total, item) => total + item.count, 0);
        })
        .catch(error => {
          console.error('An error occurred while fetching data:', error);
        });
    },
    fetchRadarChartData() {
      axios.get('http://127.0.0.1:8000/api/get-radar-program-counts', {
        params: {
          fromDate: this.fromDate,
          toDate: this.toDate,
          academicYear: this.selectedAcademicYear,
        }
      })
        .then(response => {
          const data = response.data;

          const labels = data.labels;
          const datasets = data.datasets;

          console.log('fetch labels:', labels);
          console.log('fetch datasets:', datasets);

          const radarChartData = {
            labels: labels,
            datasets: datasets
          };

          this.isDataAvailable = datasets.some(dataset => dataset.data.some(count => count > 0));

          console.log('radarChartData:', radarChartData);

          this.createRadarChart(radarChartData);
        })
        .catch(error => {
          console.error('Error fetching radar chart data:', error);
        });
    },
    fetchCurrentUserName() {
      axios.get('http://127.0.0.1:8000/api/get-current-username', {
        params: {
          userID: this.$route.params.id,
        }
      })
        .then(response => {
          const data = response.data;

          this.currentUserName = data.name;

          console.log('Current User name:', data);

        })
        .catch(error => {
          console.error('Error fetching current username data:', error);
        });
    },
    handlePieChartClick(event) {
      const activePoints = this.pieChart.getElementsAtEventForMode(event, 'point', this.pieChart.options);
      if (activePoints.length > 0) {
        const clickedLabel = this.pieChart.data.labels[activePoints[0].index];

        this.fetchDataForDeliverableType(clickedLabel);
      }
    },
    handleStackedBarChartClick(event) {
      const stackedBarChart = this.stackedBarChart;
      if (!stackedBarChart) {
        console.error('Stacked bar chart is not initialized.');
        return;
      }

      const activeElements = stackedBarChart.getElementsAtEventForMode(event, 'nearest', { intersect: true });

      if (activeElements.length > 0) {

        const datasetIndex = activeElements[0].datasetIndex;

        const stackIndex = activeElements[0].index;

        const program = this.stackedBarChart.data.labels[stackIndex];
        const deliverableType = this.stackedBarChart.data.datasets[datasetIndex].label;
        const count = this.stackedBarChart.data.datasets[datasetIndex].data[stackIndex];

        const totalSubmissionCountPerProgram = this.calculateTotalSubmissionCount(program);

        this.fetchDataForStackedBarChart(program, deliverableType, count, totalSubmissionCountPerProgram);
      }
    },
    calculateTotalSubmissionCount(program) {
      const stackedBarChart = this.stackedBarChart;
      if (!stackedBarChart) {
        console.error('Stacked bar chart is not initialized.');
        return 0;
      }

      const programIndex = stackedBarChart.data.labels.indexOf(program);
      if (programIndex === -1) {
        console.error('Program not found in the chart data.');
        return 0;
      }

      let totalSubmissionCount = 0;

      stackedBarChart.data.datasets.forEach((dataset) => {
        totalSubmissionCount += dataset.data[programIndex];
      });

      return totalSubmissionCount;
    },
    openModal() {
      this.isModalOpen = true;
    },
    openProgramModal() {
      this.isModalProgramOpen = true;
    },
    generateReports() {
      if (!this.clickedDeliverableType) {
        console.error('No deliverable type selected for report generation.');
        return;
      }

      this.generatePDF();

      this.isModalOpen = false;
    },

    fetchDataForDeliverableType(clickedLabel) {

      const deliverableType = clickedLabel.split(':')[0].trim();

      const fromDate = this.fromDate || '';
      const toDate = this.toDate || '';

      axios
        .get(`http://127.0.0.1:8000/api/get-pieChartModal-data/${deliverableType}`, {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.modalTableData = data;

          this.clickedDeliverableType = clickedLabel;
          this.openModal();

          this.uniquePrograms = Array.from(new Set(this.modalTableData.map(item => item.program)));
        })
        .catch(error => {
          console.error('Error fetching data for deliverable type:', error);
        });
    },
    fetchDataForStackedBarChart(program, deliverableType, count, totalSubmissionCountPerProgram) {
      axios
        .get(`http://127.0.0.1:8000/api/get-stackedBarModal-data/${program}`, {
          params: {
            fromDate: this.fromDate,
            toDate: this.toDate,
            academicYear: this.selectedAcademicYear,
          }
        })
        .then(response => {
          const data = response.data;
          this.modalProgramTableData = data;

          console.log('Modal Data:', this.modalProgramTableData);

          this.clickedDeliverableTypeStacked = deliverableType;
          this.clickedProgram = program;
          this.clickedCount = count;
          this.clickedTotalCounts = totalSubmissionCountPerProgram;
          this.openProgramModal();
        })
        .catch(error => {
          console.error('Error fetching data for Stacked Bar Chart:', error);
        });
    },
    generatePDF() {
      if (!this.clickedDeliverableType) {
        console.error('No deliverable type selected for report generation.');
        return;
      }

      const doc = new jsPDF();

      const logoPath = '/images/um_header.png';
      doc.addImage(logoPath, 'PNG', 23, 3, 185, 30);

      // const umlogoPath = '/images/umlogo.png';
      // doc.addImage(umlogoPath, 'PNG', 9, 5.5, 20, 20);

      const [deliverableType, otherInfo] = this.clickedDeliverableType.split(': ');

      const introduction = 'Faculty Deliverable Submission Summary Report';

      doc.setFontSize(12);
      let mainText = '';
      if (this.selectedAcademicYear) {
        mainText = `Academic Year/Sem/Term: ${this.selectedAcademicYear}`;
      } else if (this.fromDate && this.toDate) {
        mainText = `Date Range: ${this.fromDate} - ${this.toDate}`;
      } else {
        mainText = 'Academic Year/Sem/Term: All Data';
      }

      const programText = this.selectedProgram
        ? `Program: ${this.selectedProgram}`
        : 'Program: All Programs';

      const deliverableTypeText = `Deliverable Type: ${deliverableType}`;

      doc.text(mainText, 23, 50);
      doc.text(programText, 23, 56);
      doc.text(deliverableTypeText, 23, 62);

      this.fetchCurrentUserName();

      setTimeout(() => {
        const generatedByText = `Generated by: ${this.currentUserName}`;
        doc.text(generatedByText, 23, 68);

        const generatedDate = new Date().toLocaleString();
        const generatedDateText = `Generated on: ${generatedDate}`;
        doc.text(generatedDateText, 23, 74);

        const tableData = this.getAllTableData(this.$refs.modalTableFiltered);

        if (tableData.length > 0) {
          doc.setFontSize(16);
          doc.text(introduction, 23, 40);

          const totalDeliverables = tableData.length - 1;

          const percentage = otherInfo.match(/\(([^)]+)\)/)[1];

          const text = `Total number of deliverables submitted: ${totalDeliverables} (${percentage})`;

          const startY = 90;

          const headStyles = {
            fillColor: [207, 174, 70],
            textColor: [0, 0, 0],
            fontStyle: 'bold',
          };

          doc.autoTable({
            head: [tableData[0]],
            body: tableData.slice(1),
            startY,
            headStyles,
          });

          doc.setFontSize(12);
          doc.text(text, 10, startY + doc.autoTable.previous.finalY + 10);

          doc.save(`${deliverableType}_report.pdf`);
        }
      }, 500);
    },

    generateStackedPDF() {
      if (!this.clickedDeliverableTypeStacked || !this.clickedProgram) {
        console.error('No deliverable type or program selected for report generation.');
        return;
      }

      const doc = new jsPDF();

      const logoPath = '/images/ccelogo.png';
      doc.addImage(logoPath, 'PNG', 23, -2, 35, 35);

      const umlogoPath = '/images/umlogo.png';
      doc.addImage(umlogoPath, 'PNG', 9, 5.5, 20, 20);

      const introduction = 'Faculty Submission Performance Summary Report';

      doc.setFontSize(12);
      let mainText = '';
      if (this.selectedAcademicYear) {
        mainText = `Academic Year/Sem/Term: ${this.selectedAcademicYear}`;
      } else if (this.fromDate && this.toDate) {
        mainText = `Date Range: ${this.fromDate} - ${this.toDate}`;
      } else {
        mainText = 'Academic Year/Sem/Term: All Data';
      }

      const totalSubmissionCount = `Total Submission Count: ${this.clickedTotalCounts}`;

      const programText = this.clickedProgram
        ? `Program: ${this.clickedProgram}`
        : 'Program: All Programs';

      doc.text(mainText, 10, 45);
      doc.text(programText, 10, 51);

      this.fetchCurrentUserName();

      setTimeout(() => {
        const generatedByText = `Generated by: ${this.currentUserName}`;
        doc.text(generatedByText, 10, 57);

        const generatedDate = new Date().toLocaleString();
        const generatedDateText = `Generated on: ${generatedDate}`;
        doc.text(generatedDateText, 10, 63);

        const stackedTableData = this.getStackedTableData(this.$refs.modalProgramTableNotPaginated);

        if (stackedTableData.length > 0) {
          doc.setFontSize(16);
          doc.text(introduction, 10, 35);
          const tableStartY = 73;
          const textSpacing = 5;

          const headStyles = {
            fillColor: [207, 174, 70],
            textColor: [0, 0, 0],
            fontStyle: 'bold',
          };

          doc.autoTable({
            head: [stackedTableData[0]],
            body: stackedTableData.slice(1),
            startY: tableStartY,
            headStyles,
          });

          doc.setFontSize(12);
          doc.text(totalSubmissionCount, 10, tableStartY + doc.autoTable.previous.finalY + textSpacing * 2);

          doc.save(`${this.clickedProgram}_report.pdf`);
        }
      }, 500);
    },

    getStackedTableData(table) {
      const tableData = [];
      const tableRows = table.querySelectorAll('tr');

      tableRows.forEach((row) => {
        const rowData = [];
        const cells = row.querySelectorAll('td, th');

        cells.forEach((cell) => {
          rowData.push(cell.innerText);
        });

        tableData.push(rowData);
      });

      return tableData;
    },
    getAllTableData(table) {
      const tableData = [];
      const tableRows = table.querySelectorAll('tr');

      tableRows.forEach((row) => {
        const rowData = [];
        const cells = row.querySelectorAll('td, th');

        cells.forEach((cell) => {
          rowData.push(cell.innerText);
        });

        tableData.push(rowData);
      });

      return tableData;
    },
  },
  mounted() {
    this.fetchData();
    this.fetchRadarChartData();
    this.fetchStackedBarChartData();
    this.fetchApprovedPH();
    this.fetchApprovedDean();
    this.fetchApprovedChanges();
    this.fetchOnTimeCount();
    this.fetchDeliverablesCount();
    this.fetchLateSubmissionCount();
    this.fetchDidNotSubmitCount();
    this.fetchCurrentUserName();
  },
};
</script>

<style scoped>
.count-and-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.large-label {
  font-size: 18px;

}

.bold-title {
  font-weight: bold;
}
</style>
