@extends('layout.user')

@section('title', 'رفع وصفة طبية جديدة')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">رفع وصفة طبية جديدة</h1>
        <p class="text-gray-500 dark:text-gray-400">اختر الأدوية المطلوبة وأرفق صورة الوصفة الطبية</p>
    </div>

    <!-- Form -->
    <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              step: 1,
              selectedMeds: {},
              searchTerm: '',
              showMedicationsList: false,
              medications: [],
              addMedication(med) {
                  this.selectedMeds[med.id] = {
                      medication: med,
                      quantity: 1,
                      dosage_instructions: ''
                  };
                  this.showMedicationsList = false;
                  this.searchTerm = '';
              },
              removeMedication(medId) {
                  delete this.selectedMeds[medId];
              },
              searchMedications() {
                  if (this.searchTerm.length < 2) {
                      this.medications = [];
                      return;
                  }
                  fetch(`/api/medications/search?q=${this.searchTerm}`)
                      .then(res => res.json())
                      .then(data => {
                          this.medications = data;
                          this.showMedicationsList = true;
                      });
              }
          }"
          class="space-y-8">
        @csrf

        <!-- Step 1: اختيار الأدوية -->
        <div x-show="step === 1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">اختيار الأدوية</h2>

                <!-- Search Bar -->
                <div class="relative mb-6">
                    <input type="text"
                           x-model="searchTerm"
                           @input="searchMedications"
                           @click.away="showMedicationsList = false"
                           placeholder="ابحث عن الدواء..."
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                    
                    <!-- Search Results -->
                    <div x-show="showMedicationsList" 
                         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-64 overflow-y-auto">
                        <template x-for="med in medications" :key="med.id">
                            <button type="button"
                                    @click="addMedication(med)"
                                    class="w-full px-4 py-3 text-right hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="font-medium text-gray-800 dark:text-white" x-text="med.name"></div>
                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="med.strength"></div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Selected Medications -->
                <div class="space-y-4">
                    <template x-for="(item, id) in selectedMeds" :key="id">
                        <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-white" x-text="item.medication.name"></h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="item.medication.strength"></p>
                                    </div>
                                    <button type="button" @click="removeMedication(id)"
                                            class="p-1 text-gray-400 hover:text-rose-500">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">الكمية</label>
                                        <input type="number" x-model="item.quantity"
                                               :name="`medications[${id}][quantity]`"
                                               class="mt-1 w-20 px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700"
                                               min="1">
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">تعليمات الجرعة</label>
                                        <input type="text" x-model="item.dosage_instructions"
                                               :name="`medications[${id}][dosage_instructions]`"
                                               class="mt-1 w-full px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700"
                                               placeholder="مثال: مرتين يومياً">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="Object.keys(selectedMeds).length === 0">
                        <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-pills text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <h4 class="text-gray-500 dark:text-gray-400">ابحث عن الأدوية وأضفها إلى الوصفة</h4>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Next Step Button -->
            <div class="flex justify-end mt-6">
                <button type="button" 
                        @click="step = 2"
                        :disabled="Object.keys(selectedMeds).length === 0"
                        class="px-6 py-3 rounded-xl bg-teal-600 dark:bg-teal-500 text-white disabled:opacity-50">
                    التالي
                    <i class="fas fa-arrow-left mr-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: معلومات الوصفة -->
        <div x-show="step === 2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-gray-700/10 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">معلومات الوصفة الطبية</h2>
                
                <!-- Doctor Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اسم الطبيب
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                            name="doctor_name" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اسم المستشفى
                        </label>
                        <input type="text" 
                            name="hospital_name"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700">
                    </div>
                </div>

                <!-- Prescription Date -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        تاريخ الوصفة
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                        name="prescription_date" 
                        required
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700">
                </div>

                <!-- Prescription Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        صورة الوصفة الطبية
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" 
                        name="image" 
                        required
                        accept="image/*"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700">
                    <p class="mt-2 text-sm text-gray-500">يجب أن تكون الصورة واضحة وبحجم لا يتجاوز 5 ميجابايت</p>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ملاحظات إضافية
                    </label>
                    <textarea name="notes" 
                            rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700"></textarea>
                </div>

                <!-- Hidden Medications Data -->
                <template x-for="(item, id) in selectedMeds" :key="id">
                    <div class="hidden">
                        <input type="hidden" :name="`medications[${id}][id]`" :value="item.medication.id">
                        <input type="hidden" :name="`medications[${id}][quantity]`" :value="item.quantity">
                        <input type="hidden" :name="`medications[${id}][dosage_instructions]`" :value="item.dosage_instructions">
                    </div>
                </template>
            </div>
            <!-- Navigation Buttons -->
        <div class="flex justify-between mt-6">
            <button type="button" 
                    @click="step = 1"
                    class="px-6 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-right ml-2"></i>
                السابق
            </button>
    
            <button type="submit" 
                    class="px-6 py-3 rounded-xl bg-teal-600 dark:bg-teal-500 text-white hover:bg-teal-700 dark:hover:bg-teal-600 transition-colors">
                <i class="fas fa-check ml-2"></i>
                رفع الوصفة
            </button>
        </div>
        </div>


    </form>
</div>
@endsection