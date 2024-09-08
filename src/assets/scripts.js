document.addEventListener("DOMContentLoaded", () => {
    initializeFileTree();
    updateButtonStates();

    const directoryNameInput = document.querySelector("#directory-name");
    const fileUploadInput = document.querySelector("#file-upload");
    const downloadBtn = document.querySelector("#download-btn");
    const addFolderBtn = document.querySelector("#add-folder-btn");
    const addFileBtn = document.querySelector("#add-file-btn");
    const deleteBtn = document.querySelector("#delete-btn");

    directoryNameInput.addEventListener("input", updateButtonStates);
    fileUploadInput.addEventListener("change", uploadFile);
    downloadBtn.addEventListener("click", downloadFile);

    addFolderBtn.addEventListener("click", addDirectory);
    addFileBtn.addEventListener("click", () => fileUploadInput.click());
    deleteBtn.addEventListener("click", deleteItem);
});

function initializeFileTree() {
    const fileItems = document.querySelectorAll("#file-structure li");
    const controlForm = document.querySelector("#control-form");
    const selectedFile = document.querySelector("#selected-file");

    if (fileItems.length === 0) {
        selectedFile.textContent = "Корневой каталог";
        controlForm.dataset.selectedId = null;
        controlForm.dataset.selectedType = "directory";
    } else {
        fileItems.forEach((li) => {
            li.addEventListener("click", (event) => {
                handleFileSelection(event, li);
            });
        });
    }
}

function handleFileSelection(event, li) {
    event.stopPropagation();

    document.querySelectorAll("#file-structure li").forEach((item) => {
        item.classList.remove("selected");
    });

    li.classList.add("selected");

    const selectedId = li.getAttribute("data-id");
    const selectedType = li.getAttribute("data-type");
    const selectedText = li.childNodes[0].nodeValue.trim();

    document.querySelector("#selected-file").textContent = buildFullPath(li);
    const controlForm = document.querySelector("#control-form");
    controlForm.dataset.selectedId = selectedId;
    controlForm.dataset.selectedType = selectedType;

    const directoryNameInput = document.querySelector("#directory-name");
    directoryNameInput.disabled = selectedType === "file";

    const managmentInput = document.querySelector(".managment__input");

    if (selectedType === "file") {
        managmentInput.value = "";
    }

    if (selectedType === "file" && isImageFile(selectedText)) {
        displayImage(selectedText);
    } else {
        displayDefaultImage();
    }

    updateButtonStates();
}

function buildFullPath(element) {
    let path = element.childNodes[0].nodeValue.trim();
    let parent = element.parentElement.closest("li");

    while (parent) {
        path = `${parent.childNodes[0].nodeValue.trim()}/${path}`;
        parent = parent.parentElement.closest("li");
    }

    return `${path}${
        element.getAttribute("data-type") === "directory" ? "/" : ""
    }`;
}

function updateButtonStates() {
    const dirname = document.querySelector("#directory-name").value.trim();
    const selectedType =
        document.querySelector("#control-form").dataset.selectedType || "";
    const selectedId =
        document.querySelector("#control-form").dataset.selectedId || null;

    const addFolderBtn = document.querySelector("#add-folder-btn");
    const addFileBtn = document.querySelector("#add-file-btn");
    const deleteBtn = document.querySelector("#delete-btn");
    const downloadBtn = document.querySelector("#download-btn");

    addFolderBtn.disabled = !(dirname && selectedType !== "file");
    addFileBtn.disabled = selectedType !== "directory";
    deleteBtn.disabled = !selectedId;
    downloadBtn.disabled = selectedType !== "file";
}

function isImageFile(fileName) {
    const imageExtensions = [".jpg", ".jpeg", ".png", ".gif"];
    return imageExtensions.includes(
        fileName.slice(fileName.lastIndexOf(".")).toLowerCase()
    );
}

function displayImage(fileName) {
    const selectedFilePath = document
        .querySelector("#selected-file")
        .textContent.trim();
    const imageContainer = document.querySelector("#image-preview");

    imageContainer.innerHTML = `<img src="uploads/${selectedFilePath}" alt="Image">`;
    imageContainer.style.display = "block";
}

function displayDefaultImage() {
    const imageContainer = document.querySelector("#image-preview");
    imageContainer.innerHTML = `<img src="assets/img/no-photo.png" alt="No Image">`;
    imageContainer.style.display = "block";
}

async function addDirectory() {
    const dirname = document.querySelector("#directory-name").value.trim();
    const parentId =
        document.querySelector("#control-form").dataset.selectedId || null;

    if (!dirname) return;

    try {
        const response = await fetch("index.php?action=add_directory", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ dirname, parent_id: parentId }),
        });

        const data = await response.text();
        if (response.ok) {
            if (data.includes("Каталог добавлен")) {
                location.reload();
            }
        } else {
            alert(`Ошибка: ${data}`);
        }
    } catch (error) {
        alert("Ошибка при добавлении каталога:", error);
    }
}

async function uploadFile() {
    const form = document.querySelector("#control-form");
    const parentId = form.dataset.selectedId || null;

    if (!parentId) return;

    const fileInput = document.querySelector("#file-upload");
    const file = fileInput.files[0];

    if (!file) return;

    try {
        const formData = new FormData();
        formData.append("file", file);
        formData.append("parent_id", parentId);

        const response = await fetch("index.php?action=upload_file", {
            method: "POST",
            body: formData,
        });

        const data = await response.text();

        if (response.ok) {
            if (data.includes("Файл успешно загружен")) {
                location.reload();
            }
        } else {
            alert(`Ошибка: ${data}`);
        }
    } catch (error) {
        alert("Произошла ошибка во время загрузки файла.");
    }
}

async function deleteItem() {
    const itemId = document.querySelector("#control-form").dataset.selectedId;

    if (!itemId) return;

    try {
        const response = await fetch("index.php?action=delete_item", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({ id: itemId }),
        });

        const data = await response.text();

        if (response.ok) {
            if (data.includes("Элемент удален")) {
                location.reload();
            }
        } else {
            alert(`Ошибка: ${data}`);
        }
    } catch (error) {
        alert("Ошибка при удалении элемента:", error);
    }
}

function downloadFile() {
    const selectedText = document
        .querySelector("#selected-file")
        .textContent.trim();
    const selectedType =
        document.querySelector("#control-form").dataset.selectedType;

    if (selectedType === "file") {
        const downloadLink = document.createElement("a");
        downloadLink.href = `/uploads/${encodeURIComponent(selectedText)}`;
        downloadLink.download = selectedText.substring(
            selectedText.lastIndexOf("/") + 1
        );
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
}
