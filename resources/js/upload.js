import 'flowbite';

// Drag and Drop functionality for inventory CSV upload
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('upload-inventory-csv');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const removeFileBtn = document.getElementById('remove-file');
    const uploadBtn = document.getElementById('upload-csv');

    if (!dropZone || !fileInput) {
        return; // Elements not found, exit early
    }

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-gray-700');
        dropZone.classList.remove('border-gray-600');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-gray-700');
        dropZone.classList.add('border-gray-600');
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        handleFiles(files);
    }

    // Handle file selection via click
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        console.log('handleFiles called with:', files.length, 'files');
        if (files.length === 0) return;

        const file = files[0];
        console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

        // Validate file type
        if (!file.name.endsWith('.csv')) {
            alert('Please upload a CSV file only.');
            return;
        }

        // Update the file input with the dropped file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        console.log('File input updated. Files in input:', fileInput.files.length);
        console.log('File input value:', fileInput.value);

        // Show file info
        fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
        fileInfo.classList.remove('hidden');
        uploadBtn.disabled = false;
    }

    // Remove file
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            fileInfo.classList.add('hidden');
            uploadBtn.disabled = true;
        });
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Disable submit button initially if no file selected
    if (uploadBtn && fileInput.files.length === 0) {
        uploadBtn.disabled = true;
    }
    
    // Add form submit handler for debugging
    const form = document.getElementById('inventory-upload-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitting...');
            console.log('File input files:', fileInput.files);
            console.log('File input name:', fileInput.name);
            console.log('File input value:', fileInput.value);
            
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a file before uploading');
                return false;
            }
        });
    }
});
