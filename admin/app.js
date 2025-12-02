document.addEventListener("DOMContentLoaded", async () => {
  const app = document.getElementById("flamingo-crud-app");
  app.innerHTML = "<h2>Loading…</h2>";

  try {
    const res = await fetch("/wp-json/flamingo-crud/v1/messages");
    const data = await res.json();

    if (!data || data.length === 0) {
      app.innerHTML = "<p>No messages found.</p>";
      return;
    }

    app.innerHTML = `
      <h1 style="margin-bottom: 20px;">Flamingo Messages</h1>
      <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <thead>
          <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          ${data.map(
            m => `
            <tr>
              <td>${m.id}</td>
              <td>${m.subject}</td>
              <td>${m.from_name}</td>
              <td>${m.from_email}</td>
              <td>${m.created_at}</td>
            </tr>`
          ).join("")}
        </tbody>
      </table>
    `;
  } catch (err) {
    console.error(err);
    app.innerHTML = "<p>Error loading messages.</p>";
  }
});
