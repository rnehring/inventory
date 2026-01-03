import { Modal } from 'flowbite';
import {
    showToast
} from './app';

const deleteBtns = document.querySelectorAll(".delete-button");
console.log(deleteBtns);
deleteBtns.forEach((delBtn) => {
    delBtn.addEventListener("click", openModal);
});

// set the modal menu element
const $targetEl = document.getElementById('deleteModal');

// options with default values
const options = {
    placement: 'center-center',
    backdrop: 'static',
    backdropClasses:
        'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
    closable: false
};

// instance options object
const instanceOptions = {
    id: 'deleteModal',
    override: true
};

const modal = new Modal($targetEl, options, instanceOptions);


function openModal(e){
    modal.notagid = e.currentTarget.dataset.notagid;
    console.log(modal.notagid);
    modal.show()
}

document.getElementById('yesDelete').addEventListener('click', function() {
    if (modal.notagid) {
        modal.hide(); // Close modal first

        // Use axios for cleaner handling
        axios.post('/notag/delete/' + modal.notagid)
            .then(function(response) {
                // Remove the row from the table
                const row = document.querySelector(`[data-notagid="${modal.notagid}"]`)
                    .closest('tr');
                row.remove();

                showToast('No Tag Part deleted successfully!');
            })
            .catch(function(error) {
                showToast('Failed to delete part', 6000, true);
            });
    }
});
