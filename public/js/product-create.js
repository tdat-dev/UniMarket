/**
 * Product Create Form - JavaScript Logic
 * Handles: Condition cards, Price formatting, Image upload, Category picker
 */

document.addEventListener('DOMContentLoaded', function () {
    // --- VARIABLES ---
    let selectedFiles = [];
    const inputImage = document.getElementById('imageInput');
    const miniPreviewGrid = document.getElementById('imagePreviewGrid');
    const inputCondition = document.getElementById('inputCondition');
    const inputName = document.getElementById('inputName');
    const imgError = document.getElementById('imgError');

    // --- CONDITION SELECTION ---
    window.selectCondition = function (element, value) {
        inputCondition.value = value;
        // Reset all
        document.querySelectorAll('.condition-card').forEach(card => {
            card.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-500/20', 'bg-indigo-50/10');
            card.classList.add('border-slate-200', 'bg-white');
            const tick = card.querySelector('.check-mark');
            if (tick) tick.remove();
        });

        // Activate selected
        element.classList.remove('border-slate-200', 'bg-white');
        element.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-500/20', 'bg-indigo-50/10');

        // Add Premium Tick
        element.insertAdjacentHTML('beforeend', `
        <div class="check-mark absolute -top-1 -right-1">
            <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-check text-white text-[10px]"></i>
            </div>
        </div>
    `);
    }

    // --- QUANTITY ADJUST ---
    window.adjustQuantity = function (change) {
        const input = document.getElementById('inputQuantity');
        let val = parseInt(input.value) || 0;
        val += change;
        if (val < 1) val = 1;
        input.value = val;
    }

    // --- PRICE FORMATTING ---
    const displayPrice = document.getElementById('displayPrice');
    const realPrice = document.getElementById('realPrice');

    window.setPrice = function (val) {
        realPrice.value = val;
        displayPrice.value = new Intl.NumberFormat('vi-VN').format(val);
    }

    displayPrice.addEventListener('input', function (e) {
        let rawValue = this.value.replace(/\D/g, '');
        if (rawValue === '') {
            this.value = '';
            realPrice.value = '';
        } else {
            let numberValue = parseInt(rawValue, 10);
            this.value = new Intl.NumberFormat('vi-VN').format(numberValue);
            realPrice.value = numberValue;
        }
    });

    // --- CHAR COUNT ---
    inputName.addEventListener('input', function () {
        document.getElementById('nameCount').textContent = this.value.length;
    });

    // --- IMAGE LOGIC ---
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f.file));
        inputImage.files = dt.files;
        if (selectedFiles.length < 2) imgError.classList.remove('hidden'); 
        else imgError.classList.add('hidden');
    }

    function renderPreview() {
        miniPreviewGrid.innerHTML = '';
        selectedFiles.forEach((fileData, index) => {
            const div = document.createElement('div');
            div.className = 'relative aspect-square rounded-xl overflow-hidden group shadow-sm border border-slate-100 cursor-pointer';
            div.innerHTML = `
            <img src="${fileData.dataUrl}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center gap-2 backdrop-blur-[2px]">
                <button type="button" onclick="removeImage(${index})" class="w-8 h-8 rounded-full bg-white/20 hover:bg-red-500 text-white flex items-center justify-center transition-colors backdrop-blur-md">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
                <span class="text-white text-[10px] font-medium tracking-wide">XÓA ẢNH</span>
            </div>
        `;
            miniPreviewGrid.appendChild(div);
        });
    }

    window.removeImage = function (index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreview();
    }

    inputImage.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        if (!files.length) return;
        const remaining = 9 - selectedFiles.length;
        const toAdd = files.slice(0, remaining);
        toAdd.forEach(file => {
            const reader = new FileReader();
            reader.onload = function (ev) {
                selectedFiles.push({ file: file, dataUrl: ev.target.result });
                updateFileInput();
                renderPreview();
            }
            reader.readAsDataURL(file);
        });
        this.value = '';
    });

    // --- CATEGORY PICKER ---
    initCategoryPicker();
});

