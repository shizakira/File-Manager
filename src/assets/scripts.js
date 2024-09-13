const fileStructure = document.querySelector("#file-structure");
const directoryNameInput = document.querySelector("#directory-name");
const allButtons = document.querySelectorAll("button");
const btnAddFolder = document.querySelector("#add-folder-btn");
const btnAddFile = document.querySelector("#add-file-btn");
const btnDelete = document.querySelector("#delete-btn");
const btnDownload = document.querySelector("#download-btn");
const chosenPath = document.querySelector(".content__path");
const uploadInput = document.querySelector("#file-upload");

let selectedElem;

function updateElementStates(event) {
    selectedElem?.classList?.remove("selected");
    selectedElem = event.target;
    selectedElem.classList.add("selected");
}

function updateButtonStates() {
    const isDirectory = selectedElem?.dataset.type === "directory";
    const isFile = selectedElem?.dataset.type === "file";
    const isInputValue = directoryNameInput.value === "";
    const root = !isDirectory && !isFile;

    if (root) {
        directoryNameInput.disabled = false;
        btnAddFolder.disabled = isInputValue;
    } else {
        btnAddFolder.disabled = !selectedElem || isFile || isInputValue;
        btnAddFile.disabled = !isDirectory;
        directoryNameInput.disabled = !isDirectory;
        btnDelete.disabled = !selectedElem;
        btnDownload.disabled = !isFile;

        directoryNameInput.value = isFile ? "" : directoryNameInput.value;
    }
}

function updateChosenPath() {
    chosenPath.textContent = getFullPath(selectedElem);
}

function getFullPath(elem) {
    let fullPath = [];

    while (elem && elem !== fileStructure) {
        if (elem.tagName === "LI") {
            fullPath.unshift(elem.firstChild.nodeValue.trim());
        }

        elem = elem.parentElement;
    }

    return fullPath.join("/");
}

function displayImage(event) {
    const fileType = event.target.dataset.type;
    const fileName = event.target.textContent.trim();
    const imagePreview = document.querySelector("#image-preview img");
    const defaultImage = "assets/img/no-photo.png";
    const isImageFile = /\.(jpg|jpeg|png|gif)$/i.test(fileName);

    if (fileType !== "file" || !isImageFile) {
        imagePreview.src = defaultImage;
        return;
    }

    imagePreview.src = `uploads/${getFullPath(event.target)}`;
}

async function downloadFile() {
    const selectedFilePath = getFullPath(selectedElem);

    try {
        const response = await fetch(`uploads/${selectedFilePath}`);
        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");

        a.href = url;
        a.download = selectedFilePath;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    } catch (error) {
        alert(error);
    }
}

async function addDirectory() {
    const dirname = directoryNameInput.value.trim();
    const parentId = selectedElem?.dataset.id || null;

    try {
        const response = await fetch("index.php?action=add_directory", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ dirname, parent_id: parentId }),
        });

        const data = await response.text();

        response.ok ? location.reload() : alert(data);
    } catch (error) {
        alert(error);
    }
}

async function uploadFile() {
    const parentId = selectedElem?.dataset.id || null;

    const file = uploadInput.files[0];
    const formData = new FormData();

    formData.append("file", file);
    formData.append("parent_id", parentId);

    try {
        const response = await fetch("index.php?action=upload_file", {
            method: "POST",
            body: formData,
        });

        const data = await response.text();

        response.ok ? location.reload() : alert(data);
    } catch (error) {
        alert(error);
    }
}

async function deleteElement() {
    try {
        const response = await fetch("index.php?action=delete_item", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ id: selectedElem?.dataset.id }),
        });

        const data = await response.text();

        response.ok ? location.reload() : alert(data);
    } catch (error) {
        alert(error);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    allButtons.forEach((button) => {
        button.disabled = true;
    });

    selectedElem = null;
});

fileStructure.addEventListener("click", (event) => {
    displayImage(event);
    updateElementStates(event);
    updateButtonStates();
    updateChosenPath();
});

directoryNameInput.addEventListener("input", updateButtonStates);
uploadInput.addEventListener("change", uploadFile);
btnAddFolder.addEventListener("click", addDirectory);
btnAddFile.addEventListener("click", () => uploadInput.click());
btnDelete.addEventListener("click", deleteElement);
btnDownload.addEventListener("click", downloadFile);
