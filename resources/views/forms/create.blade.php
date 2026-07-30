<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Form Builder</span>
            <span class="text-slate-300">/</span>
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                Create Dynamic Form
            </h2>
        </div>
    </x-slot>

    <div x-data="formBuilder()" class="max-w-7xl mx-auto space-y-6">
        <form :action="formActionUrl" method="POST" @submit="submitForm($event)">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 2 Cols: Form Metadata & Builder Canvas -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Form Details -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Form Configuration</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Form Title</label>
                                <input type="text" name="title" required placeholder="e.g., Lead Contact Form" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Publishing Status</label>
                                <select name="status" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                    <option value="active">Active (Accepting Submissions)</option>
                                    <option value="draft">Draft (Private)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Description</label>
                            <textarea name="description" rows="2" placeholder="Describe the purpose of this form..." class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"></textarea>
                        </div>
                    </div>

                    <!-- Builder Workspace / Canvas -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col min-h-[400px]">
                        <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Workspace Canvas</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Drag-and-drop or click elements from the right sidebar to construct your form.</p>
                            </div>
                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 rounded-lg" x-text="`${fields.length} Elements`"></span>
                        </div>

                        <!-- Dropzone Area -->
                        <div
                            @dragover.prevent="dragOver"
                            @dragleave="dragLeave"
                            @drop.prevent="dropElement"
                            class="flex-1 rounded-2xl border-2 border-dashed p-6 transition-all duration-200 flex flex-col justify-start space-y-4"
                            :class="isDraggingOver ? 'border-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/10'"
                        >
                            <template x-if="fields.length === 0">
                                <div class="my-auto py-12 text-center text-slate-400">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold">Your form canvas is empty</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Click field buttons on the side, or drag them here to add elements dynamically.</p>
                                </div>
                            </template>

                            <!-- Rendered Fields list inside Canvas -->
                            <template x-for="(field, index) in fields" :key="field.id">
                                <div
                                    class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-indigo-500/50 transition-colors cursor-pointer relative group"
                                    :class="selectedFieldId === field.id ? 'ring-2 ring-indigo-500 border-indigo-500' : ''"
                                    @click="selectField(field.id)"
                                >
                                    <!-- Field Info & Label Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400" x-text="field.type"></span>
                                            <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="field.label"></span>
                                            <span x-show="field.required" class="text-rose-500 font-bold">*</span>
                                        </div>

                                        <!-- Ordering & Action Handles -->
                                        <div class="flex items-center space-x-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" @click.stop="moveUp(index)" :disabled="index === 0" class="p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 disabled:opacity-30">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                            </button>
                                            <button type="button" @click.stop="moveDown(index)" :disabled="index === fields.length - 1" class="p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 disabled:opacity-30">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </button>
                                            <button type="button" @click.stop="deleteField(field.id)" class="p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-950/30 text-rose-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Mock Display depending on Field Type -->
                                    <div class="pointer-events-none">
                                        <!-- Text/Email/Number -->
                                        <template x-if="['text', 'email', 'number'].includes(field.type)">
                                            <input type="text" :placeholder="field.placeholder || 'Enter response...'" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-500 py-2.5 px-3.5 text-sm">
                                        </template>

                                        <!-- Textarea -->
                                        <template x-if="field.type === 'textarea'">
                                            <textarea rows="2" :placeholder="field.placeholder || 'Enter multiline response...'" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-500 py-2.5 px-3.5 text-sm"></textarea>
                                        </template>

                                        <!-- Dropdown Select -->
                                        <template x-if="field.type === 'select'">
                                            <select class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-500 py-2.5 px-3.5 text-sm">
                                                <template x-for="opt in field.options" :key="opt">
                                                    <option x-text="opt"></option>
                                                </template>
                                            </select>
                                        </template>

                                        <!-- Checkbox -->
                                        <template x-if="field.type === 'checkbox'">
                                            <div class="space-y-1.5">
                                                <template x-for="opt in field.options" :key="opt">
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                                        <span class="text-xs text-slate-600 dark:text-slate-400" x-text="opt"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Radio -->
                                        <template x-if="field.type === 'radio'">
                                            <div class="space-y-1.5">
                                                <template x-for="opt in field.options" :key="opt">
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" class="text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                                        <span class="text-xs text-slate-600 dark:text-slate-400" x-text="opt"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Col: Controls Toolbox & Configurations panel -->
                <div class="space-y-6">
                    <!-- Elements Toolbox -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Form Elements</h3>
                        <p class="text-xs text-slate-500">Click or drag elements into the workspace to build your custom form structure.</p>

                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="addField('text')" draggable="true" @dragstart="dragStart($event, 'text')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Text Input</span>
                            </button>

                            <button type="button" @click="addField('textarea')" draggable="true" @dragstart="dragStart($event, 'textarea')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Textarea</span>
                            </button>

                            <button type="button" @click="addField('email')" draggable="true" @dragstart="dragStart($event, 'email')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 00-2 2z" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Email</span>
                            </button>

                            <button type="button" @click="addField('number')" draggable="true" @dragstart="dragStart($event, 'number')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Number</span>
                            </button>

                            <button type="button" @click="addField('select')" draggable="true" @dragstart="dragStart($event, 'select')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Dropdown</span>
                            </button>

                            <button type="button" @click="addField('checkbox')" draggable="true" @dragstart="dragStart($event, 'checkbox')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Checkbox</span>
                            </button>

                            <button type="button" @click="addField('radio')" draggable="true" @dragstart="dragStart($event, 'radio')" class="py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:bg-indigo-50/10 transition-colors flex flex-col items-center justify-center space-y-1.5 cursor-grab">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Radio Options</span>
                            </button>
                        </div>
                    </div>

                    <!-- Selected Field Configurations Drawer/Panel -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Field Properties</h3>
                        </div>

                        <template x-if="!activeField">
                            <p class="text-xs text-slate-500 italic py-6 text-center">Click on any element in the workspace canvas to configure its dynamic options here.</p>
                        </template>

                        <template x-if="activeField">
                            <div class="space-y-4">
                                <!-- Field Name (derived identifier) -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Field ID / Key</label>
                                    <input type="text" x-model="activeField.name" readonly class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 text-slate-500 py-2 px-3 text-xs focus:outline-none">
                                </div>

                                <!-- Field Label -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Field Label / Title</label>
                                    <input type="text" x-model="activeField.label" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                </div>

                                <!-- Placeholder (applicable to text/textarea/email/number) -->
                                <template x-if="['text', 'email', 'number', 'textarea'].includes(activeField.type)">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Placeholder Text</label>
                                        <input type="text" x-model="activeField.placeholder" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                    </div>
                                </template>

                                <!-- Validation Required -->
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" x-model="activeField.required" id="field_required_chk" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                                    <label for="field_required_chk" class="text-xs font-bold text-slate-700 dark:text-slate-300">Required validation field</label>
                                </div>

                                <!-- Dynamic Options Manager (applicable to select, checkbox, radio) -->
                                <template x-if="['select', 'checkbox', 'radio'].includes(activeField.type)">
                                    <div class="space-y-2 border-t border-slate-100 dark:border-slate-800 pt-3">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dynamic Select Options</label>

                                        <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                            <template x-for="(opt, oIdx) in activeField.options" :key="oIdx">
                                                <div class="flex items-center space-x-1.5">
                                                    <input type="text" :value="opt" @input="updateOption(oIdx, $event.target.value)" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-1.5 px-2.5 text-xs">
                                                    <button type="button" @click="deleteOption(oIdx)" class="p-1 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button" @click="addOption()" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center space-x-1">
                                            <span>+ Add Option</span>
                                        </button>
                                    </div>
                                </template>

                                <!-- Delete Field Trigger -->
                                <button type="button" @click="deleteField(activeField.id)" class="w-full mt-4 py-2 px-3 border border-rose-200 dark:border-rose-950/50 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-rose-600 rounded-xl text-xs font-bold transition-colors">
                                    Remove Field From Canvas
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden Input to post structured compiled fields JSON -->
                    <input type="hidden" name="fields" :value="JSON.stringify(fields)">

                    <!-- Submission Action button -->
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs tracking-wider uppercase transition-all duration-200 shadow-lg shadow-indigo-600/20 flex items-center justify-center space-x-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span>Save Form Schema</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script backing Form Builder logic -->
    <script>
        function formBuilder() {
            return {
                fields: [],
                selectedFieldId: null,
                isDraggingOver: false,
                currentDragType: null,
                formActionUrl: "{{ route('forms.store') }}",

                init() {
                    // Pre-populate if editing (will be overridden on edit page)
                    if (window.prePopulatedFields) {
                        this.fields = window.prePopulatedFields;
                        if (this.fields.length > 0) {
                            this.selectedFieldId = this.fields[0].id;
                        }
                    }
                },

                get activeField() {
                    return this.fields.find(f => f.id === this.selectedFieldId) || null;
                },

                // Click-to-add element
                addField(type) {
                    const id = 'field_' + Math.random().toString(36).substr(2, 9);
                    const label = type.charAt(0).toUpperCase() + type.slice(1) + ' Field';
                    const name = type + '_' + Math.random().toString(36).substr(2, 5);

                    const newField = {
                        id: id,
                        type: type,
                        label: label,
                        name: name,
                        placeholder: '',
                        required: false,
                        options: ['Option 1', 'Option 2', 'Option 3']
                    };

                    this.fields.push(newField);
                    this.selectedFieldId = id;
                },

                selectField(id) {
                    this.selectedFieldId = id;
                },

                deleteField(id) {
                    this.fields = this.fields.filter(f => f.id !== id);
                    if (this.selectedFieldId === id) {
                        this.selectedFieldId = this.fields.length > 0 ? this.fields[0].id : null;
                    }
                },

                moveUp(index) {
                    if (index > 0) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index - 1];
                        this.fields[index - 1] = temp;
                    }
                },

                moveDown(index) {
                    if (index < this.fields.length - 1) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index + 1];
                        this.fields[index + 1] = temp;
                    }
                },

                addOption() {
                    if (this.activeField) {
                        this.activeField.options.push('New Option ' + (this.activeField.options.length + 1));
                    }
                },

                updateOption(optIdx, newVal) {
                    if (this.activeField) {
                        this.activeField.options[optIdx] = newVal;
                    }
                },

                deleteOption(optIdx) {
                    if (this.activeField) {
                        this.activeField.options.splice(optIdx, 1);
                    }
                },

                // HTML5 Drag and Drop Handlers
                dragStart(event, type) {
                    this.currentDragType = type;
                    event.dataTransfer.setData('text/plain', type);
                    event.dataTransfer.effectAllowed = 'move';
                },

                dragOver() {
                    this.isDraggingOver = true;
                },

                dragLeave() {
                    this.isDraggingOver = false;
                },

                dropElement(event) {
                    this.isDraggingOver = false;
                    const type = event.dataTransfer.getData('text/plain') || this.currentDragType;
                    if (type) {
                        this.addField(type);
                    }
                    this.currentDragType = null;
                },

                submitForm(event) {
                    if (this.fields.length === 0) {
                        event.preventDefault();
                        alert('Please add at least one element onto the canvas before saving.');
                    }
                }
            };
        }
    </script>
</x-app-layout>