/**
 * Initialize Category Picker
 * Requires: window.categoryData to be set from PHP
 */
function initCategoryPicker() {
    const categoryData = window.categoryData || [];
    
    const categoryTrigger = document.getElementById('categoryTrigger');
    const categoryPanel = document.getElementById('categoryPanel');
    const categoryDisplay = document.getElementById('categoryDisplay');
    const inputCategoryId = document.getElementById('inputCategoryId');
    const categoryArrow = document.getElementById('categoryArrow');
    const parentList = document.getElementById('parentCategoryList');
    const childList = document.getElementById('childCategoryList');
    const categoryContainer = document.getElementById('categoryContainer');

    if (!categoryTrigger || !categoryPanel) return;

    categoryTrigger.addEventListener('click', (e) => {
        categoryPanel.classList.toggle('hidden');
        categoryArrow.classList.toggle('rotate-180');
        e.stopPropagation();
    });

    document.addEventListener('click', (e) => {
        if (!categoryContainer.contains(e.target)) {
            categoryPanel.classList.add('hidden');
            categoryArrow.classList.remove('rotate-180');
        }
    });

    function renderParents() {
        parentList.innerHTML = '';
        categoryData.forEach((parent) => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 p-3 hover:bg-indigo-50 cursor-pointer group transition-all border-b border-slate-50';
            
            // Icon Logic
            let iconHtml = '';
            if (parent.image) {
                iconHtml = `<img src="${parent.image}" class="w-8 h-8 object-contain rounded-full bg-indigo-50 p-1">`;
            } else if (parent.icon) {
                iconHtml = `<div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center"><i class="fa-solid ${parent.icon} text-sm text-indigo-400"></i></div>`;
            } else {
                iconHtml = `<div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-folder text-sm text-slate-400"></i></div>`;
            }

            div.innerHTML = `
                ${iconHtml}
                <span class="flex-1 text-sm text-slate-700 group-hover:text-indigo-700 font-medium">${parent.name}</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-indigo-400"></i>
            `;

            div.addEventListener('click', (e) => {
                e.stopPropagation();
                // Highlight selected parent
                Array.from(parentList.children).forEach(c => c.classList.remove('bg-indigo-50', 'border-l-2', 'border-indigo-500'));
                div.classList.add('bg-indigo-50', 'border-l-2', 'border-indigo-500');
                
                // Check if parent has children
                if (parent.children && parent.children.length > 0) {
                    renderChildren(parent);
                } else {
                    // No children - select parent directly
                    categoryDisplay.value = parent.name;
                    inputCategoryId.value = parent.id;
                    categoryPanel.classList.add('hidden');
                    categoryArrow.classList.remove('rotate-180');
                }
            });
            parentList.appendChild(div);
        });
    }

    function renderChildren(parent) {
        childList.innerHTML = '';
        
        // Header showing parent name
        const header = document.createElement('div');
        header.className = 'px-2 py-2 text-xs font-bold text-indigo-600 border-b border-slate-100 mb-2';
        header.textContent = parent.name;
        childList.appendChild(header);

        parent.children.forEach(child => {
            const div = document.createElement('div');
            div.className = 'p-2.5 hover:text-indigo-600 hover:bg-white cursor-pointer rounded-lg text-sm text-slate-600 transition-colors flex items-center justify-between mb-1 group';
            div.innerHTML = `
                <span>${child.name}</span>
                <i class="fa-solid fa-check text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            `;

            div.addEventListener('click', (e) => {
                e.stopPropagation();
                categoryDisplay.value = `${parent.name} > ${child.name}`;
                inputCategoryId.value = child.id;
                categoryPanel.classList.add('hidden');
                categoryArrow.classList.remove('rotate-180');
            });
            childList.appendChild(div);
        });
    }

    renderParents();
}
