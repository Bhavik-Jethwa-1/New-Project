<template>
    <div class="p-6 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Person Details</h2>


        <div class="space-y-6">
            <div v-for="person in personList" :key="person.id"
                class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-indigo-600">
                    Person ID: {{ person.id }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                    <div><strong>Name:</strong> {{ person.name }}</div>
                    <div><strong>Surname:</strong> {{ person.surname }}</div>
                    <div><strong>Father/Husband:</strong> {{ person.father_or_husband_name }}</div>
                    <div><strong>Mother:</strong> {{ person.mother_name }}</div>
                    <div><strong>Date of Birth:</strong> {{ person.date_of_birth }}</div>
                    <div><strong>Gender:</strong> {{ person.gender }}</div>
                    <div><strong>Mobile:</strong> {{ person.mobile_number }}</div>
                    <div><strong>Marital Status:</strong> {{ person.marital_status }}</div>
                    <div><strong>Education:</strong> {{ person.education }}</div>
                    <div><strong>Education Details:</strong> {{ person.education_details }}</div>
                    <div><strong>Completion Year:</strong> {{ person.education_completion_year }}</div>
                    <div><strong>Occupation:</strong> {{ person.occupation }}</div>
                    <div><strong>Occupation Details:</strong> {{ person.occupation_details }}</div>
                    <div><strong>Handicap:</strong> {{ person.handicap }}</div>
                    <div><strong>Handicap %:</strong> {{ person.handicap_percentage }}</div>
                    <div><strong>Handicap Card:</strong> {{ person.handicap_card }}</div>
                    <div><strong>Orphan:</strong> {{ person.orphan }}</div>
                    <div><strong>Aadhar:</strong> {{ person.aadhar_card_no }}</div>
                    <div><strong>Govt Job:</strong> {{ person.government_service }}</div>
                    <div><strong>Income Tax:</strong> {{ person.eligible_for_income_tax }}</div>
                    <div><strong>Driving Licence:</strong> {{ person.driving_licence }}</div>
                    <div><strong>Voter ID:</strong> {{ person.election_card }}</div>
                    <div><strong>PAN Card:</strong> {{ person.pan_card }}</div>
                    <div><strong>Shramik Card:</strong> {{ person.sharamik_card }}</div>
                    <div><strong>Maa Amruta:</strong> {{ person.maa_amruta_card }}</div>
                    <div><strong>Caste Certificate:</strong> {{ person.cast_certificate }}</div>
                    <div><strong>Birth Certificate:</strong> {{ person.birth_certificate }}</div>
                    <div><strong>Insurance:</strong> {{ person.insurance_policy }}</div>
                    <div><strong>ABHA Card:</strong> {{ person.abha_card }}</div>
                    <div><strong>Jandhan Account:</strong> {{ person.jandhan_account }}</div>
                </div>

                <div class="mt-4">
                    <button @click="openEditModal(person)"
                        class="bg-indigo-500 cursor-pointer hover:bg-indigo-600 text-white px-4 py-1 rounded text-sm">
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30">
            <div class="bg-white p-6 w-[90%] rounded-xl max-w-2xl shadow-xl relative overflow-y-auto max-h-[90vh]">
                <button @click="showModal = false"
                    class="absolute top-3 right-4 cursor-pointer text-red-500 text-2xl hover:text-red-700">
                    ✖
                </button>
                <h3 class="text-xl font-bold mb-4">Edit Person Details</h3>

                <form @submit.prevent="submitForm" class="grid grid-cols-2 gap-4">
                    <input v-model="form.family_id" placeholder="Family_id"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.name" placeholder="Name" class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.surname" placeholder="Surname"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.father_or_husband_name" placeholder="Father/Husband Name"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.mother_name" placeholder="Mother Name"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.date_of_birth" type="date" class="w-full p-2 border border-gray-300 rounded" />
                    <select v-model="form.gender" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                    <input v-model="form.mobile_number" placeholder="Mobile Number" maxlength="10"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <select v-model="form.marital_status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled>Select Marital Status</option>
                        <option value="unmarried">Unmarried</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                    </select>
                    <select v-model="form.education" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled>Select Education</option>
                        <option value="uneducated">Uneducated</option>
                        <option value="studing">Studying</option>
                        <option value="completed">Completed</option>
                    </select>
                    <input v-model="form.education_details" placeholder="Education Details"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.education_completion_year" type="number" placeholder="Completion Year"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.occupation" placeholder="Occupation"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <input v-model="form.occupation_details" placeholder="Occupation Details"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <select v-model="form.handicap" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled>Handicap</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <input v-model="form.handicap_percentage" type="number" placeholder="Handicap %"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <select v-model="form.handicap_card" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled>Handicap Card</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <select v-model="form.orphan" class="w-full p-2 border border-gray-300 rounded">
                        <option value="" disabled>Orphan</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <input v-model="form.aadhar_card_no" placeholder="Aadhar Number" maxlength="16"
                        class="w-full p-2 border border-gray-300 rounded" />
                    <template v-for="field in yesNoFields" :key="field">
                        <select v-model="form[field]" class="w-full p-2 border border-gray-300 rounded">
                            <option value="" selected disabled>{{ formatLabel(field) }}</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </template>

                    <div class="col-span-2 flex justify-end space-x-2 mt-4">
                        <button type="button" @click="closeModal"
                            class="bg-gray-500 cursor-pointer text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit"
                            class="bg-blue-600 text-white cursor-pointer px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PersonDetail',
    data() {
        return {
            personList: [],
            form: {},
            showModal: false,
            yesNoFields: [
                'government_service', 'eligible_for_income_tax', 'driving_licence', 'election_card',
                'pan_card', 'sharamik_card', 'maa_amruta_card', 'cast_certificate',
                'birth_certificate', 'insurance_policy', 'abha_card', 'jandhan_account'
            ]
        };
    },
    methods: {
        async fetchData() {
            try {
                const res = await axios.get('/api/volunteer/person-details', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });
                this.personList = res.data.data || [];
                // this.family_id = res.data.data?.id;
            } catch (err) {
                console.error('Error fetching data', err);
            }
        },
        openEditModal(person) {
            this.showModal = true;
            // this.form = { ...person };
            const { family, ...rest } = person;
            this.form = {
                ...rest,
                family_id: person.family_id || family?.id || null
            };
        },
        closeModal() {
            this.showModal = false;
            this.form = {};
        },
        formatLabel(field) {
            return field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },
        // async submitForm() {
        //     try {
        //         const payload = { ...this.form, family_id: this.family_id };
        //         const res = await axios.post('/api/volunteer/person-details', payload, {
        //             headers: {
        //                 Authorization: `Bearer ${localStorage.getItem('token')}`
        //             }
        //         });
        //         this.closeModal();
        //         this.fetchData();
        //     } catch (err) {
        //         console.error('Failed to save person details', err);
        //     }
        // }
        async submitForm() {
            try {
                const headers = {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                };

                // Editing existing person (has an ID)
                if (this.form.id) {
                    await axios.put(`/api/volunteer/person-details/${this.form.id}`, this.form, { headers });
                } else {
                    // New person
                    await axios.post('/api/volunteer/person-details', this.form, { headers });
                }

                this.closeModal();
                this.fetchData();
            } catch (err) {
                console.error('Failed to save person details', err.response?.data || err);
            }
        }
    },
    mounted() {
        this.fetchData();
    }
}
</script>
