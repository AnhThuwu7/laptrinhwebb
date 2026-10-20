"use strict";

// C1: Dữ liệu mẫu được lưu trong mảng đối tượng, không viết từng công việc trong HTML.
const items = [
  { id: 1, title: "Viết báo cáo HTML", dueDate: "2026-10-20", priority: "Cao", status: "pending" },
  { id: 2, title: "Ôn Flexbox", dueDate: "2026-10-23", priority: "Trung bình", status: "pending" },
  { id: 3, title: "Làm bài DOM", dueDate: "2026-10-19", priority: "Cao", status: "done" },
  { id: 4, title: "Đọc tài liệu CSS", dueDate: "2026-10-25", priority: "Thấp", status: "pending" },
  { id: 5, title: "Thiết kế trang cá nhân", dueDate: "2026-10-21", priority: "Trung bình", status: "done" }
];

const taskForm = document.querySelector("#task-form");
const titleInput = document.querySelector("#task-title");
const dueDateInput = document.querySelector("#task-due-date");
const priorityInput = document.querySelector("#task-priority");
const formError = document.querySelector("#form-error");
const searchInput = document.querySelector("#search-input");
const statusFilter = document.querySelector("#status-filter");
const taskList = document.querySelector("#task-list");
const taskCount = document.querySelector("#task-count");
const emptyMessage = document.querySelector("#empty-message");

let nextId = Math.max(...items.map((item) => item.id)) + 1;

function formatDate(dateString) {
  if (!dateString) return "Chưa đặt hạn";
  const parts = dateString.split("-");
  if (parts.length !== 3) return dateString;
  return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

function getPriorityClass(priority) {
  if (priority === "Cao") return "priority-high";
  if (priority === "Thấp") return "priority-low";
  return "priority-medium";
}

// C1 + C3: Lọc dữ liệu rồi hiển thị danh sách lên DOM.
function renderTasks() {
  const keyword = searchInput.value.trim().toLocaleLowerCase("vi");
  const selectedStatus = statusFilter.value;

  const filteredItems = items.filter((item) => {
    const matchesTitle = item.title.toLocaleLowerCase("vi").includes(keyword);
    const matchesStatus = selectedStatus === "all" || item.status === selectedStatus;
    return matchesTitle && matchesStatus;
  });

  taskList.replaceChildren();

  filteredItems.forEach((item) => {
    const card = document.createElement("article");
    card.className = "task-card";

    const main = document.createElement("div");
    main.className = "task-main";

    const checkbox = document.createElement("input");
    checkbox.className = "task-checkbox";
    checkbox.type = "checkbox";
    checkbox.checked = item.status === "done";
    checkbox.setAttribute("aria-label", `Đánh dấu hoàn thành: ${item.title}`);
    checkbox.addEventListener("change", () => toggleTaskStatus(item.id));

    const info = document.createElement("div");
    info.className = "task-info";

    const title = document.createElement("p");
    title.className = `task-title${item.status === "done" ? " is-done" : ""}`;
    title.textContent = item.title;

    const meta = document.createElement("div");
    meta.className = "task-meta";

    const priorityBadge = document.createElement("span");
    priorityBadge.className = `badge ${getPriorityClass(item.priority)}`;
    priorityBadge.textContent = `Ưu tiên: ${item.priority}`;

    const dueDate = document.createElement("span");
    dueDate.textContent = `Hạn: ${formatDate(item.dueDate)}`;

    const statusBadge = document.createElement("span");
    statusBadge.className = `badge status-badge${item.status === "done" ? " done" : ""}`;
    statusBadge.textContent = item.status === "done" ? "Đã hoàn thành" : "Chưa hoàn thành";

    meta.append(priorityBadge, dueDate, statusBadge);
    info.append(title, meta);
    main.append(checkbox, info);

    const actions = document.createElement("div");
    actions.className = "task-actions";

    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.className = "button button-delete";
    deleteButton.textContent = "Xóa";
    deleteButton.setAttribute("aria-label", `Xóa công việc: ${item.title}`);
    deleteButton.addEventListener("click", () => deleteTask(item.id));

    actions.append(deleteButton);
    card.append(main, actions);
    taskList.append(card);
  });

  const completedCount = items.filter((item) => item.status === "done").length;
  taskCount.textContent = `Hiển thị ${filteredItems.length}/${items.length} công việc · Đã hoàn thành ${completedCount}/${items.length}`;
  emptyMessage.hidden = filteredItems.length !== 0;
}

// C2: Kiểm tra form, thêm công việc hợp lệ và làm trống form.
taskForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const title = titleInput.value.trim();
  if (!title) {
    formError.textContent = "Bạn hãy nhập tên công việc nhé.";
    titleInput.setAttribute("aria-invalid", "true");
    titleInput.focus();
    return;
  }

  formError.textContent = "";
  titleInput.removeAttribute("aria-invalid");

  items.unshift({
    id: nextId++,
    title,
    dueDate: dueDateInput.value,
    priority: priorityInput.value,
    status: "pending"
  });

  taskForm.reset();
  priorityInput.value = "Trung bình";
  renderTasks();
  titleInput.focus();
});

// C3: Tìm kiếm theo tên và lọc trạng thái, không tải lại trang.
searchInput.addEventListener("input", renderTasks);
statusFilter.addEventListener("change", renderTasks);

// C4: Cập nhật trạng thái hoặc xóa đúng công việc dựa trên ID.
function toggleTaskStatus(id) {
  const task = items.find((item) => item.id === id);
  if (!task) return;
  task.status = task.status === "done" ? "pending" : "done";
  renderTasks();
}

function deleteTask(id) {
  const index = items.findIndex((item) => item.id === id);
  if (index === -1) return;
  items.splice(index, 1);
  renderTasks();
}

// Hiển thị 5 công việc mẫu ngay khi mở trang.
renderTasks();
