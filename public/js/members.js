import { getAllMembers, deleteMember } from "./firebase-db.js";

const tableBody = document.querySelector("#membersTable tbody");

async function loadMembers() {
  tableBody.innerHTML = "";
  const members = await getAllMembers();
  for (const key in members) {
    const m = members[key];
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td><input type="checkbox" data-key="${key}"></td>
      <td>${m.id}</td>
      <td>${m.name}</td>
      <td>${m.tel}</td>
      <td>${m.addr}</td>
      <td>${m.remark}</td>
      <td>${m.level}</td>
    `;
    tableBody.appendChild(tr);
  }
}

document.getElementById("deleteBtn").addEventListener("click", () => {
  const checked = tableBody.querySelectorAll("input[type=checkbox]:checked");
  checked.forEach(cb => {
    deleteMember(cb.dataset.key);
  });
  setTimeout(loadMembers, 500); // 삭제 후 재로딩
});

loadMembers();
