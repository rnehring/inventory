import { Modal } from 'flowbite';

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
    closable: false,
    onHide: () => {
        console.log('in here');
        window.location.href = "users/delete/" + modal.userid;
    }
};

// instance options object
const instanceOptions = {
    id: 'deleteModal',
    override: true
};

const modal = new Modal($targetEl, options, instanceOptions);


function openModal(e){
    modal.userid = e.currentTarget.dataset.userid;
    console.log(modal.userid);
    modal.show()
}

