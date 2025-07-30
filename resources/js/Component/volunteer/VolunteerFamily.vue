<template>
  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Volunteer Family Details</h2>

    <!-- Table View -->
    <div v-if="family">
      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2 text-left">Field</th>
            <th class="border px-4 py-2 text-left">Value</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(value, key) in tableFields" :key="key">
            <td class="border px-4 py-2 font-medium">{{ formatLabel(key) }}</td>
            <td class="border px-4 py-2">{{ family[key] }}</td>
          </tr>
        </tbody>
      </table>
      <button @click="openEditModal" class="mt-4 cursor-pointer bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Edit</button>
    </div>

    <!-- Form View -->
    <div v-else>
      <form @submit.prevent="submitForm" class="grid grid-cols-1 gap-4">
        <input v-model="form.head_of_family" placeholder="Head of Family" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model="form.mobile_number" placeholder="Mobile Number" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model="form.village" placeholder="Village" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model="form.taluka" placeholder="Taluka" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model="form.district" placeholder="District" required class="w-full p-2 border border-gray-300 rounded" />
        <textarea v-model="form.address" placeholder="Address" required class="w-full p-2 border border-gray-300 rounded"></textarea>
        <input v-model="form.sub_caste" type="number" placeholder="Sub-caste" class="w-full p-2 border border-gray-300 rounded" />
        <select v-model="form.ration_card" required class="w-full p-2 border border-gray-300 rounded">
          <option disabled value="">Select Ration Card Option</option>
          <option value="APL">APL</option>
          <option value="BPL">BPL</option>
        </select>
        <input v-model.number="form.number_of_family_members" type="number" placeholder="No. of Family Members" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model.number="form.ward_no" type="number" placeholder="Ward Number" required class="w-full p-2 border border-gray-300 rounded" />
        <input v-model="form.vidhan_sabha" placeholder="Vidhan Sabha" required class="w-full p-2 border border-gray-300 rounded" />

        <button type="submit" class="bg-blue-600 cursor-pointer text-white py-2 px-4 rounded hover:bg-blue-700 transition">Submit</button>
      </form>
    </div>

    <!-- Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30">
      <div class="bg-white p-6 w-[90%] rounded-xl max-w-2xl shadow-xl relative overflow-y-auto max-h-[90vh]">
        <button
      @click="showModal = false"
      class="absolute top-3 right-4 cursor-pointer text-red-500 text-2xl hover:text-red-700"
    >
      ✖
    </button>
        <h3 class="text-xl font-semibold mb-4">Edit Family Details</h3>
        <form @submit.prevent="updateForm" class="grid grid-cols-1 gap-4">
          <label for="Head">Head of Family :</label>
          <input v-model="form.head_of_family" name="Head" placeholder="Head of Family" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="mobile">Mobile No. :</label>
          <input v-model="form.mobile_number" name="mobile" placeholder="Mobile Number" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="village">Village :</label>
          <input v-model="form.village" name="village" placeholder="Village" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="taluka">Taluka :</label>
          <input v-model="form.taluka" name="taluka" placeholder="Taluka" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="district">District :</label>
          <input v-model="form.district" name="district" placeholder="District" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="address">Address :</label>
          <textarea v-model="form.address" name="address" placeholder="Address" required class="w-full p-2 border border-gray-300 rounded"></textarea>
          <label for="caste">Sub-Caste :</label>
          <input v-model="form.sub_caste" name="caste" type="number" placeholder="Sub-caste" class="w-full p-2 border border-gray-300 rounded" />
          <label for="ration">Ration-Card :</label>
          <select v-model="form.ration_card" name="ration" required class="w-full p-2 border border-gray-300 rounded">
            <option disabled value="">Select Ration Card Option</option>
            <option value="APL">APL</option>
            <option value="BPL">BPL</option>
          </select>
          <label for="member">Family Member :</label>
          <input v-model.number="form.number_of_family_members" type="number" name="member" placeholder="No. of Family Members" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="wad">Ward No. :</label>
          <input v-model.number="form.ward_no" name="ward" type="number" placeholder="Ward Number" required class="w-full p-2 border border-gray-300 rounded" />
          <label for="vidhan">Vidhan Sabha :</label>
          <input v-model="form.vidhan_sabha" name="vidhan" placeholder="Vidhan Sabha" required class="w-full p-2 border border-gray-300 rounded" />

          <div class="flex justify-between mt-4">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-500 cursor-pointer text-white rounded hover:bg-gray-600">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white cursor-pointer rounded hover:bg-green-700">Save</button>
          </div>
        </form>
      </div>
    </div>

    <p v-if="message" class="text-green-600 text-center mt-4">{{ message }}</p>
    <p v-if="error" class="text-red-600 mt-4">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'VolunteerFamily',
  data() {
    return {
      family: null,
      form: {
        head_of_family: '',
        mobile_number: '',
        village: '',
        taluka: '',
        district: '',
        address: '',
        sub_caste: '',
        ration_card: '',
        number_of_family_members: 0,
        ward_no: 0,
        vidhan_sabha: '',
      },
      showModal: false,
      message: '',
      error: ''
    };
  },
  computed: {
    tableFields() {
      return {
        head_of_family: this.family?.head_of_family,
        mobile_number: this.family?.mobile_number,
        village: this.family?.village,
        taluka: this.family?.taluka,
        district: this.family?.district,
        address: this.family?.address,
        sub_caste: this.family?.sub_caste,
        ration_card: this.family?.ration_card,
        number_of_family_members: this.family?.number_of_family_members,
        ward_no: this.family?.ward_no,
        vidhan_sabha: this.family?.vidhan_sabha
      };
    }
  },
  methods: {
    formatLabel(key) {
      return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    async fetchFamilyDetails() {
      try {
        const res = await axios.get('/api/volunteer/family-details', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.family = res.data.data[0];
      } catch (err) {
        console.log('No data found yet.');
      }
    },
    async submitForm() {
      this.message = '';
      this.error = '';
      try {
        const res = await axios.post('/api/volunteer/family-details', this.form, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.message = 'Family details submitted successfully!';
        this.family = res.data.data[0];
      } catch (err) {
        this.error = err.response?.data?.message || 'Submission failed.';
      }
    },
    openEditModal() {
      this.form = { ...this.family };
      this.showModal = true;
    },
    async updateForm() {
      try {
        const res = await axios.put('/api/volunteer/family-details/' + this.family.id, this.form, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.family = res.data.data;
        this.showModal = false;
        this.message = 'Family details updated successfully!';
      } catch (err) {
        this.error = err.response?.data?.message || 'Update failed.';
      }
    }
  },
  mounted() {
    this.fetchFamilyDetails();
  }
};
</script>

