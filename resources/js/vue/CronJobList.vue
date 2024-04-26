<template>
    <div>
      <h2>Add Cron Job</h2>
      <form @submit.prevent="addCronJob">
        <label for="name">Name:</label>
        <input type="text" id="name" placeholder="name" v-model="newCronJob.name" required>
        <label for="url">Url:</label>
        <input type="text" id="url" placeholder="http://test.com" v-model="newCronJob.url" required>
        <label for="interval">Interval:</label>
        <input type="text" id="interval" placeholder="in minutes" v-model="newCronJob.interval" required>
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
      <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th style="padding: 10px; background-color: #f2f2f2;">Name</th>
          <th style="padding: 10px; background-color: #f2f2f2;">Url</th>
          <th style="padding: 10px; background-color: #f2f2f2;">Interval</th>
          <th style="padding: 10px; background-color: #f2f2f2;">Action</th>
        </tr>
      </thead>
        <tbody>
          <tr v-for="cronJob in cronJobs" :key="cronJob.id">
            <td>{{ cronJob.name }}</td>
            <td>{{ cronJob.url }}</td>
            <td>{{ cronJob.interval }}</td>
            <td>
              <button class="btn btn-danger" @click="deleteCronJob(cronJob.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      cronJobs: [],
      newCronJob: {
      name: '',
      url: '',
      interval: '',
    }
    };
  },
  created() {
    this.fetchCronJobs();
  },
  methods: {
    async fetchCronJobs() {
      try {
        const response = await axios.get('/api/cron-jobs');
        this.cronJobs = response.data;
      } catch (error) {
        console.error('Error fetching cron jobs:', error);
      }
    },
    async deleteCronJob(id) {
      // Confirmation before deletion (optional)
      if (confirm(`Are you sure you want to delete cron job "${id}"?`)) {
        try {
          const response = await axios.delete(`/api/cron-jobs/${id}`); // Assuming DELETE endpoint
          // Update cronJobs data if successful (optional)
          this.cronJobs = this.cronJobs.filter(job => job.id !== id);
          console.log(`Cron job with ID ${id} deleted successfully.`);
        } catch (error) {
          console.error('Error deleting cron job:', error);
        }
      }
    },
    async addCronJob() {
    try {
      const response = await axios.post('/api/cron-jobs', this.newCronJob);
      this.cronJobs.push(response.data); // Assuming response contains the created cron job
      this.newCronJob = { name: '', url: '', interval: '' }; // Clear the form
      console.log('Cron job added successfully.');
    } catch (error) {
      console.error('Error adding cron job:', error);
    }
  }
  }
};
</script>
