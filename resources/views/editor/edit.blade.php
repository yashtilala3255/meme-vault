@extends('layouts.app')

@section('title', 'Edit ' . $template->name . ' - MemeVault')

@push('styles')
<style>
    .canvas-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .canvas-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    canvas {
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .tool-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .tool-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .gradient-border {
        position: relative;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
        border: 3px solid transparent;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')

<!-- Editor Header -->
<section class="bg-gradient-to-r from-gray-900 via-purple-900 to-gray-900 py-6 border-b border-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('templates.index') }}" class="p-3 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $template->name }}</h1>
                    <p class="text-gray-400 text-sm">Meme Editor</p>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-3">
                <button onclick="memeEditor.resetCanvas()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-semibold transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
                <button onclick="memeEditor.downloadMeme()" class="px-6 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg hover:shadow-neon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Editor Workspace -->
<section class="py-8 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Canvas Area -->
            <div class="lg:col-span-8">
                <div class="tool-card">
                    <!-- Canvas Container -->
                    <div class="flex justify-center items-center mb-6">
                        <div id="canvas-wrapper" class="canvas-container">
                            <canvas id="memeCanvas"></canvas>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="flex flex-wrap justify-center gap-3">
                        <button onclick="memeEditor.addText()" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-bold hover:shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Text
                        </button>
                        <button onclick="memeEditor.deleteSelected()" class="px-6 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                        <button onclick="memeEditor.resetCanvas()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tools Panel -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Tab Navigation -->
                <div class="tool-card">
                    <div class="grid grid-cols-2 gap-2 p-1 bg-gray-100 rounded-xl">
                        <button onclick="memeEditor.switchTab('text')" id="tab-text" class="py-3 rounded-lg font-bold text-white bg-gradient-to-r from-primary-500 to-primary-600 transition-all">
                            ✏️ Text
                        </button>
                        <button onclick="memeEditor.switchTab('customize')" id="tab-customize" class="py-3 rounded-lg font-bold text-gray-700 hover:bg-gray-200 transition-all">
                            🎨 Style
                        </button>
                    </div>
                </div>
                
                <!-- Text Tools -->
                <div id="text-tab" class="tool-card space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Text Controls
                        </h3>
                        <button onclick="memeEditor.addText()" class="px-4 py-2 bg-primary-500 text-white rounded-lg text-sm font-bold hover:bg-primary-600 transition-all">
                            + Add
                        </button>
                    </div>
                    
                    <div id="text-controls" style="display: none;" class="space-y-4 border-t border-gray-200 pt-4">
                        <!-- Text Input -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Text Content</label>
                            <textarea 
                                id="text-content"
                                rows="3"
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 text-gray-900 font-semibold transition-all"
                                placeholder="Enter your text..."
                            ></textarea>
                        </div>
                        
                        <!-- Font Family -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Font</label>
                            <select id="font-family" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900 font-semibold transition-all">
                                <option value="Impact">Impact</option>
                                <option value="Arial Black">Arial Black</option>
                                <option value="Arial">Arial</option>
                                <option value="Comic Sans MS">Comic Sans MS</option>
                                <option value="Helvetica">Helvetica</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Courier New">Courier New</option>
                                <option value="Verdana">Verdana</option>
                            </select>
                        </div>
                        
                        <!-- Font Size -->
                        <div>
                            <label class="flex items-center justify-between text-sm font-bold text-gray-700 mb-2">
                                <span>Font Size</span>
                                <span id="font-size-label" class="px-3 py-1 bg-primary-100 text-primary-700 rounded-lg text-xs">48px</span>
                            </label>
                            <input type="range" id="font-size" min="12" max="120" value="48" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-500">
                        </div>
                        
                        <!-- Colors -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Text Color</label>
                                <div class="relative">
                                    <input type="color" id="text-color" value="#ffffff" class="w-full h-14 rounded-xl cursor-pointer border-4 border-gray-200">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Outline</label>
                                <div class="relative">
                                    <input type="color" id="stroke-color" value="#000000" class="w-full h-14 rounded-xl cursor-pointer border-4 border-gray-200">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Outline Width -->
                        <div>
                            <label class="flex items-center justify-between text-sm font-bold text-gray-700 mb-2">
                                <span>Outline Width</span>
                                <span id="stroke-width-label" class="px-3 py-1 bg-secondary-100 text-secondary-700 rounded-lg text-xs">3px</span>
                            </label>
                            <input type="range" id="stroke-width" min="0" max="15" value="3" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-secondary-500">
                        </div>
                        
                        <!-- Text Alignment -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alignment</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button onclick="memeEditor.alignText('left')" class="py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-gray-700 transition-all">
                                    ⬅️ Left
                                </button>
                                <button onclick="memeEditor.alignText('center')" class="py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-gray-700 transition-all">
                                    ↔️ Center
                                </button>
                                <button onclick="memeEditor.alignText('right')" class="py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-gray-700 transition-all">
                                    ➡️ Right
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bold Toggle -->
                        <button onclick="memeEditor.makeTextBold()" class="w-full py-3 bg-gray-900 text-white rounded-xl font-black hover:bg-gray-800 transition-all">
                            <span class="text-lg">B</span> Bold
                        </button>
                        
                        <!-- Delete Button -->
                        <button onclick="memeEditor.deleteSelected()" class="w-full py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Selected
                        </button>
                    </div>
                    
                    <div id="no-selection" class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary-100 to-secondary-100 mb-4">
                            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-semibold mb-2">No text selected</p>
                        <p class="text-sm text-gray-400">Click "Add Text" or select existing text</p>
                    </div>
                </div>
                
                <!-- Customize Tools -->
                <div id="customize-tab" class="tool-card space-y-4" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        Image Filters
                    </h3>
                    
                    <!-- Brightness -->
                    <div>
                        <label class="flex items-center justify-between text-sm font-bold text-gray-700 mb-2">
                            <span>☀️ Brightness</span>
                            <span id="brightness-label" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs">1.0</span>
                        </label>
                        <input type="range" id="brightness" min="0" max="2" step="0.1" value="1" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-yellow-500">
                    </div>
                    
                    <!-- Contrast -->
                    <div>
                        <label class="flex items-center justify-between text-sm font-bold text-gray-700 mb-2">
                            <span>🎚️ Contrast</span>
                            <span id="contrast-label" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs">1.0</span>
                        </label>
                        <input type="range" id="contrast" min="0" max="2" step="0.1" value="1" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                    </div>
                    
                    <!-- Saturation -->
                    <div>
                        <label class="flex items-center justify-between text-sm font-bold text-gray-700 mb-2">
                            <span>🌈 Saturation</span>
                            <span id="saturation-label" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs">1.0</span>
                        </label>
                        <input type="range" id="saturation" min="0" max="2" step="0.1" value="1" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-500">
                    </div>
                    
                    <!-- Transform Buttons -->
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <button onclick="memeEditor.flipHorizontal()" class="w-full py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-bold hover:from-blue-600 hover:to-cyan-600 transition-all">
                            ↔️ Flip Horizontal
                        </button>
                        <button onclick="memeEditor.flipVertical()" class="w-full py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-bold hover:from-purple-600 hover:to-pink-600 transition-all">
                            ↕️ Flip Vertical
                        </button>
                        <button onclick="memeEditor.resetFilters()" class="w-full py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all">
                            🔄 Reset All Filters
                        </button>
                    </div>
                </div>
                
                <!-- Download Options -->
                <div class="tool-card space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Options
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Format</label>
                        <select id="download-format" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 text-gray-900 font-semibold transition-all">
                            <option value="png">PNG (Best Quality)</option>
                            <option value="jpeg">JPG (Smaller Size)</option>
                            <option value="webp">WebP (Modern)</option>
                        </select>
                    </div>
                    
                    <button onclick="memeEditor.downloadMeme()" class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-xl font-black text-lg shadow-lg hover:shadow-neon transition-all flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Meme
                    </button>
                    
                    <button onclick="memeEditor.shareMeme()" class="w-full py-3 bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share Meme
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
// Use the same memeEditor JavaScript from the previous version
// (The JavaScript code remains exactly the same as before)

const memeEditor = {
    canvas: null,
    backgroundImage: null,
    selectedObject: null,
    templateSlug: '{{ $template->slug }}',
    templateName: '{{ $template->name }}',
    
    init() {
        console.log('Initializing meme editor...');
        
        this.canvas = new fabric.Canvas('memeCanvas', {
            width: 600,
            height: 600,
            backgroundColor: '#ffffff'
        });
        
        const imageUrl = '{{ $template->image_url }}';
        console.log('Loading image:', imageUrl);
        
        fabric.Image.fromURL(imageUrl, (img) => {
            if (!img) {
                alert('Failed to load image. Please try again.');
                return;
            }
            
            const scale = Math.min(
                this.canvas.width / img.width,
                this.canvas.height / img.height
            );
            
            img.scale(scale);
            img.set({
                left: this.canvas.width / 2,
                top: this.canvas.height / 2,
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            
            this.backgroundImage = img;
            this.canvas.setBackgroundImage(img, this.canvas.renderAll.bind(this.canvas));
            
            console.log('Image loaded successfully');
        }, { crossOrigin: 'anonymous' });
        
        this.canvas.on('selection:created', (e) => this.onObjectSelected(e));
        this.canvas.on('selection:updated', (e) => this.onObjectSelected(e));
        this.canvas.on('selection:cleared', () => this.onSelectionCleared());
        
        this.bindInputEvents();
    },
    
    bindInputEvents() {
        document.getElementById('text-content').addEventListener('input', (e) => {
            if (this.selectedObject && this.selectedObject.type === 'i-text') {
                this.selectedObject.set('text', e.target.value);
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('font-family').addEventListener('change', (e) => {
            if (this.selectedObject) {
                this.selectedObject.set('fontFamily', e.target.value);
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('font-size').addEventListener('input', (e) => {
            document.getElementById('font-size-label').textContent = e.target.value + 'px';
            if (this.selectedObject) {
                this.selectedObject.set('fontSize', parseInt(e.target.value));
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('text-color').addEventListener('input', (e) => {
            if (this.selectedObject) {
                this.selectedObject.set('fill', e.target.value);
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('stroke-color').addEventListener('input', (e) => {
            if (this.selectedObject) {
                this.selectedObject.set('stroke', e.target.value);
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('stroke-width').addEventListener('input', (e) => {
            document.getElementById('stroke-width-label').textContent = e.target.value + 'px';
            if (this.selectedObject) {
                this.selectedObject.set('strokeWidth', parseInt(e.target.value));
                this.canvas.renderAll();
            }
        });
        
        document.getElementById('brightness').addEventListener('input', (e) => {
            document.getElementById('brightness-label').textContent = e.target.value;
            this.applyFilters();
        });
        
        document.getElementById('contrast').addEventListener('input', (e) => {
            document.getElementById('contrast-label').textContent = e.target.value;
            this.applyFilters();
        });
        
        document.getElementById('saturation').addEventListener('input', (e) => {
            document.getElementById('saturation-label').textContent = e.target.value;
            this.applyFilters();
        });
    },
    
    addText() {
        const text = new fabric.IText('YOUR TEXT HERE', {
            left: this.canvas.width / 2,
            top: this.canvas.height / 4,
            fontFamily: 'Impact',
            fontSize: 48,
            fill: '#ffffff',
            stroke: '#000000',
            strokeWidth: 3,
            textAlign: 'center',
            originX: 'center',
            fontWeight: 'bold',
            paintFirst: 'stroke'
        });
        
        this.canvas.add(text);
        this.canvas.setActiveObject(text);
        this.canvas.renderAll();
        
        this.switchTab('text');
    },
    
    onObjectSelected(e) {
        const obj = e.selected[0];
        if (obj && obj.type === 'i-text') {
            this.selectedObject = obj;
            
            document.getElementById('text-content').value = obj.text;
            document.getElementById('font-family').value = obj.fontFamily;
            document.getElementById('font-size').value = obj.fontSize;
            document.getElementById('font-size-label').textContent = obj.fontSize + 'px';
            document.getElementById('text-color').value = obj.fill;
            document.getElementById('stroke-color').value = obj.stroke;
            document.getElementById('stroke-width').value = obj.strokeWidth;
            document.getElementById('stroke-width-label').textContent = obj.strokeWidth + 'px';
            
            document.getElementById('text-controls').style.display = 'block';
            document.getElementById('no-selection').style.display = 'none';
        }
    },
    
    onSelectionCleared() {
        this.selectedObject = null;
        document.getElementById('text-controls').style.display = 'none';
        document.getElementById('no-selection').style.display = 'block';
    },
    
    deleteSelected() {
        const activeObject = this.canvas.getActiveObject();
        if (activeObject) {
            this.canvas.remove(activeObject);
            this.canvas.discardActiveObject();
            this.canvas.renderAll();
        }
    },
    
    alignText(alignment) {
        if (this.selectedObject) {
            this.selectedObject.set('textAlign', alignment);
            this.canvas.renderAll();
        }
    },
    
    makeTextBold() {
        if (this.selectedObject) {
            const currentWeight = this.selectedObject.fontWeight;
            this.selectedObject.set('fontWeight', currentWeight === 'bold' ? 'normal' : 'bold');
            this.canvas.renderAll();
        }
    },
    
    applyFilters() {
        if (!this.backgroundImage) return;
        
        const brightness = parseFloat(document.getElementById('brightness').value);
        const contrast = parseFloat(document.getElementById('contrast').value);
        const saturation = parseFloat(document.getElementById('saturation').value);
        
        this.backgroundImage.filters = [
            new fabric.Image.filters.Brightness({ brightness: brightness - 1 }),
            new fabric.Image.filters.Contrast({ contrast: contrast - 1 }),
            new fabric.Image.filters.Saturation({ saturation: saturation - 1 })
        ];
        
        this.backgroundImage.applyFilters();
        this.canvas.renderAll();
    },
    
    resetFilters() {
        document.getElementById('brightness').value = 1;
        document.getElementById('contrast').value = 1;
        document.getElementById('saturation').value = 1;
        document.getElementById('brightness-label').textContent = '1.0';
        document.getElementById('contrast-label').textContent = '1.0';
        document.getElementById('saturation-label').textContent = '1.0';
        
        if (this.backgroundImage) {
            this.backgroundImage.filters = [];
            this.backgroundImage.applyFilters();
            this.canvas.renderAll();
        }
    },
    
    flipHorizontal() {
        if (this.backgroundImage) {
            this.backgroundImage.set('flipX', !this.backgroundImage.flipX);
            this.canvas.renderAll();
        }
    },
    
    flipVertical() {
        if (this.backgroundImage) {
            this.backgroundImage.set('flipY', !this.backgroundImage.flipY);
            this.canvas.renderAll();
        }
    },
    
    resetCanvas() {
        if (confirm('Are you sure you want to reset? All changes will be lost.')) {
            location.reload();
        }
    },
    
    switchTab(tab) {
        document.getElementById('tab-text').classList.remove('bg-gradient-to-r', 'from-primary-500', 'to-primary-600', 'text-white');
        document.getElementById('tab-customize').classList.remove('bg-gradient-to-r', 'from-primary-500', 'to-primary-600', 'text-white');
        document.getElementById('tab-text').classList.add('text-gray-700', 'dark:text-gray-300');
        document.getElementById('tab-customize').classList.add('text-gray-700', 'dark:text-gray-300');
        
        document.getElementById('tab-' + tab).classList.add('bg-gradient-to-r', 'from-primary-500', 'to-primary-600', 'text-white');
        document.getElementById('tab-' + tab).classList.remove('text-gray-700', 'dark:text-gray-300');
        
        document.getElementById('text-tab').style.display = tab === 'text' ? 'block' : 'none';
        document.getElementById('customize-tab').style.display = tab === 'customize' ? 'block' : 'none';
    },
    
    downloadMeme() {
        const format = document.getElementById('download-format').value;
        const dataURL = this.canvas.toDataURL({
            format: format,
            quality: 0.95,
            multiplier: 2
        });
        
        const link = document.createElement('a');
        link.download = this.templateSlug + '-meme.' + format;
        link.href = dataURL;
        link.click();
        
        fetch('{{ route("templates.download", $template) }}').catch(() => {});
        
        // Success notification (you can add a toast notification here)
        alert('✅ Meme downloaded successfully!');
    },
    
    shareMeme() {
        if (navigator.share) {
            this.canvas.toBlob((blob) => {
                const format = document.getElementById('download-format').value;
                const file = new File([blob], 'meme.' + format, { 
                    type: 'image/' + format 
                });
                
                navigator.share({
                    files: [file],
                    title: this.templateName,
                    text: 'Check out my meme!'
                }).catch(() => {
                    this.downloadMeme();
                });
            });
        } else {
            alert('Sharing is not supported on this browser. Downloading instead...');
            this.downloadMeme();
        }
    }
};

window.addEventListener('load', () => {
    memeEditor.init();
});
</script>
@endpush

@endsection