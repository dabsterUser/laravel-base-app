<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dynamic Reporting Center') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="reportingCenter()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Description / Info Banner -->
            <div class="bg-gradient-to-r from-slate-900 to-indigo-950 p-6 rounded-2xl shadow-xl text-white flex justify-between items-center">
                <div class="max-w-2xl">
                    <h3 class="text-lg font-bold">Zero-Code Auto-Discovered Reporting</h3>
                    <p class="text-sm text-slate-300 mt-1 leading-relaxed">
                        Whenever you create a new database module or Eloquent model in this application, it will automatically populate here. You can select custom table columns, specify date filters, and instantly generate Excel-ready CSV files or printable HTML tables. No custom coding needed!
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="h-16 w-16 text-indigo-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4V9m-9 3h3m2 0h3m-9 2h3m2 0h3m-9 2h3m2 0h3M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Parameters & Configurations Form -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 lg:col-span-1 space-y-6">
                    <h4 class="text-md font-bold text-slate-800 dark:text-white uppercase tracking-wider">Report Setup</h4>

                    <form action="{{ route('reports.export') }}" method="GET" @submit="validateForm($event)" target="_blank">
                        <!-- Module Selection -->
                        <div class="space-y-2">
                            <label for="model" class="block text-sm font-semibold text-slate-700 dark:text-gray-300">Choose Module / Model</label>
                            <select
                                name="model"
                                id="model"
                                x-model="selectedModel"
                                @change="loadColumns()"
                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-gray-800 placeholder-gray-400 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 transition duration-150"
                                required
                            >
                                <option value="">-- Choose Module --</option>
                                @foreach($modules as $key => $mod)
                                    <option value="{{ $key }}">{{ $mod['label'] }} ({{ $mod['table'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range Filters -->
                        <div class="grid grid-cols-1 gap-4 mt-6">
                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-semibold text-slate-700 dark:text-gray-300">Start Date (Optional)</label>
                                <input
                                    type="date"
                                    name="start_date"
                                    id="start_date"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-gray-800 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 transition duration-150"
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-semibold text-slate-700 dark:text-gray-300">End Date (Optional)</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    id="end_date"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 text-gray-800 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- Format Choice -->
                        <div class="mt-6 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300">Export Format</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">
                                    <input type="radio" name="format" value="csv" checked class="text-indigo-600 focus:ring-indigo-500 mr-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Excel / CSV</span>
                                </label>
                                <label class="flex items-center justify-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">
                                    <input type="radio" name="format" value="print" class="text-indigo-600 focus:ring-indigo-500 mr-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Print / PDF</span>
                                </label>
                            </div>
                        </div>

                        <!-- Hidden Column Inputs generated dynamically by Alpine -->
                        <template x-for="col in selectedColumns">
                            <input type="hidden" name="columns[]" :value="col">
                        </template>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button
                                type="submit"
                                :disabled="!selectedModel || selectedColumns.length === 0"
                                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm uppercase tracking-wider transition shadow-lg shadow-indigo-600/25 disabled:opacity-40 disabled:cursor-not-allowed"
                            >
                                Generate & Export
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Column Selection Area -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-bold text-slate-800 dark:text-white uppercase tracking-wider">Select Columns to Export</h4>

                        <!-- Toggle Buttons -->
                        <div class="flex space-x-2" x-show="columns.length > 0">
                            <button @click="selectAll()" type="button" class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Select All</button>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <button @click="deselectAll()" type="button" class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Clear All</button>
                        </div>
                    </div>

                    <!-- Placeholder -->
                    <div x-show="!selectedModel" class="py-16 text-center text-slate-400 dark:text-slate-500 space-y-3">
                        <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <p class="text-sm">Please select a reporting module from the left menu to load database columns.</p>
                    </div>

                    <!-- Columns Grid -->
                    <div x-show="selectedModel && columns.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="col in columns">
                            <label class="flex items-center p-3 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-900 cursor-pointer transition">
                                <input
                                    type="checkbox"
                                    :value="col"
                                    x-model="selectedColumns"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 mr-3"
                                >
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300" x-text="formatHeader(col)"></span>
                            </label>
                        </template>
                    </div>

                    <!-- Empty State for Columns -->
                    <div x-show="selectedModel && columns.length === 0" class="py-16 text-center text-slate-400 dark:text-slate-500">
                        <p class="text-sm">Loading columns listing...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine.js script to drive report center dynamics -->
    <script>
        function reportingCenter() {
            return {
                selectedModel: '',
                columns: [],
                selectedColumns: [],

                loadColumns() {
                    if (!this.selectedModel) {
                        this.columns = [];
                        this.selectedColumns = [];
                        return;
                    }

                    this.columns = [];
                    this.selectedColumns = [];

                    fetch(`/reports/columns?model=${this.selectedModel}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.columns) {
                                this.columns = data.columns;
                                // Auto-select all by default
                                this.selectedColumns = [...data.columns];
                            }
                        })
                        .catch(err => {
                            console.error("Columns load error: ", err);
                        });
                },

                selectAll() {
                    this.selectedColumns = [...this.columns];
                },

                deselectAll() {
                    this.selectedColumns = [];
                },

                formatHeader(col) {
                    return col
                        .replace(/_/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase());
                },

                validateForm(event) {
                    if (this.selectedColumns.length === 0) {
                        event.preventDefault();
                        alert('Please select at least one column to export!');
                    }
                }
            }
        }
    </script>
</x-app-layout>
