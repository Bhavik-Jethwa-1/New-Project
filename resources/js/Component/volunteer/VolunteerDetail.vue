<template>
  <!-- <div class="p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Volunteer Person Details</h1>

    <table class="min-w-full bg-white border border-gray-300 shadow">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 border-b">Name</th>
          <th class="py-2 px-4 border-b">Surname</th>
          <th class="py-2 px-4 border-b">DOB</th>
          <th class="py-2 px-4 border-b">Gender</th>
          <th class="py-2 px-4 border-b">Aadhar No.</th>
          <th class="py-2 px-4 border-b">Mobile</th>
          <th class="py-2 px-4 border-b">Education</th>
          <th class="py-2 px-4 border-b">Ration Card</th>
          <th class="py-2 px-4 border-b">Village</th>
          <th class="py-2 px-4 border-b">Address</th>
          <th class="py-2 px-4 border-b">District</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="person in personDetails" :key="person.id" class="text-center">
          <td class="py-2 px-4 border-b">{{ person.name }}</td>
          <td class="py-2 px-4 border-b">{{ person.surname }}</td>
          <td class="py-2 px-4 border-b">{{ person.date_of_birth }}</td>
          <td class="py-2 px-4 border-b">{{ person.gender }}</td>
          <td class="py-2 px-4 border-b">{{ person.aadhar_card_no }}</td>
          <td class="py-2 px-4 border-b">{{ person.mobile_no }}</td>
          <td class="py-2 px-4 border-b">{{ person.education }}</td>
          <td class="py-2 px-4 border-b">{{ person.family.ration_card }}</td>
          <td class="py-2 px-4 border-b">{{ person.family.village }}</td>
          <td class="py-2 px-4 border-b">{{ person.family.address }}</td>
          <td class="py-2 px-4 border-b">{{ person.family.district }}</td>
        </tr>
      </tbody>
    </table>
  </div> -->

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Volunteer Person Details</h1>

    <Table :head="tableHeaders" bodyHeight="max-h-[500px] overflow-y-auto">
      <tr v-for="person in personDetails" :key="person.id">
        <td class="py-2 px-4 border-b">{{ person.name }}</td>
        <!-- <td class="py-2 px-4 border-b">{{ person.surname }}</td> -->
        <!-- <td class="py-2 px-4 border-b">{{ person.date_of_birth }}</td> -->
        <!-- <td class="py-2 px-4 border-b">{{ person.gender }}</td> -->
        <!-- <td class="py-2 px-4 border-b">{{ person.aadhar_card_no }}</td> -->
        <td class="py-2 px-4 border-b text-center">{{ person.mobile_no }}</td>
        <!-- <td class="py-2 px-4 border-b">{{ person.education }}</td> -->
        <!-- <td class="py-2 px-4 border-b">{{ person.family.ration_card }}</td> -->
        <td class="py-2 px-4 border-b">{{ person.family.village }}</td>
        <td class="py-2 px-4 border-b">{{ person.family.address }}</td>
        <td class="py-2 px-4 border-b">{{ person.family.district }}</td>
      </tr>
    </Table>
  </div>
</template>

<script>
// import axios from 'axios'

// export default {
//   name: 'VolunteerPersonDetails',
//   data() {
//     return {
//       personDetails: []
//     }
//   },
//   mounted() {
//     const token = localStorage.getItem('token') // Adjust if stored differently

//     axios.get('/api/volunteer/person-details', {
//       headers: {
//         Authorization: `Bearer ${token}`
//       }
//     })
//     .then(response => {
//       this.personDetails = response.data.data
//     })
//     .catch(error => {
//       console.error('Error fetching data:', error)
//       alert('Unauthorized or error occurred.')
//     })
//   }
// }

import axios from 'axios'
import Table from '@/Component/Table.vue'

export default {
  name: 'VolunteerPersonDetails',
  components: {
    Table
  },
  data() {
    return {
      personDetails: [],
      tableHeaders: [
        { title: 'Name', order_by: 'name', sortable: false },
        // { title: 'Surname', order_by: 'surname', sortable: false },
        // { title: 'DOB', order_by: 'date_of_birth', sortable: false },
        // { title: 'Gender', order_by: 'gender', sortable: false },
        // { title: 'Aadhar No.', order_by: 'aadhar_card_no', sortable: false },
        { title: 'Mobile', order_by: 'mobile_no', sortable: false, classes:'text-center' },
        // { title: 'Education', order_by: 'education', sortable: false },
        // { title: 'Ration Card', order_by: 'ration_card', sortable: false },
        { title: 'Village', order_by: 'village', sortable: false },
        { title: 'Address', order_by: 'address', sortable: false },
        { title: 'District', order_by: 'district', sortable: false }
      ]
    }
  },
  mounted() {
    axios.get('/api/volunteer/person-details')
      .then(response => {
        this.personDetails = response.data.data
      })
      .catch(error => {
        console.error('Error fetching data:', error)
        alert('Unauthorized or error occurred.')
      })
  }
}
</script>
